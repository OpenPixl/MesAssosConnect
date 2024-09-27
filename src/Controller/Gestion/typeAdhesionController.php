<?php

namespace App\Controller\Gestion;

use App\Entity\Admin\Association;
use App\Entity\Gestion\Adhesion;
use App\Entity\Gestion\typeAdhesion;
use App\Form\Gestion\typeAdhesionType;
use App\Repository\Admin\AssociationRepository;
use App\Repository\Gestion\typeAdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/typeadhesion')]
class typeAdhesionController extends AbstractController
{
    #[Route('/', name: 'app_gestion_type_adhesion_index', methods: ['GET'])]
    public function index(typeAdhesionRepository $typeAdhesionRepository): Response
    {
        return $this->render('gestion/type_adhesion/index.html.twig', [
            'type_adhesions' => $typeAdhesionRepository->findAll(),
        ]);
    }

    #[Route('/filterByAsso/{idAsso}', name: 'app_gestion_type_adhesion_filterbyasso', methods: ['GET'])]
    public function FilterByAsso($idAsso, typeAdhesionRepository $typeAdhesionRepository): JsonResponse
    {
        // Sélection des Cotisations selon l'asso
        $typesAdhesions = $typeAdhesionRepository->findBy(['Asso' => $idAsso]);

        // Format JSON à retourner
        $typesAdhesionData = [];
        foreach ($typesAdhesions as $typesAdhesion) {
            $typesAdhesionData[] = [
                'id' => $typesAdhesion->getId(),
                'nom' => $typesAdhesion->getNom(),
            ];
        }

        return new JsonResponse($typesAdhesionData);
    }

    #[Route('/new/{idAsso}', name: 'app_gestion_type_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idAsso, AssociationRepository $associationRepository): Response
    {
        $association = $associationRepository->find($idAsso);

        $typeAdhesion = new typeAdhesion();
        $form = $this->createForm(typeAdhesionType::class, $typeAdhesion, [
            'action' => $this->generateUrl('app_gestion_type_adhesion_new', ['idAsso'=>$idAsso]),
            'attr' => [
                'id' => 'formTypeAdhesion'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeAdhesion->setAsso($association);
            $entityManager->persist($typeAdhesion);
            $entityManager->flush();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listCotisations.html.twig',[
                    'association' => $association
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/type_adhesion/_form.html.twig', [
            'type_adhesion' => $typeAdhesion,
            'form' => $form,
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'app_gestion_type_adhesion_show', methods: ['GET'])]
    public function show(typeAdhesion $typeAdhesion): Response
    {
        return $this->render('gestion/type_adhesion/show.html.twig', [
            'type_adhesion' => $typeAdhesion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_type_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, typeAdhesion $typeAdhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(typeAdhesionType::class, $typeAdhesion, [
            'action' => $this->generateUrl('app_gestion_type_adhesion_edit', ['id' => $typeAdhesion->getId()]),
            'attr' => [
                'id' => 'formTypeAdhesion'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $association = $typeAdhesion->getAsso();

            return $this->json([
                "code" => 200,
                'list' => $this->renderView('admin/association/include/_listCotisations.html.twig',[
                    'association' => $association
                ])
            ], 200);
        }

        // view
        $view = $this->render('gestion/type_adhesion/_form.html.twig', [
            'type_adhesion' => $typeAdhesion,
            'form' => $form,
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'app_gestion_type_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, typeAdhesion $typeAdhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeAdhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeAdhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_type_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/del/{id}', name: 'app_gestion_type_adhesion_del', methods: ['POST'])]
    public function del(Request $request, typeAdhesion $typeAdhesion, typeAdhesionRepository $typeAdhesionRepository, EntityManagerInterface $entityManager): Response
    {
        $association = $typeAdhesion->getAsso();
        if($association){
            $association->removeTypeAdhesion($typeAdhesion);
        }

        $adhesions = $typeAdhesion->getAdhesions();
        if($adhesions){
            foreach($adhesions as $adhesion){
                $typeAdhesion->removeAdhesion($adhesion);
            }
        }

        $entityManager->remove($typeAdhesion);
        $entityManager->flush();

        return $this->json([
            "code" => 200,
            'list' => $this->renderView('admin/association/include/_listCotisations.html.twig',[
                'association' => $association
            ])
        ], 200);
    }
}
