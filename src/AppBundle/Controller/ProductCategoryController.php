<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Productcategory controller.
 *
 * @Route("product-category")
 * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_EDITOR')")
 */
class ProductCategoryController extends Controller
{
    /**
     * Lists all productCategory entities.
     *
     * @Route("/", name="product-category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productCategories = $em->getRepository('AppBundle:ProductCategory')->findAll();

        return $this->render('productcategory/index.html.twig', array(
            'productCategories' => $productCategories,
        ));
    }

    /**
     * Creates a new productCategory entity.
     *
     * @Route("/new", name="product-category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $productCategory = new Productcategory();
        $form = $this->createForm('AppBundle\Form\ProductCategoryType', $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productCategory);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Product category is created');

            return $this->redirectToRoute('product-category_index');
        }

        return $this->render('productcategory/new.html.twig', array(
            'productCategory' => $productCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productCategory entity.
     *
     * @Route("/{id}/edit", name="product-category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductCategory $productCategory)
    {
        $editForm = $this->createForm('AppBundle\Form\ProductCategoryType', $productCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Product category is edited');
            return $this->redirectToRoute('product-category_index');
        }

        return $this->render('productcategory/edit.html.twig', array(
            'productCategory' => $productCategory,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a productCategory entity.
     *
     * @Route("/delete/{id}", name="product-category_delete")
     */
    public function deleteAction(Request $request, ProductCategory $productCategory)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($productCategory);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Product category is deleted');
        return $this->redirectToRoute('product-category_index');
    }
}
