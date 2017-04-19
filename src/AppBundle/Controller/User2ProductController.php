<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User2Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * User2product controller.
 *
 * @Route("user2product")
 */
class User2ProductController extends Controller
{
    /**
     * Lists all user2Product entities.
     *
     * @Route("/", name="user2product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user2Products = $em->getRepository('AppBundle:User2Product')->findAll();

        return $this->render('user2product/index.html.twig', array(
            'user2Products' => $user2Products,
        ));
    }

    /**
     * Finds and displays a user2Product entity.
     *
     * @Route("/{id}", name="user2product_show")
     * @Method("GET")
     */
    public function showAction(User2Product $user2Product)
    {

        return $this->render('user2product/show.html.twig', array(
            'user2Product' => $user2Product,
        ));
    }
}
