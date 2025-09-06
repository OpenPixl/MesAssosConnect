<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Form\Admin\RegistrationFormType;
use App\Repository\Admin\MemberRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, MemberRepository $memberRepository): Response
    {
        $users = $memberRepository->findAll();

        $newuser = new Member();
        $date = (new \DateTime());
        $newuser->setMobilePhone('00 00 00 00 00');
        $newuser->setBirthday($date);
        $newuser->setIsVerified(1);

        $form = $this->createForm(RegistrationFormType::class, $newuser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        // encode the plain password
            $newuser->setPassword(
                $userPasswordHasher->hashPassword(
                    $newuser,
                    $form->get('password')->getData()
                )
            );

            if(!$users){
                $newuser->setRoles(["ROLE_SUPER_ADMIN"]);
            }else{
                $newuser->setRoles(["ROLE_USER"]);
            }
            $entityManager->persist($newuser);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $newuser,
                (new TemplatedEmail())
                    ->from(new Address('contact@clubmicrosaintpierre.fr', 'Contact Numérik&Co'))
                    ->to($newuser->getEmail())
                    ->subject('Vérification de votre mail')
                    ->htmlTemplate('admin/registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email
            return $security->login($newuser, 'form_login', 'main');
        }

        return $this->render('admin/registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre Email a bien été vérifié.');

        return $this->redirectToRoute('app_webapp_public');
    }
}
