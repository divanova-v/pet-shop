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
     */
    public function newAction(Request $request, User $user)
    {
        $saleOffer = new Saleoffer();
        $form = $this->createForm(UserSaleOfferNewType::class, $saleOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $saleOffer SaleOffer
             */
            $saleOffer = $form->getData();
            $saleOffer->setUser($user);
            $saleOffer->setCreatedOn(new \DateTime());
            $saleOffer->setUpdatedOn(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($saleOffer);
            $em->flush();

            return $this->redirectToRoute('user_products_in_sale', array('id' => $saleOffer->getId()));
        }

        return $this->render('saleoffer/new.html.twig', array(
            'saleOffer' => $saleOffer,
            'form' => $form->createView(),
        ));
    }

}