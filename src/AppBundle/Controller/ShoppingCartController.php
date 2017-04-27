<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User2Product;
use AppBundle\Repository\SaleOfferRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartController extends Controller
{
    /**
     * @Route("/cart", name="shopping_cart_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $sessionCart = $this->get('session')->get('cart', []);
        $offersIds = array_keys($sessionCart);
        $repo = $this->getDoctrine()->getRepository(SaleOffer::class);
        if(!empty($offersIds)){
            /**
             * @var SaleOffer[]
             */
            $offers = $repo->getOffersById($offersIds);
            $totalPrice = 0;
            foreach ($offers as $offer) {
                $offer->setQuantity($sessionCart[$offer->getId()]);
                $totalPrice += $sessionCart[$offer->getId()] * $offer->getPrice();
            }
            return $this->render('shoppingCart/index.html.twig', [
                'saleOffers' => $offers,
                'totalPrice' => $totalPrice,
            ]);
        }
        else{
            return $this->render('shoppingCart/empty.html.twig');
        }
    }

    /**
     * Finds and displays a saleOffer entity.
     *
     * @Route("/saleoffer/{id}", name="saleoffer_add_to_cart")
     * @Method("POST")
     */
    public function addToCartAction(Request $request, SaleOffer $offer)
    {
        $data = [];
        $form = $this->createAddToCartForm($data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $formData = $form->getData();

            $session = $request->getSession();
            $cart = $session->get('cart', []);
            $previousQuantity =   isset($cart[$offer->getId()]) ? $cart[$offer->getId()] : 0;
            $cart[$offer->getId()] = $previousQuantity + $formData['quantity'];
            dump($cart[$offer->getId()] > $offer->getQuantity());
            dump($cart);
            dump($offer->getQuantity());
            if($cart[$offer->getId()] > $offer->getQuantity()){
                $this->addFlash('error', 'You are trying to add '
                    . $cart[$offer->getId()]
                    . ' quantity of this product. Max quantity is the available quantity: '
                    . $offer->getQuantity()
                    . '. '
                    . (($previousQuantity > 0) ? 'You already have ' . $previousQuantity : ''));

                return $this->redirectToRoute('saleoffer_show', ['id' => $offer->getId()]);
            }
            else{
                $session->set('cart', $cart);
                $this->addFlash('success', 'Successfully added ' . $offer->getProduct()->getName() . ' to you shopping cart.');
                return $this->redirectToRoute('saleoffer_index');
            }
        }

        return $this->redirectToRoute('saleoffer_show', ['id' => $offer->getId()]);
    }

    /**
     * checks out users cart
     */
    public function checkoutCartAction()
    {
        $sessionCart = $this->get('session')->get('cart', []);
        if(!empty($sessionCart)){
            $saleOffersRepo = $this->getDoctrine()->getRepository(SaleOffer::class);
            /**
             * @var $offers SaleOffer[]
             */
            $offers = $saleOffersRepo->getOffersById(array_keys($sessionCart));
            $em = $this->getDoctrine()->getManager();
            $totalPrice = 0;
            foreach ($offers as $offer){
                $boughtQuantity = $sessionCart[$offer->getId()];
                if($boughtQuantity <= $offer->getQuantity()){
                    //persist new user's products
                    $user2Product = new User2Product();
                    $user2Product->setUser($this->getUser());
                    $user2Product->setSaleOffer($offer);
                    $user2Product->setProduct($offer->getProduct());
                    $user2Product->setQuantity($boughtQuantity);
                    $user2Product->setCreatedOn(new \DateTime());
                    $user2Product->setUpdatedOn(new \DateTime());
                    $em->persist($user2Product);

                    $price = $user2Product->getQuantity() * $offer->getPrice();
                    $totalPrice += $price;

                    $saler = $offer->getUser();
                    if($saler != null){
                        $saler->setCash($saler->getCash() + $price);
                        $em->persist();
                    }

                    $offer->setQuantity($offer->getQuantity() - $boughtQuantity);
                    $em->persist();
                }
                else{
                    //@TODO try to byu more than available quantity
                }
            }
            $buyer = $this->getUser();
            if($buyer->getCash() <= $totalPrice){
                $buyer->setCash($buyer->getCash() - $totalPrice);
                $em->persist($buyer);
            }
        }

        $this->addFlash('info', 'You tried to check outan empty cart. Please add products frirst');
        return $this->redirectToRoute('saleoffer_index');
    }

    /**
     */
    private function createAddToCartForm( $data = []){
        return $this->createFormBuilder($data)
            ->add('quantity', NumberType::class)
            ->getForm();
    }
}
