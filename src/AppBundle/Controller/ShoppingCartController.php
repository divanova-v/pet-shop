<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User;
use AppBundle\Entity\User2Product;
use AppBundle\Repository\SaleOfferRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShoppingCartController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER') or has_role('ROLE_EDITOR') or has_role('ROLE_ADMIN')")
 */
class ShoppingCartController extends Controller
{
    /**
     * @Route("/cart", name="shopping_cart_index")
     * @return \Symfony\Component\HttpFoundation\Response
     *
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
            $form = $this->createFormBuilder()->getForm();
            $calc = $this->get('price_calculator');
            foreach ($offers as $offer) {
                /**
                 * @var $offer SaleOffer
                 */
                $offer->setQuantity($sessionCart[$offer->getId()]);
                $offer->setFinalPrice($calc->calculate($offer));
                $totalPrice += $sessionCart[$offer->getId()] * $offer->getFinalPrice();
            }
            return $this->render('shoppingCart/index.html.twig', [
                'saleOffers' => $offers,
                'totalPrice' => $totalPrice,
                'form' => $form->createView(),
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
        if($this->getUser() == $offer->getUser()){
            $this->addFlash('info', 'Yuo can not add product that you sell');
            return $this->redirectToRoute('saleoffer_index');
        }

        $data = [];
        $form = $this->createAddToCartForm($data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $formData = $form->getData();

            $session = $request->getSession();
            $cart = $session->get('cart', []);
            $previousQuantity =   isset($cart[$offer->getId()]) ? $cart[$offer->getId()] : 0;
            $cart[$offer->getId()] = $previousQuantity + $formData['quantity'];
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
     * @Route("/cart/checkout", name="cart_checkout")
     * @Method("POST")
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
            $calc = $this->get('price_calculator');
            foreach ($offers as $offer){
                $boughtQuantity = $sessionCart[$offer->getId()];
                if($boughtQuantity <= $offer->getQuantity()){
                    //persist new user's products
                    $user2Product = $this->getDoctrine()
                        ->getRepository(User2Product::class)
                        ->getUserProductByUserIdAndProductId($this->getUser()->getId(), $offer->getProductId());
                    if(empty($user2Product)){
                        $user2Product = new User2Product();
                        $user2Product->setUser($this->getUser());
                        $user2Product->setSaleOffer($offer);
                        $user2Product->setProduct($offer->getProduct());
                        $user2Product->setQuantity($boughtQuantity);
                        $user2Product->setCreatedOn(new \DateTime());
                    }
                    else{
                        $user2Product->setQuantity($user2Product->getQuantity() + $boughtQuantity);
                    }
                    $user2Product->setUpdatedOn(new \DateTime());
                    $em->persist($user2Product);
                    $offer->setFinalPrice($calc->calculate($offer));
                    $price = $user2Product->getQuantity() * $offer->getFinalPrice();
                    $totalPrice += $price;

                    $seller = $offer->getUser();
                    if($seller != null){
                        $seller->setCash($seller->getCash() + $price);
                        $em->persist($seller);
                    }

                    $offer->setQuantity($offer->getQuantity() - $boughtQuantity);
                    $em->persist($offer);
                }
                else{
                    $this->addFlash('info', 'You try to byu more than available quantity. Please change quantity first.');
                    return $this->redirectToRoute('shopping_cart_index');
                }
            }
            /**
             * @var $buyer User
             */
            $buyer = $this->getUser();
            if($buyer->getCash() >= $totalPrice){
                $buyer->setCash($buyer->getCash() - $totalPrice);
                $em->persist($buyer);
                $em->flush();
                $this->get('session')->remove('cart');
                $this->addFlash('success', 'Successfully checked out a cart.');
                return $this->redirectToRoute('saleoffer_index');
            }
            else{
                $this->addFlash('info', 'You do not have enough cash. Please remove some items first.');
                return $this->redirectToRoute('shopping_cart_index');
            }
        }

        $this->addFlash('info', 'You tried to check out an empty cart. Please add products frirst');
        return $this->redirectToRoute('saleoffer_index');
    }

    /**
     * @Route("/cart/{id}/remove", name="cart_remove_item")
     * @Method("POST")
     * @param SaleOffer $saleOffer
     */
    public function removeAction(SaleOffer $saleOffer)
    {
        $sessionCart = $this->get('session')->get('cart',[]);
        if(array_key_exists($saleOffer->getId(), $sessionCart)){
            unset($sessionCart[$saleOffer->getId()]);
            $this->get('session')->set('cart',$sessionCart);
            $this->addFlash('success', 'You removed product from your cart');
        }
        else{
            $this->addFlash('error', 'You are trying to remove product that is not in your cart');
        }
        return $this->redirectToRoute('shopping_cart_index');
    }

    /**
     */
    private function createAddToCartForm( $data = []){
        return $this->createFormBuilder($data)
            ->add('quantity', NumberType::class)
            ->getForm();
    }
}
