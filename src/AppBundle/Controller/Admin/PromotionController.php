<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionCategoryType;
use AppBundle\Form\PromotionGeneralType;
use AppBundle\Form\PromotionSaleOfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PromotionController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/admin/promotion")
 */
class PromotionController extends Controller
{
    /**
     * list promotions
     * @Route("/", name="promotion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        /**
         * @var $promotions Promotion[]
         */
        $promotions = $this->getDoctrine()
            ->getRepository(Promotion::class)
            ->getActivePromotions();
        return $this->render('admin/promotion/index.html.twig',[
            'promotions' => $promotions,
        ]);
    }

    /**
     * @Route("/new/general", name="promotion_new_general")
     * @Method({"GET", "POST"})
     */
    public function newGeneralAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionGeneralType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();
            $promotion->setIsGeneral(Promotion::GENERAL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', 'Promotion successfully added!');
            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('admin/promotion/new.html.twig', array(
            'promotion' => $promotion,
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/new/category", name="promotion_new_category")
     * @Method({"GET", "POST"})
     */
    public function newCategoriesPromotion(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionCategoryType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $promotion = $form->getData();
            $promotion->setIsGeneral(Promotion::NOT_GENERAL);
            $categories = $promotion->getCategories();
            foreach ($categories as $category){
                $category->getPromotions()->add($promotion);
                $em->persist($category);
            }
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', 'Promotion successfully added!');
            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('admin/promotion/new.html.twig', array(
            'promotion' => $promotion,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/new/saleoofer", name="promotion_new_saleoffer")
     * @Method({"GET", "POST"})
     */
    public function newSaleOffersPromotion(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionSaleOfferType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /**
             * @var $promotion Promotion
             */
            $promotion = $form->getData();
            $promotion->setIsGeneral(Promotion::NOT_GENERAL);
            $em->persist($promotion);
            $em->flush();

            $this->addFlash('success', 'Promotion successfully added!');
            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('admin/promotion/new.html.twig', array(
            'promotion' => $promotion,
            'form' => $form->createView(),
        ));
    }
}
