<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SaleOffer;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 * @Route("admin/product")
 * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_EDITOR')")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        /**
         * @var $products Product[]
         */
        $products = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->getShopProductsAndOffers();
        foreach ($products as $product){
            $product->setShopOffer($product->getSaleOffers()->current());
        }
        return $this->render('admin/product/index.html.twig', array(
            'products' => $products,

        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $offer = new SaleOffer();
        $product->getSaleOffers()->add($offer);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCategoryId($product->getCategory()->getId());
            $product->setCreatedOn(new \DateTime());
            $product->setUpdatedOn(new \DateTime());
            $offer->setCreatedOn(new \DateTime());
            $offer->setUpdatedOn(new \DateTime());
            /** @var UploadedFile $file */
            $file = $product->getUploadedImage();
            $filename = md5(
                $product->getName()
                        . $product->getCreatedOn()->format('Y-m-d H:i:s'))
                        . '.'
                        . $file->guessExtension();
            $file->move(
                $this->get('kernel')->getRootDir() . '/../web/images/products/',
                $filename);
            $product->setImage($filename);

            $product->addSaleOffer($offer);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($offer);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Product is created');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     * @param $request Request
     * @param $id int
     */
    public function editAction(Request $request, $id)
    {
        /**
         * @var $product Product
         */
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->getShopProductAndOfferByProductId($id);

        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $product->setUpdatedOn(new \DateTime());
            if($product->getUploadedImage() instanceof UploadedFile){
                /** @var UploadedFile $file */
                $file = $product->getImage();
                $filename = md5(
                        $product->getName()
                        . $product->getCreatedOn()->format('Y-m-d H:i:s'))
                    . '.'
                    . $file->guessExtension();
                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/products/',
                    $filename);
                $product->setImage($filename);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Product is edited');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('admin/product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/delete/{id}", name="product_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Product $product)
    {
        if(($product->isDeletable() && true === $this->get('security.authorization_checker')->isGranted('ROLE_EDITOR'))
            || true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            $form = $this->createDeleteForm($product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($product);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Product is deleted');
                return $this->redirectToRoute('product_index');
            }

            return $this->render('admin/delete.html.twig', [
                'message' => 'product',
                'deleteForm' => $form->createView()
            ]);
        }
        else{
            throw $this->createAccessDeniedException('Unable to access this page!');
        }

    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
