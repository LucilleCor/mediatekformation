<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author Lucille
 */
class AdminCategoriesController extends AbstractController
{

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    public function __construct(CategorieRepository $categorieRepository, FormationRepository $formationRespository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
                    'categories' => $categories
        ]);
    }

    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }

    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response
    {
        $nom = trim((string) $request->request->get('name'));
        $test = $this->categorieRepository->findOneBy(['name' => $nom]);
        if ($test !== null && $nom !== '') {
            return $this->redirectToRoute('admin.categories');
        } else {
            $categorie = new Categorie();
            $categorie->setName($nom);
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }
    }
}
