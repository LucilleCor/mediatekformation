<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\FormationType;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author Lucille
 */
class AdminPlaylistsController extends AbstractController
{
    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    
    const PLAYLISTS = "admin/admin.playlists.html.twig";
    
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository            
    )
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    #[Route('/admin/playlists', name:'admin.playlists')]
    public function index() : Response 
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }
    
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        if ($champ == "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        if ($champ == "nbFormations") {
            $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories
        ]);
    }
    
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PLAYLISTS, [
                    'playlists' => $playlists,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }
    
    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response{
        $playlist = $this->playlistRepository->find($id);
        $formations = $this->formationRepository->findBy(['playlist' => $id]);
        if(empty($formations) ){
            $this->playlistRepository->remove($playlist);
            return $this->redirectToRoute('admin.playlists');
        }else{
            $this->addFlash('danger', 'Impossible de supprimer une playlist qui contient des formations.');
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        } 
    }
    
    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        $formations = $this->formationRepository->findBy(['playlist' => $id], ['publishedAt' => 'DESC']);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView(),
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    #[Route('/admin/playlists/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
}
