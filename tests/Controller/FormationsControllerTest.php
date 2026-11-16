<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest
 *
 * @author Lucille
 * attendu 13 tests et 35 assertions 
 * Affiche 25 tests, 69 assertions car prend aussi en compte AdminFormationsControllerTest qui fait lui 12 tests et 34 assertions
 */
class FormationsControllerTest extends WebTestCase
{

    //1 assertion
    public function testAccessPage()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    //4 assertion
    public function testAccessPageAcceuil()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->clickLink('Accueil');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/', $uri);
        $this->assertSelectorTextContains(('h3'), 'Bienvenue sur le site de MediaTek86 consacré aux formations en ligne');
    }

    //5 assertions
    public function testContenuPageTriFormationsNomPlein()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
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
    public function testContenuPageTriFormationsASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#formations-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    //2 assertions
    public function testContenuPageTriFormationsDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#formations-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'UML : Diagramme de paquetages');
    }

    //5 assertions
    public function testContenuPageTriPlaylistNomPlein()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
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
    public function testContenuPageTriPlaylistASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#playlist-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation n°74 - POO : collections');
    }

    //2 assertions
    public function testContenuPageTriPlaylistDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#playlist-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'C# : ListBox en couleur');
    }

    //2 assertions
    public function testContenuPageFiltreCategorie()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => '2'
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Eclipse n°2 : rétroconception avec ObjectAid');
    }

    //2 assertions 
    public function testContenuPageFiltreCategorieVide()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => ''
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }

    //2 assertions
    public function testContenuPageTriDateASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#date-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
    }

    //2 assertions 
    public function testContenuPageTriDateDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->click($crawler->filter('#date-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Eclipse n°8 : Déploiement');
    }

    //4 assertions 
    public function testContenuPageUneFormation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $client->clickLink('Miniature de la formation');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
        $this->assertSelectorTextContains(('h4'), 'Eclipse n°8 : Déploiement');
    }
}
