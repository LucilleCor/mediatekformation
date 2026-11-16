<?php


namespace App\tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRespositoryTest
 *
 * @author Lucille
 */
class CategorieRespositoryTest extends KernelTestCase
{
    public function recupRepository(): CategorieRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }
    
    public function newCategorie(){
        $categorie = (new Categorie())
                ->setName("CategorieTest");
        return $categorie;
    }
    
    public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemoveCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression");

    }
    
    public function testfindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $categorieTrouvé = $repository->findAllForOnePlaylist(17);
        $this->assertCount(1 , $categorieTrouvé, "erreur à findAllForOnePlaylist");
    }
}
