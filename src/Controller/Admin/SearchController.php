<?php

namespace App\Controller\Admin;

use App\Form\Admin\SearchMemberType;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchPhrasePrefix;
use FOS\ElasticaBundle\Finder\FinderInterface;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{


    #[Route('/admin/searchcontroller', name: 'mac_admin_searchcontroller')]
    public function index(): Response
    {
        return $this->render('admin/search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    #[Route('/admin/search/memberonassociationshow/{idAsso}', name: 'mac_admin_search_memberonassociationshow', methods: ['POST', 'GET'])]
    public function memberOnAssociationShow(Request $request, TransformedFinder $memberFinder, int $idAsso): Response
    {
        $form = $this->createForm(SearchMemberType::class, null, [
            'action' => $this->generateUrl('mac_admin_search_memberonassociationshow', [
                'idAsso' => $idAsso,
            ]),
            'method' => 'POST',
            'attr' => [
                'id' => 'SearchFormCustomer'
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData()->slug;
            $page = $request->query->getInt('page', 1);

            //dd($data);

            if (empty($data)) {
                return $this->json([]);
            }

            // Découper la recherche sur les espaces
            $terms = preg_split('/\s+/', $data);

            // Le code ci dessous :
            // - Découpe la chaîne de recherche sur les espaces
            // - Pour chaque mot, construit un sous-bool qui cherche dans firstName ou lastName (should)
            // - Combine tous ces sous-bool avec un must (= tous les mots doivent apparaître au moins une fois dans les deux champs)
            // - Utilise MatchPhrasePrefix pour que ce soit tolérant aux débuts de mots

            $boolQuery = new BoolQuery();

            foreach ($terms as $term) {
                $should = new BoolQuery();
                $should->addShould(new MatchPhrasePrefix('firstName', $term));
                $should->addShould(new MatchPhrasePrefix('lastName', $term));

                $boolQuery->addMust($should);
            }

            $query = new Query($boolQuery);

            // Récupérer les résultats (objets Customer)
            $members = $memberFinder->find($query);

            //dd($members);

            return $this->json([
                'liste' => $this->renderView('admin/search/include/_searchResultAssociation.html.twig', [
                    'members' => $members,
                    'idAsso' => $idAsso,
                ]),
            ], 200);
        }

        return $this->render('admin/search/searchmemberonassociationshow.html.twig', [
            'form' => $form,
        ]);
    }
}
