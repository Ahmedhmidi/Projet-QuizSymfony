<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPassReqFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/forgotPass", name="forgot_Pass")
     */
    public function ForgottenPass(  Request $request, 
                                    UserRepository $userRepository, 
                                    TokenGeneratorInterface $tokenGenerator,
                                    MailerInterface $mail,
                                    EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPassReqFormType::class);
        $form->handleRequest($request);
         
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData()); 
            if ($user) {
                //on genere un token de reinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetPass($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                //on genere un lien de reinitialisation
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL); 

                //envoi du mail
                $email = (new Email())
                ->from('reply.quiz@gmail.com')
                ->to($user->getEmail())
                ->subject('Reset Password')
                ->html("<p>Bonjour " . $user->getName() . ",</p>
                <p>pour votre demande de reinitialisation de mot de passe veuillez cliquez 
                sur le lien suivant : " . $url . ".</p>
                <p>Merci</p>", "text/html");
                $mail->send($email);  
                $this->addFlash('success', 'Email send with success');
                return $this->redirectToRoute('app_login');

            }
            $this->addFlash('danger', 'A problem has occured');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_pass_req.html.twig', [
            'ReqPassForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgotPass/{token}", name="reset_pass")
     */
    public function resetPass(  string $token, 
                                Request $request, 
                                UserRepository $userRepository, 
                                UserPasswordHasherInterface $passwordHasher): Response
    {
        //on verifie si on a le token dans la base
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_pass'=>$token]);
        // $user = $userRepository->findOneByResetPass($token);
        if($user == null){
            $this->addFlash('danger', 'Invalid Token');
            return $this->redirectToRoute('app_login');
        }
    
            if($request->isMethod('POST')) {
            $user->setResetPass("");

            $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('password')));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");
        }
        return $this->render("security/reset_pass.html.twig",[
            'token'=>$token
        ]);
    }
}
