<?php

namespace App\Controller\Gestion;

use App\Entity\Gestion\Adhesion;
use App\Form\Gestion\AdhesionOnMemberType;
use App\Form\Gestion\AdhesionType;
use App\Form\Gestion\AdhesionOnAssoType;
use App\Repository\Admin\AssociationRepository;
use App\Repository\Gestion\AdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/adhesion')]
class AdhesionController extends AbstractController
{
    #[Route('/', name: 'mac_gestion_adhesion_index', methods: ['GET'])]
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('gestion/adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'mac_gestion_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/newOnAssociation/{idAsso}', name: 'mac_gestion_adhesion_newonassociation', methods: ['GET', 'POST'])]
    public function newOnAssociation(Request $request, EntityManagerInterface $entityManager, $idAsso, AssociationRepository $associationRepository): Response
    {
        $association = $associationRepository->find($idAsso);
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionOnAssoType::class, $adhesion, [
            "action" => $this->generateUrl('mac_gestion_adhesion_newonassociation', ['idAsso'=>$idAsso]),
            "attr" => [
                "id" => 'formAdhesion',
            ],
            "association" => $association
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adhesion->setAsso($association);
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listMembers.html.twig',[
                    'association' => $association
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/newOnMember/{idMember}', name: 'mac_gestion_adhesion_newonmember', methods: ['GET', 'POST'])]
    public function newOnMember(Request $request, EntityManagerInterface $entityManager, $idMember, AssociationRepository $associationRepository): Response
    {
        $member = $associationRepository->find($idMember);
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionOnMemberType::class, $adhesion, [
            "action" => $this->generateUrl('mac_gestion_adhesion_newonmember', ['idMember'=>$idMember]),
            "attr" => [
                "id" => 'formAdhesion',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adhesion->addMember($member);
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listMembers.html.twig',[
                    'member' => $member
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_gestion_adhesion_show', methods: ['GET'])]
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('gestion/adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    #[Route('/{id}/edit', name: 'mac_gestion_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editOnAsso', name: 'mac_gestion_adhesion_editonasso', methods: ['GET', 'POST'])]
    public function editOnAsso(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $association = $adhesion->getAsso();

        $form = $this->createForm(AdhesionOnAssoType::class, $adhesion, [
            "action" => $this->generateUrl('mac_gestion_adhesion_editonasso', ['id' => $adhesion->getId()]),
            "attr" => [
                'id' => 'formAdhesion'
            ],
            "association" => $association
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $association = $adhesion->getAsso();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listMembers.html.twig',[
                    'association' => $association
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}/editOnMember', name: 'mac_gestion_adhesion_editonmember', methods: ['GET', 'POST'])]
    public function editOnMember(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $association = $adhesion->getAsso();

        $form = $this->createForm(AdhesionOnAssoType::class, $adhesion, [
            "action" => $this->generateUrl('mac_gestion_adhesion_editonasso', ['id' => $adhesion->getId()]),
            "attr" => [
                'id' => 'formAdhesion'
            ],
            "association" => $association
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $association = $adhesion->getAsso();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listMembers.html.twig',[
                    'association' => $association
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}/duplicateFromMember', name: 'mac_gestion_adhesion_duplicateFromMember', methods: ['GET', 'POST'])]
    public function duplicateFromMember(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mac_gestion_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mac_gestion_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/del/{id}', name: 'mac_gestion_adhesion_del', methods: ['POST'])]
    public function del(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $association = $adhesion->getAsso();

        $entityManager->remove($adhesion);
        $entityManager->flush();

        return $this->json([
            "code" => 200,
            'list' => $this->renderView('admin/association/include/_listMembers.html.twig',[
                'association' => $association
            ])
        ], 200);
    }
}
