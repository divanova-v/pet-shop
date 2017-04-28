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
        $form = $this->createFormBuilder()
            ->add('quantity', NumberType::class)
            ->getForm();
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
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a saleOffer entity.
     *
     * @Route("/saleoffer/{id}", name="saleoffer_show")
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
     * Creates a new saleOffer entity.
     *
     * @Route("/profile/offer/new", name="saleoffer_new")
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

            return $this->redirectToRoute('user_products_in_sale', array('id' => $saleOffer->getUserId()));
        }

        return $this->render('saleoffer/new.html.twig', array(
            'saleOffer' => $saleOffer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a saleOffer entity.
     *
     * @Route("/saleoffer/{id}/delete", name="saleoffer_delete")
     * @Method({"GET", "DELETE"})
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, SaleOffer $saleOffer)
    {
        if($saleOffer) {
            if ($saleOffer->getUser() == $this->getUser() || $this->getUser()->isAdmin()) {
                $form = $this->createDeleteForm($saleOffer);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    $userProductRepo = $this->getDoctrine()->getRepository(User2Product::class);
                    $userProduct = $userProductRepo->getUserProductByUserIdAndProductId($saleOffer->getUserId(), $saleOffer->getProductId());
                    $userProduct->setQuantity($userProduct->getQuantity() + $saleOffer->getQuantity());
                    $em->persist($userProduct);
                    $em->remove($saleOffer);
                    $em->flush();

                    $this->addFlash('success', 'Sale offer is deleted');
                    if($saleOffer->getUser() == $this->getUser()){
                        return $this->redirectToRoute('profile_index');
                    }
                    else{
                        return $this->redirectToRoute('user_products_in_sale', ['id' => $saleOffer->getUserId()]);
                    }

                }

                return $this->render('admin/delete.html.twig', [
                    'message' => 'sale offer',
                    'deleteForm' => $form->createView(),
                ]);
            }
        }
        $this->addFlash('info', 'You are trying to delete offer that does not exist');
        if($saleOffer->getUser() == $this->getUser()){
            return $this->redirectToRoute('profile_index');
        }
        else{
            return $this->redirectToRoute('user_products_in_sale', ['id' => $saleOffer->getUserId()]);
        }

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
