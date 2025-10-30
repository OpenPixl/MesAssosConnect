<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Member;
use App\Entity\Gestion\Adhesions\Adherent;
use App\Form\Admin\MemberType;
use App\Repository\Admin\MemberRepository;
use App\Repository\Gestion\Associations\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/member')]
class MemberController extends AbstractController
{
    private function generatePassword(int $minLength = 12) : string
    {
        $password = '';
        $wordsUsed = [];

        // Dictionnaire utilisable pour la génération
        $dictionary = [
            "maison", "soleil", "chat", "chien", "voiture",
            "arbre", "pomme", "livre", "ordinateur", "plage"
        ];

        // Table de remplacements des caractères alphabétiques
        $replacements = [
            'a' => ['4', '@'],
            'l' => ['1', '!'],
            'o' => ['0'],
            's' => ['&']
        ];

        // 1. Construire une base avec assez de mots pour atteindre la longueur minimale
        while (strlen(implode('', $wordsUsed)) < $minLength) {
            $word = $dictionary[array_rand($dictionary)];
            $wordsUsed[] = $word;
        }

        // 2. Insérer des séparateurs entre les mots (s'il y a plusieurs mots)
        $separators = ['-', '_', '#', '*', '$'];
        $password = implode(
            $separators[array_rand($separators)],
            $wordsUsed
        );

        $chars = str_split($password);
        $hasReplacement = false;

        // 3. Appliquer les substitutions (au moins une)
        foreach ($chars as &$char) {
            if (isset($replacements[$char])) {
                $variants = $replacements[$char];
                if (rand(0, 1) === 1 || !$hasReplacement) {
                    $char = $variants[array_rand($variants)];
                    $hasReplacement = true;
                }
            }
        }
        unset($char);

        // Si jamais aucune substitution n'a été faite → forcer une sur la première occurrence possible
        if (!$hasReplacement) {
            foreach ($chars as &$char) {
                if (isset($replacements[$char])) {
                    $variants = $replacements[$char];
                    $char = $variants[array_rand($variants)];
                    break;
                }
            }
        }

        return implode('', $chars);
    }

    #[Route('/', name: 'mac_admin_member_index', methods: ['GET'])]
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('admin/member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'mac_admin_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member, [
            'action' => $this->generateUrl('mac_admin_member_new'),
            'attr' => [
                'id' => 'formMember'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('mac_admin_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/newJson/{idAsso}', name: 'mac_admin_member_newjson', methods: ['GET', 'POST'])]
    public function newJson(Request $request, EntityManagerInterface $em, $idAsso, AssociationRepository $associationRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $association = $associationRepository->find($idAsso);

        $member = new Member();
        $form = $this->createForm(MemberType::class, $member, [
            'action' => $this->generateUrl('mac_admin_member_newjson', ['idAsso' => $idAsso]),
            'attr' => [
                'id' => 'formMember'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                $password = $this->generatePassword();
                $hashedPassword = $passwordHasher->hashPassword(
                    $member,
                    $password
                );

                $member->setPassword($hashedPassword);
                $member->setRoles(['ROLE_MEMBER']);

                $em->persist($member);
                $em->flush();

                $adherant = new Adherent();
                $adherant->setAssociation($association);
                $adherant->setMember($member);

                $em->persist($adherant);
                $em->flush();


                // return
                return $this->json([
                    "code" => 200,
                    'liste' => $this->renderView('gestion/associations/association/include/_listAdherants.html.twig', [
                        'association' => $association,
                    ]),
                    'message' => 'L\'ajout de l\'utlisateur à la plateforme et à la BDD a été réussi'
                ], 200);
            }

            //dd($form->getErrors());

            // view
            $view = $this->render('admin/member/_form.html.twig', [
                'member' => $member,
                'form' => $form
            ]);

            // return
            return $this->json([
                "code" => 422,
                'message' => 'Une erreur s\'est glissé dans le formulaire',
                'formView' => $view->getContent()
            ], 200);
        }

        // view
        $view = $this->render('admin/member/_form.html.twig', [
            'member' => $member,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_admin_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('admin/member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/{id}/edit', name: 'mac_admin_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MemberType::class, $member, [
            'action' => $this->generateUrl('mac_admin_member_edit', ['id'=>$member->getId()]),
            'attr' => [
                'id' => 'formMember'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()){
                $entityManager->flush();
                // return
                return $this->json([
                    "code" => 200,
                    'message' => "Les modifications du compte ont été mises à jour."
                ], 200);
            }else{
                // view
                $view = $this->render('admin/member/_formedit.html.twig', [
                    'member' => $member,
                    'form' => $form
                ]);

                // return
                return $this->json([
                    'code' => 422,
                    'message' => 'Une erreur s\'est glissé dans le formulaire.',
                    'formView' => $view->getContent()
                ], 422);
            }



        }

        return $this->render('admin/member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editJson', name: 'mac_admin_member_editjson', methods: ['GET', 'POST'])]
    public function editJson(Request $request,Member $member, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MemberType::class, $member, [
            'action' => $this->generateUrl('mac_admin_member_editjson', ['id'=>$member->getId()]),
            'attr' => [
                'id' => 'formMember'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            if($form->isValid()){
                $entityManager->flush();

                // return
                return $this->json([
                    "code" => 200,
                    'message' => "Les modifications du compte ont été mises à jour."
                ], 200);
            }
            // view
            $view = $this->render('admin/member/_form.html.twig', [
                'member' => $member,
                'form' => $form
            ]);

            // return
            return $this->json([
                "code" => 422,
                'formView' => $view->getContent()
            ], 200);
        }

        // view
        $view = $this->render('admin/member/_formDialog.html.twig', [
            'member' => $member,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_admin_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mac_admin_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
