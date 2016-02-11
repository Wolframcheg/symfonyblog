<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthController extends Controller
{
    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/login", name="login")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();
        $user->setUsername($lastUsername);

        $form = $this->createForm('AppBundle\Form\LoginType', $user, [
            'action' => $this->generateUrl('login_check')
        ]);

        return [
                'form'          => $form->createView(),
                'error'         => $error,
        ];
    }

    /**
     * @Route("/register", name="registration")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listeyour firewall namener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole(User::ROLE_USER);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->authenticateUser($user);//authentificate

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView()
        ];
    }

    private function authenticateUser(User $user)
    {
        $providerKey = 'frontend_area'; // Firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);
    }

}
