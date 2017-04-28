<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @Route("/profile")
 * @Security("has_role('ROLE_USER') or has_role('ROLE_EDITOR') or has_role('ROLE_ADMIN')")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="profile_index")
     */
    public function indexAction()
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $form = $this->createFormBuilder()->getForm();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
