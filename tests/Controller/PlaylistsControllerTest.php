<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author Lucille
 * Attendu 18 tests, 47 assertions
 */
class PlaylistsControllerTest extends WebTestCase
{
    //1 assertion
    public function testAccessPage()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    //5 assertions
    public function testContenuPageTriPlaylistsNomPlein()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        //test avec un nom
        $crawler = $client->submitForm('filtrer-playlist', [
            'recherche' => 'Cours Curseurs'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains(('h5'), 'Cours Curseurs');
        //test avec champ vide
        $crawler = $client->submitForm('filtrer-playlist', [
            'recherche' => ''
        ]);
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation (C#)');
    }

    //2 assertions
    public function testContenuPageTriPlaylistsASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->click($crawler->filter('#playlists-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation (C#)');
    }

    //2 assertions 
    public function testContenuPageTriPlaylistsDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->click($crawler->filter('#playlists-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Visual Studio 2019 et C#');
    }
    
    //2 assertions
    public function testContenuPageTriNbformationsASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->click($crawler->filter('#nbFormations-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Cours Informatique embarquée');
    }

    //2 assertions 
    public function testContenuPageTriNbformationsDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->click($crawler->filter('#nbFormations-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation (C#)');
    }
    
    //2 assertions
    public function testContenuPageFiltreCategorie()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => '2'
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    //2 assertions 
    public function testContenuPageFiltreCategorieVide()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => ''
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    
    //4 assertions (9 tests, 22 assertions)
    public function testContenuPageUnePlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $client->click($crawler->filter('#playlist-detail')->link());
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/13', $uri);
        $this->assertSelectorTextContains(('h4'), 'Bases de la programmation (C#)');
    }
}
