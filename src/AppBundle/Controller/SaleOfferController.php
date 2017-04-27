<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User2Product;
use AppBundle\Form\FilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Saleoffer controller.
 *
 * @Route("saleoffer")
 */
class SaleOfferController extends Controller
{

    /**
     * Lists all saleOffer entities.
     *
     * @Route("/", name="saleoffer_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(SaleOffer::class);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            //filter sale offers
            $category = $filterForm->getData()['category'];
            $saleOffers = $repo->getSaleOffersByCategory($category);
        }
        else{
            $saleOffers = $repo->getAvailableSaleOffers();
        }

        return $this->render('saleoffer/index.html.twig', array(
            'saleOffers' => $saleOffers,
            'filterForm' => $filterForm->createView(),
        ));
    }


    /**
     * Creates a new saleOffer entity.
     *
     * @Route("/new", name="saleoffer_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $saleOffer = new Saleoffer();
        $form = $this->createForm('AppBundle\Form\SaleOfferType', $saleOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($saleOffer);
            $em->flush();

            return $this->redirectToRoute('saleoffer_show', array('id' => $saleOffer->getId()));
        }

        return $this->render('saleoffer/new.html.twig', array(
            'saleOffer' => $saleOffer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a saleOffer entity.
     *
     * @Route("/{id}", name="saleoffer_show")
     * @Method("GET")
     */
    public function showAction(SaleOffer $saleOffer)
    {
        $form = $this->createFormBuilder()
            ->add('quantity', NumberType::class)
            ->getForm();

        return $this->render('saleoffer/show.html.twig', array(
            'saleOffer' => $saleOffer,
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing saleOffer entity.
     *
     * @Route("/{id}/edit", name="saleoffer_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SaleOffer $saleOffer)
    {
        $editForm = $this->createForm('AppBundle\Form\SaleOfferType', $saleOffer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Sale offer is edited');
            return $this->redirectToRoute('user_products_in_sale', ['id' => $saleOffer->getUser()->getId()]);
        }

        return $this->render('saleoffer/edit.html.twig', array(
            'saleOffer' => $saleOffer,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a saleOffer entity.
     *
     * @Route("/{id}/delete", name="saleoffer_delete")
     * @Method({"GET", "DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, SaleOffer $saleOffer)
    {
        $form = $this->createDeleteForm($saleOffer);
        $form->handleRequest($request);

        $userId = $saleOffer->getUserId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($saleOffer);
            $em->flush();

            $this->addFlash('success', 'Sale offer is deleted');
            return $this->redirectToRoute('user_products_in_sale', ['id' => $userId]);
        }

        return $this->render('admin/delete.html.twig', [
            'message' => 'sale offer',
            'deleteForm'=> $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a saleOffer entity.
     *
     * @param SaleOffer $saleOffer The saleOffer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SaleOffer $saleOffer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('saleoffer_delete', array('id' => $saleOffer->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
