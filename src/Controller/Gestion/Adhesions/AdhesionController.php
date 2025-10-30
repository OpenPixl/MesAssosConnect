<?php

namespace App\Controller\Gestion\Adhesions;

use App\Entity\Gestion\Adhesions\Adhesion;
use App\Form\Gestion\Adhesions\AdhesionType;
use App\Repository\Gestion\Adhesions\AdherentRepository;
use App\Repository\Gestion\Adhesions\AdhesionRepository;
use App\Repository\Gestion\Associations\AssociationRepository;
use App\Repository\Gestion\Associations\CampaignAdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/adhesions/adhesion')]
final class AdhesionController extends AbstractController
{
    #[Route(name: 'app_gestion_adhesions_adhesion_index', methods: ['GET'])]
    public function index(AdhesionRepository $adhesionRepository): Response
    {
        return $this->render('gestion/adhesions/adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    #[Route('byasso/{idAsso}', name: 'mac_gestion_adhesions_adhesion_indexbyasso', methods: ['GET'])]
    public function indexbyasso(AdhesionRepository $adhesionRepository, $idAsso, AssociationRepository $associationRepository): Response
    {
        $association = $associationRepository->find($idAsso);

        $adhesions = $adhesionRepository->listbyasso($association);
        //dd($adhesions);

        return $this->render('gestion/adhesions/adhesion/indexbyasso.html.twig', [
            'adhesions' => $adhesions,
            'association' => $association,
        ]);
    }


    #[Route('/new', name: 'app_gestion_adhesions_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adhesion = new Adhesion();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion_adhesions_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesions/adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/newinshowasso/{idCampaign}/{idAdherent}', name: 'mac_gestion_adhesions_adhesion_newinshowasso', methods: ['GET', 'POST'])]
    public function newinshowasso(
        Request $request,
        EntityManagerInterface $entityManager,
        $idCampaign,
        CampaignAdhesionRepository $campaignAdhesionRepository,
        $idAdherent,
        AdherentRepository $adherentRepository
    ): Response
    {
        $adherent = $adherentRepository->find($idAdherent);
        $campaignAdhesion = $campaignAdhesionRepository->find($idCampaign);
        $association = $campaignAdhesion->getAssociation();

        $adhesion = new Adhesion();
        $adhesion->setCampaign($campaignAdhesion);
        $adhesion->addAdherent($adherent);
        $adhesion->setStartAt($campaignAdhesion->getStartAt());
        $adhesion->setFinishAt($campaignAdhesion->getFinishAt());
        $form = $this->createForm(AdhesionType::class, $adhesion, [
            'action' => $this->generateUrl('mac_gestion_adhesions_adhesion_newinshowasso', [
                'idCampaign' => $idCampaign,
                'idAdherent' => $idAdherent,
            ]),
            'attr' => [
                'id' => 'formAdhesion'
            ],
            'association' => $association,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            if($form->isValid()) {
                $entityManager->persist($adhesion);
                $entityManager->flush();

                return $this->json([
                    'code' => 200,
                    'liste' => $this->renderView('gestion/associations/association/include/_listAdherants.html.twig', [
                        'association' => $association,
                    ]),
                    'message' => 'Adhesion ajoutée à l\'adhérent',
                ], 200);
            }

            // Bloc dans le cas d'une erreur de formulaire à la soummission'
            $view = $this->render('gestion/adhesions/adhesion/_form.html.twig', [
                'adhesion' => $adhesion,
                'form' => $form,
            ]);
            return $this->json([
                "code" => 422,
                'message' => 'Une erreur s\'est glissé dans le formulaire',
                'formView' => $view->getContent()
            ], 200);

        }

        // Bloc pour afficher le formulaire lors du premier appel
        $view = $this->render('gestion/adhesions/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'app_gestion_adhesions_adhesion_show', methods: ['GET'])]
    public function show(Adhesion $adhesion): Response
    {
        return $this->render('gestion/adhesions/adhesion/show.html.twig', [
            'adhesion' => $adhesion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_adhesions_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $association = $adhesion->getCampaign()->getAssociation();

        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion_adhesions_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesions/adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
            'association' => $association,
        ]);
    }

    #[Route('/{id}/editjson', name: 'mac_gestion_adhesions_adhesion_editjson', methods: ['GET', 'POST'])]
    public function editjson(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        $association = $adhesion->getCampaign()->getAssociation();

        $form = $this->createForm(AdhesionType::class, $adhesion,[
            'action' => $this->generateUrl('mac_gestion_adhesions_adhesion_editjson', ['id' => $adhesion->getId()]),
            'association' => $association,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                $entityManager->flush();

                return $this->redirectToRoute('app_gestion_adhesions_adhesion_index', [], Response::HTTP_SEE_OTHER);
            }

            // Bloc dans le cas d'une erreur de formulaire à la soummission'
            $view = $this->render('gestion/adhesions/adhesion/_form.html.twig', [
                'adhesion' => $adhesion,
                'form' => $form,
            ]);
            return $this->json([
                "code" => 422,
                'message' => 'Une erreur s\'est glissé dans le formulaire',
                'formView' => $view->getContent()
            ], 200);
        }

        // Bloc pour afficher le formulaire lors du premier appel
        $view = $this->render('gestion/adhesions/adhesion/_form.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'app_gestion_adhesions_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adhesion $adhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_adhesions_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }
}
