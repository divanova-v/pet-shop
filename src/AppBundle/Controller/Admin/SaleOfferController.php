<?php
/**
 * Created by PhpStorm.
 * User: divanova.v
 * Date: 23-Apr-17
 * Time: 19:36
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User;
use AppBundle\Entity\User2Product;
use AppBundle\Form\SaleOfferType;
use AppBundle\Form\UserSaleOfferNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class SaleOfferController
 * @Route("admin/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SaleOfferController extends Controller
{
    /**
     * @Route("/{id}/offers", name="user_products_in_sale")
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userSaleOffersAction(User $user)
    {

        $userOffers = $this
            ->getDoctrine()
            ->getRepository(SaleOffer::class)
            ->getOffersByUserId($user->getId());

        return $this->render('admin/saleoffer/index.html.twig', [
            'userOffers' => $userOffers,
            'user' => $user
        ]);

    }

    /**
     * Creates a new saleOffer entity.
     *
     * @Route("/{id}/offer/new", name="user_saleoffer_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_USER')")
     */
    public function newAction(Request $request, User $user)
    {
        $saleOffer = new Saleoffer();
        $form = $this->createForm(UserSaleOfferNewType::class, $saleOffer, ['userId' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /**
             * @var $saleOffer SaleOffer
             */
            $userProduct = $this->getDoctrine()
                ->getRepository(User2Product::class)
                ->getUserProductByUserIdAndProductId($user->getId(), $saleOffer->getProduct()->getId());
            if($userProduct->getQuantity() >= $saleOffer->getQuantity()){
                $userProduct->setQuantity($userProduct->getQuantity() - $saleOffer->getQuantity());
                $em->persist($userProduct);
                $saleOffer = $form->getData();
                $saleOffer->setUser($user);
                $saleOffer->setCreatedOn(new \DateTime());
                $saleOffer->setUpdatedOn(new \DateTime());

                $em->persist($saleOffer);
                $em->flush();
                if($this->getUser()->isAdmin()){
                    return $this->redirectToRoute('user_products_in_sale', array('id' => $user->getId()));
                }
                else{
                    return $this->redirectToRoute('profile_index');
                }
            }
            else{
                $this->addFlash('error', 'Max quantity is the available quantity: ' . $userProduct->getQuantity());
            }
        }

        return $this->render('saleoffer/new.html.twig', array(
            'saleOffer' => $saleOffer,
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

}