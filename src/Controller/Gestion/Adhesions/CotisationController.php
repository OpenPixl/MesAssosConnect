<?php

namespace App\Controller\Gestion\Adhesions;

use App\Entity\Gestion\Adhesions\Cotisation;
use App\Form\Gestion\Adhesions\CotisationType;
use App\Repository\Gestion\Adhesions\CotisationRepository;
use App\Repository\Gestion\Associations\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/adhesions/cotisation')]
final class CotisationController extends AbstractController
{
    #[Route(name: 'mac_gestion_cotisation_index', methods: ['GET'])]
    public function index(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('gestion/adhesions/cotisation/index.html.twig', [
            'cotisations' => $cotisationRepository->findAll(),
        ]);
    }

    #[Route('/byAsso/{idAsso}', name: 'mac_gestion_cotisation_indexbyAsso', methods: ['GET'])]
    public function indexbyasso(CotisationRepository $cotisationRepository, $idAsso, AssociationRepository $associationRepository): Response
    {

        $cotisations = $cotisationRepository->findBy(['association' => $idAsso]);
        $association = $associationRepository->find($idAsso);

        return $this->render('gestion/adhesions/cotisation/indexbyasso.html.twig', [
            'cotisations' => $cotisations,
            'association' => $association,
        ]);
    }

    // Ajoute une cotisation pour une association indentifiée.
    #[Route('/new/{idAsso}', name: 'mac_gestion_cotisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CotisationRepository $cotisationRepository, $idAsso, AssociationRepository $associationRepository): Response
    {
        $cotisation = new Cotisation();
        $form = $this->createForm(CotisationType::class, $cotisation, [
            'action' => $this->generateUrl('mac_gestion_cotisation_new', ['idAsso' => $idAsso]),
            'attr' => [
                'id' => 'formCotisation'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->isValid()){
                $association = $associationRepository->find($idAsso);
                $cotisation->setAssociation($association);

                $entityManager->persist($cotisation);
                $entityManager->flush();

                $cotisations = $cotisationRepository->findBy(['association' => $association]);

                // return
                return $this->json([
                    "code" => 200,
                    'liste' => $this->renderView('gestion/adhesions/cotisation/include/_listeCotisations.html.twig', [
                        'cotisations' => $cotisations,
                        'association' => $association,
                    ]),
                    'message' => 'L\'ajout de l\'utlisateur à la plateforme et à la BDD a été réussi'
                ], 200);
            }

            // view
            $view = $this->render('admin/member/_form.html.twig', [
                'cotisation' => $cotisation,
                'form' => $form
            ]);

            // return
            return $this->json([
                "code" => 422,
                'message' => 'Une erreur s\'est glissé dans le formulaire',
                'formView' => $view->getContent()
            ], 200);
        }

        // Vue du formulaire
        $view = $this->render('gestion/adhesions/cotisation/_form.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form
        ]);

        // retour json
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_gestion_cotisation_show', methods: ['GET'])]
    public function show(Cotisation $cotisation): Response
    {
        return $this->render('gestion/adhesions/cotisation/show.html.twig', [
            'cotisation' => $cotisation,
        ]);
    }

    #[Route('/{id}/price', name: 'mac_gestion_cotisation_price', methods: ['GET'])]
    public function getPrice(Cotisation $cotisation): JsonResponse
    {
        return $this->json([
            'id' => $cotisation->getId(),
            'price' => $cotisation->getCotisation(),
        ]);
    }

    #[Route('/{id}/edit', name: 'mac_gestion_cotisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_cotisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesions/cotisation/edit.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mac_gestion_cotisation_delete', methods: ['POST'])]
    public function delete(Request $request, Cotisation $cotisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotisation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cotisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mac_gestion_cotisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
