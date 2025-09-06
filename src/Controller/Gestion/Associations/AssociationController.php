<?php

namespace App\Controller\Gestion\associations;

use App\Entity\Gestion\Associations\Association;
use App\Form\Gestion\associations\AssociationType;
use App\Repository\Gestion\Associations\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/association')]
class AssociationController extends AbstractController
{
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }

    #[Route('/', name: 'mac_admin_association_index', methods: ['GET'])]
    public function index(AssociationRepository $associationRepository): Response
    {
        return $this->render('admin/association/index.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'mac_admin_association_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('mac_admin_association_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association/new.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/newModal/{idMember}', name: 'mac_admin_association_newModal', methods: ['GET', 'POST'])]
    public function newModal(Request $request, EntityManagerInterface $entityManager,  $idMember): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('mac_admin_association_index', [], Response::HTTP_SEE_OTHER);
        }

        // view
        $view = $this->render('admin/association/_form.html.twig', [
            'association' => $association,
            'idMember' =>$idMember,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_admin_association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('admin/association/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/{id}/edit', name: 'mac_admin_association_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Association $association,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $form = $this->createForm(AssociationType::class, $association,[
            'action' => $this->generateUrl('mac_admin_association_edit', [
                'id'=>$association->getId()
            ]),
            'method' => 'POST',
            'attr' => [
                'id'=>'formAssociation'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            //dd($form->isValid());

            if($form->isValid()){

                // Si changement de nom de l'association


                //Insertion du code d'ajout d'une image
                $logoFile = $form->get('logoFile')->getData();
                $logoName = $association->getLogoName();
                $slugAsso = $association->getSlug();

                if($logoFile){
                    $pathdir = $this->getParameter('association_directory').$slugAsso."/logo/";
                    $pathfile = $pathdir.$logoName;
                    // Suppression du document si déjà présent en BDD.
                    if($logoName){
                        // On vérifie si le pdf existe
                        if(file_exists($pathfile)){
                            unlink($pathfile);
                        }
                    }
                    // Normalisation du nom de fichier
                    $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $slugAsso.'-'.$safeFilename.'.'.$logoFile->guessExtension();
                    try {
                        if (is_dir($pathdir)){
                            $logoFile->move(
                                $this->getParameter('association_directory').$slugAsso."/logo/",
                                $newFilename
                            );
                        }else{
                            // Création du répertoire s'il n'existe pas.
                            mkdir($pathdir."/", 0775, true);
                            // Déplacement de la photo
                            $logoFile->move(
                                $this->getParameter('association_directory').$slugAsso."/logo/",
                                $newFilename
                            );
                        }
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $association->setLogoName($newFilename);
                }
                $entityManager->flush();

                $view = $this->render('admin/association/_form.html.twig', [
                    'association' => $association,
                    'form' => $form
                ]);

                return $this->json([
                    'code' => 200,
                    'message' => 'Modification apportées à la Base de données',
                    'formView' => $view->getContent(),
                ]);
            }

            $view = $this->renderView('admin/association/_form.html.twig', [
                'assocation' => $association,
                'form' => $form
            ]);

            return $this->json([
                'code' => 422,
                'message' => 'Le formulaire présente une ou des erreurs.<br><span class="mt-1 mb-1 fw-semibold text-warning">'. implode(', ', $this->getFormErrors($form)). '</span><br>A vous de corriger celles-ci',
                'formView' => $view,
            ],200);


        }

        return $this->render('admin/association/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editModal', name: 'mac_admin_association_editModal', methods: ['GET', 'POST'])]
    public function editModal(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('mac_admin_association_index', [], Response::HTTP_SEE_OTHER);
        }

        // view
        $view = $this->render('admin/association/_form.html.twig', [
            'association' => $association,
            'form' => $form
        ]);

        // return
        return $this->json([
            "code" => 200,
            'formView' => $view->getContent()
        ], 200);
    }

    #[Route('/{id}', name: 'mac_admin_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mac_admin_association_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/del/{id}', name: 'mac_admin_association_del', methods: ['POST'])]
    public function del(Request $request, Association $association, AssociationRepository $associationRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($association);
            $entityManager->flush();
        }

        $associations = $associationRepository->findAll();

        return $this->redirectToRoute('mac_admin_association_index', [], Response::HTTP_SEE_OTHER);
    }
}
