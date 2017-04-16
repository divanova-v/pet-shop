<?php

namespace YudjiePetShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use YudjiePetShopBundle\Form\UserType;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="user_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {

        $auth_utils = $this->get('security.authentication_utils');

        $error = $auth_utils->getLastAuthenticationError();
        $last_username = $auth_utils->getLastUsername();
        return [
            'error' => $error,
            'last_username' =>$last_username
        ];

    }

    /**
     * @Route("/login_check", name="user_check")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkAction(Request $request){
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/register", name="user_register")
     * @Template()
     */
    public function registerAction(Request $request){
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $encrypter = $this->get('security.password_encoder');

            $user->setPassword(
                $encrypter->encodePassword($user, $user->getPasswordRow())
            );

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_login');
        }
        return [
            'register_form' => $form->createView()
        ];
    }
}
