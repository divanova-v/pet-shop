<?php
/**
 * Created by PhpStorm.
 * User: divanova.v
 * Date: 23-Apr-17
 * Time: 19:17
 */

namespace AppBundle\Controller\Admin;



use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User;
use AppBundle\Entity\User2Product;
use AppBundle\Form\User2ProductEditType;
use AppBundle\Form\User2ProductNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * User2product admin controller.
 * @Route("admin/user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class User2ProductController extends Controller
{
    /**
     * list user products
     *
     * @Route("/{id}/products", name="user_products_index")
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userProductsAction(User $user)
    {
        $userProducts = $this
            ->getDoctrine()
            ->getRepository(User2Product::class)
            ->getUserProductsByUserId($user->getId());

        return $this->render('admin/user2product/index.html.twig', [
            'userProducts' => $userProducts,
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/product/new", name="user2product_new")
     */
    public function newAction(Request$request, User $user)
    {
        $userProduct = new User2Product();
        $form = $this->createForm(User2ProductNewType::class, $userProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();

            $offer = $this->getDoctrine()
                ->getRepository(SaleOffer::class)
                ->getShopSaleOfferByProduct($userProduct->getProduct());
            if($offer->getQuantity() >= $userProduct->getQuantity()) {

                $offer->setQuantity($offer->getQuantity() - $userProduct->getQuantity());

                $userProduct->setUser($user);
                $userProduct->setCreatedOn(new \DateTime());
                $userProduct->setUpdatedOn(new \DateTime());

                $em = $this->getDoctrine()->getManager();
                $em->persist($userProduct);
                $em->persist($offer);
                $em->flush();


                $this->get('session')->getFlashBag()->add('success', "Product was added to user's inventar");

                return $this->redirectToRoute(
                    'user_products_index', [
                        'id' => $user->getId(),
                    ]
                );
            }
            else{
                $this->addFlash(
                    'error',
                        'Please set smaller quantity. You have only '
                        . $offer->getQuantity()
                        . ' of this product');
            }
        }

        return $this->render('admin/user2product/new.html.twig', array(
            'userProduct' => $userProduct,
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * Deletes a user2product entity.
     *
     * @Route("/{id}/delete", name="user2product_delete")
     * @Method({"GET", "DELETE"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, User2Product $userProduct)
    {
        $form = $this->createDeleteForm($userProduct);
        $form->handleRequest($request);

        $userId = $userProduct->getUserId();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userProduct);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', "User's product is deleted");
            return $this->redirectToRoute('user_products_index', ['id' => $userId]);
        }

        return $this->render('admin/delete.html.twig', [
            'message' => "user's product",
            'deleteForm'=> $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a user2product entity.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User2Product $user2Product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user2product_delete', array('id' => $user2Product->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}