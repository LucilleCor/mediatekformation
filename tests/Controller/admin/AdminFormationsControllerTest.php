<?php

namespace App\tests\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdminFormationsControllerTest
 *
 * @author Lucille
 * Attendu 12 tests, 34 assertions
 */
class AdminFormationsControllerTest extends WebTestCase
{

    public function createAdminClient()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'admin']);
        $client->loginUser($user);
        return $client;
    }

    //5 assertions
    public function testContenuPageAdminTriFormationsNom()
    {
        $client = $this->createAdminClient();
        $client->request('GET', '/admin');
        //test avec un nom
        $crawler = $client->submitForm('filtrer-titre', [
            'recherche' => 'Eclipse n°7 : Tests unitaires'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°7 : Tests unitaires');
        //test avec champ vide
        $crawler = $client->submitForm('filtrer-titre', [
            'recherche' => ''
        ]);
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°8 : Déploiement');
    }

    //2 assertions
    public function testContenuPageAdminTriFormationsASC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#formations-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    //2 assertions
    public function testContenuPageTriAdminFormationsDESC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#formations-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'UML : Diagramme de paquetages');
    }

    //5 assertions
    public function testContenuPageTriAdminPlaylistNomPlein()
    {
        $client = $this->createAdminClient();
        $client->request('GET', '/admin');
        //test avec un nom
        $crawler = $client->submitForm('filtrer-playlist', [
            'recherche' => 'Cours UML'
        ]);
        $this->assertCount(10, $crawler->filter('h5'));
        $this->assertSelectorTextContains(('h5'), 'UML : Diagramme de paquetages');
        //test avec champ vide
        $crawler = $client->submitForm('filtrer-playlist', [
            'recherche' => ''
        ]);
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°8 : Déploiement');
    }

    //2 assertions
    public function testContenuPageAdminTriPlaylistASC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#playlist-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation n°74 - POO : collections');
    }

    //2 assertions
    public function testContenuPageAdminTriPlaylistDESC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#playlist-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'C# : ListBox en couleur');
    }

    //2 assertions
    public function testContenuPageAdminFiltreCategorie()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => '2'
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°2 : rétroconception avec ObjectAid');
    }

    //2 assertions 
    public function testContenuPageAdminFiltreCategorieVide()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => ''
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°8 : Déploiement');
    }

    //2 assertions
    public function testContenuPageAdminTriDateASC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#date-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
    }

    //2 assertions
    public function testContenuPageAdminTriDateDESC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#date-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°8 : Déploiement');
    }
    
    //4 assertions 
    public function testContenuPageModifFormation()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#editer-formation')->link());
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/edit/1', $uri);
        $this->assertInputValueSame('formation[title]', 'Eclipse n°8 : Déploiement');
    }
    
    //4 assertions (12 tests, 34 assertions) 
    public function testContenuPageAjoutFormation()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin');
        $client->click($crawler->filter('#ajouter-formation')->link());
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/ajout', $uri);
        $this->assertInputValueSame('formation[title]', '');
    }
}
