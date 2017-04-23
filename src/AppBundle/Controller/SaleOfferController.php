<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\SaleOffer;
use AppBundle\Form\FilterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

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
     * @param $category ProductCategory
     */
    public function indexAction(ProductCategory $category = null)
    {

        $repo = $this->getDoctrine()->getRepository(SaleOffer::class);
        $query = $repo->createQueryBuilder('so');
        if(empty($category)){
            $query = $query->where('so.quantity > 0')
                ->orderBy('so.showOrder')
                ->getQuery();
        }
        else{
            $query = $query->where($query->expr()->andX(
                $query->expr()->gt('so.quantity', '?1'),
                $query->expr()->eq('so.product.category_id', '?2')
            ))
                ->orderBy('so.showOrder')
                ->setParameters([
                    1 => 0,
                    2 => $category->getId()
                ])
                ->getQuery();
        }
        $saleOffers = $query->getResult();

        $category = new ProductCategory();
        $filterForm = $this->createForm(FilterType::class, $category);

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
        $deleteForm = $this->createDeleteForm($saleOffer);

        return $this->render('saleoffer/show.html.twig', array(
            'saleOffer' => $saleOffer,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($saleOffer);
        $editForm = $this->createForm('AppBundle\Form\SaleOfferType', $saleOffer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('saleoffer_edit', array('id' => $saleOffer->getId()));
        }

        return $this->render('saleoffer/edit.html.twig', array(
            'saleOffer' => $saleOffer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a saleOffer entity.
     *
     * @Route("/{id}", name="saleoffer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SaleOffer $saleOffer)
    {
        $form = $this->createDeleteForm($saleOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($saleOffer);
            $em->flush();
        }

        return $this->redirectToRoute('saleoffer_index');
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
