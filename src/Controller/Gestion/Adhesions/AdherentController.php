<?php

namespace App\Controller\Gestion\Adhesions;

use App\Entity\Gestion\Adhesions\Adherent;
use App\Form\Gestion\Adhesions\AdhesionType;
use App\Repository\Admin\MemberRepository;
use App\Repository\Gestion\Adhesions\AdherentRepository;
use App\Repository\Gestion\Associations\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gestion/associations/adherent')]
final class AdherentController extends AbstractController
{
    #[Route(name: 'mac_gestion_associations_adhesion_index', methods: ['GET'])]
    public function index(AdherentRepository $adhesionRepository): Response
    {
        return $this->render('gestion/associations/adhesion/index.html.twig', [
            'adhesions' => $adhesionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'mac_gestion_associations_adhesion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adhesion = new Adherent();
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adhesion);
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_associations_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/associations/adhesion/new.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/add/{idMember}/{idAsso}', name: 'mac_admin_adherent_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em, $idMember, MemberRepository $memberRepository, $idAsso, AssociationRepository $associationRepository ): Response
    {
        $association = $associationRepository->find($idAsso);
        $adherents = $association->getAdhesions();       // corriger le getAdhesions en getAdherents
        $check = false;

        foreach($adherents as $adherent){
            if($adherent->getMember()->getId() == $idMember){
                $check = true;
            }
        }
        if($check == true){
            return $this->json([
                'liste' => $this->renderView('gestion/associations/association/include/_listAdherants.html.twig', [
                    'association' => $association,
                ]),
                'message' => 'l\'adhérent.e est déja présent.e dans cette association.'
            ], 200);
        }

        $member = $memberRepository->find($idMember);

        // on teste si l'id Adherent est déja présent dans l'association

        $adherant = new Adherent();
        $adherant->setMember($member);
        $adherant->setAssociation($association);
        $adherant->setState(0);
        $em->persist($adherant);
        $em->flush();

        return $this->json([
            'liste' => $this->renderView('gestion/associations/association/include/_listAdherants.html.twig', [
                'association' => $association,
            ]),
            'message' => 'ajout de l\'utilisateur à l\'association',
        ], 200);
    }

    #[Route('/{id}', name: 'mac_gestion_associations_adherent_show', methods: ['GET'])]
    public function show(Adherent $adherent): Response
    {

        return $this->json([
            'view' => $this->renderView('gestion/adhesions/adherent/show.html.twig', [
                'adherent' => $adherent,
            ]),
        ], 200);
    }

    #[Route('/{id}/edit', name: 'mac_gestion_associations_adherent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherent $adhesion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdhesionType::class, $adhesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mac_gestion_associations_adhesion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gestion/associations/adhesion/edit.html.twig', [
            'adhesion' => $adhesion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mac_gestion_associations_adhesion_delete', methods: ['POST'])]
    public function delete(Request $request, Adherent $adhesion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adhesion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adhesion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mac_gestion_associations_adhesion_index', [], Response::HTTP_SEE_OTHER);
    }
}
