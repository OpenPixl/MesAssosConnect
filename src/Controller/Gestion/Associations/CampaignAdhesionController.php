<?php

namespace App\Controller\Gestion\Associations;

use App\Entity\Gestion\Associations\CampaignAdhesion;
use App\Form\Gestion\Adhesions\CampaignAdhesionType;
use App\Repository\Gestion\Associations\AssociationRepository;
use App\Repository\Gestion\Associations\CampaignAdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/associations/campaignAdhesion')]
final class CampaignAdhesionController extends AbstractController
{
    #[Route(name: 'app_gestion_adhesions_campaign_adhesion_index', methods: ['GET'])]
    public function index(CampaignAdhesionRepository $campaignAdhesionRepository): Response
    {
        return $this->render('gestion/adhesions/campaign_adhesion/index.html.twig', [
            'campaign_adhesions' => $campaignAdhesionRepository->findAll(),
        ]);
    }

    #[Route('/new/{idAsso}', name: 'mac_gestion_adhesions_campaign_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idAsso, AssociationRepository $associationRepository): Response
    {
        $association = $associationRepository->find($idAsso);
        $listCampaign = $association->getCampaignAdhesions();

        $seasonStartAt = $association->getSeasonStart();
        $seasonFinishAt = $association->getSeasonEnd();
        $currentYear = (new \DateTime())->format('Y');

        // Création de la nouvelle campagne en lien avec les périodes d'activité de l'association'
        $campaignAdhesion = new CampaignAdhesion();
        // 1- On convertit les dates en objet DateTime
        $startAt = \DateTime::createFromFormat('d/m/Y', $seasonStartAt . '/' . $currentYear);
        $finishAt = \DateTime::createFromFormat('d/m/Y', $seasonFinishAt . '/' . $currentYear);
        // 2- on controle les chevauchements d'année
        if ($finishAt < $startAt) {
            $finishAt =\DateTime::createFromFormat('d/m/Y', $seasonFinishAt . '/' . ($currentYear + 1));
            $campaignAdhesion->setName('Campagne '.$startAt->format('Y').'/'.$finishAt->format('Y'));
        }
        $campaignAdhesion->setAssociation($association);
        $campaignAdhesion->setStartAt($startAt);
        $campaignAdhesion->setFinishAt($finishAt);
        $campaignAdhesion->setName('Campagne '.$startAt->format('Y'));

        // 3- On teste si l'association n'abrites pas un doublon avec la future création
        if ($listCampaign) {
            foreach ($listCampaign as $campaign) {
                if (
                    $campaign->getStartAt()->format('d/m/Y') === $startAt->format('d/m/Y')
                    && $campaign->getFinishAt()->format('d/m/Y') === $finishAt->format('d/m/Y')
                ) {
                    return $this->json([
                        'success' => false,
                        'message' => sprintf(
                            "Une campagne existe déjà pour la période %s - %s !",
                            $startAt->format('d/m/Y'),
                            $finishAt->format('d/m/Y')
                        )
                    ], 409);
                }
            }
        }

        $form = $this->createForm(CampaignAdhesionType::class, $campaignAdhesion, [
            'action' => $this->generateUrl('mac_gestion_adhesions_campaign_adhesion_new', ['idAsso' => $idAsso]),
            'attr' => [
                'id' => 'formCampaignAdhesion'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            if($form->isValid()){
                $entityManager->persist($campaignAdhesion);
                $entityManager->flush();

                // return
                return $this->json([
                    "code" => 200,
                    'message' => 'une nouvelle campagne a été ajoutée à votre association.',
                ], 200);
            }

            // view
            $view = $this->render('gestion/associations/campaign_adhesion/_form.html.twig', [
                'campaign_adhesion' => $campaignAdhesion,
                'form' => $form
            ]);

            // return
            return $this->json([
                "code" => 422,
                'message' => 'Une erreur s\'est glissé dans le formulaire',
                'formView' => $view->getContent()
            ], 200);
        }

        // faire le retour JSON
        // Vue du formulaire
        $view = $this->render('gestion/associations/campaign_adhesion/_form.html.twig', [
            'campaign_adhesion' => $campaignAdhesion,
            'form' => $form
        ]);

        // retour json
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);

    }

    #[Route('/{id}', name: 'app_gestion_adhesions_campaign_adhesion_show', methods: ['GET'])]
    public function show(CampaignAdhesion $campaignAdhesion): Response
    {
        return $this->render('gestion/adhesions/campaign_adhesion/show.html.twig', [
            'campaign_adhesion' => $campaignAdhesion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_adhesions_campaign_adhesion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CampaignAdhesion $campaignAdhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampaignAdhesionType::class, $campaignAdhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion_adhesions_campaign_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/adhesions/campaign_adhesion/edit.html.twig', [
            'campaign_adhesion' => $campaignAdhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestion_adhesions_campaign_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, CampaignAdhesion $campaignAdhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaignAdhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($campaignAdhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gestion_adhesions_campaign_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }
}
