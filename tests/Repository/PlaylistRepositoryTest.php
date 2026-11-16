<?php


namespace App\tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 *
 * @author Lucille
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    
    public function newPlaylist(){
        $playlist = (new Playlist())
                ->setName("FormationTest");
        return $playlist;
    }
    
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylist = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylist + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemovePlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    public function testFindAllOrderByNameASC(){
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderByName('ASC');        
        $this->assertEquals(13, $resultat[0]->getId(), "erreur à findAllOrderByNameASC");
    }
    
    public function testFindAllOrderByNameDESC(){
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderByName('DESC');        
        $this->assertEquals(2, $resultat[0]->getId(), "erreur à findAllOrderByNameDESC");
    }
    
    public function testFindByContainValueNom(){
        $repository = $this->recupRepository();
        $resultat = $repository->findByContainValue('name', "Eclipse", "");        
        $this->assertEquals(1, $resultat[0]->getId(), "erreur à findByContainValueNom");
        
    }
    
    public function testFindByContainValueVide(){
        $repository = $this->recupRepository();
        $resultat = $repository->findByContainValue('name', "", "");        
        $this->assertEquals(13, $resultat[0]->getId(), "erreur à findByContainValueVide");
    }
    
    public function testFindAllOrderByNbFormationsASC(){
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderByNbFormations('ASC');        
        $this->assertEquals(36, $resultat[0]->getId(), "erreur à findAllOrderByNbFormations");
    }
    
    public function testFindAllOrderByNbFormationsDESC(){
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderByNbFormations('DESC');        
        $this->assertEquals(13, $resultat[0]->getId(), "erreur à findAllOrderByNbFormations");
    }
    
}
