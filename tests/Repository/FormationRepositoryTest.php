<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author Lucille
 */
class FormationRepositoryTest extends KernelTestCase
{

    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    public function newFormation()
    {
        $formation = (new Formation())
                ->setTitle("FormationTest");
        return $formation;
    }

    public function testAddFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemoveFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }

    public function testFindAllOrderByASC()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderBy('title', "ASC", "");
        $this->assertEquals(89, $resultat[0]->getId(), "erreur à findAllOrderByNameASC");
    }

    public function testFindAllOrderByDESC()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findAllOrderBy('title', 'DESC', "");
        $this->assertEquals(9, $resultat[0]->getId(), "erreur à findAllOrderByNameDESC");
    }

    public function testFindByContainValueNom()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findByContainValue('title', "TP Android n°9", "");
        $this->assertEquals(107, $resultat[0]->getId(), "erreur à findByContainValueNom");
    }

    public function testFindByContainValueVide()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findByContainValue('title', "", "");
        $this->assertEquals(1, $resultat[0]->getId(), "erreur à findByContainValueVide");
    }

    public function testfindAllLasted()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findAllLasted(3);
        $nbFormations = count($resultat);
        $this->assertEquals(258, $resultat[0]->getId(), "erreur à findAllLasted");
        $this->assertEquals(3, $nbFormations, "erreur à findAllLasted");
    }

    public function testfindAllForOnePlaylist()
    {
        $repository = $this->recupRepository();
        $resultat = $repository->findAllForOnePlaylist(14);
        $nbFormations = count($resultat);
        $this->assertEquals(200, $resultat[0]->getId(), "erreur à findAllForOnePlaylist");
        $this->assertEquals(4, $nbFormations, "erreur à findAllForOnePlaylist");        
    }
}
