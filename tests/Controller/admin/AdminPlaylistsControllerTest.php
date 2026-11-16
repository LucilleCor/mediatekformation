<?php

namespace App\tests\Controller\admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdminPlaylistsContollerTest
 *
 * @author Lucille
 * Attendu 9 tests, 25 assertions
 */
class AdminPlaylistsControllerTest extends WebTestCase
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
    public function testContenuPageTriPlaylistsNomPlein()
    {
        $client = $this->createAdminClient();
        $client->request('GET', '/admin/playlists');
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
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#playlists-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation (C#)');
    }

    //2 assertions 
    public function testContenuPageTriPlaylistsDESC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#playlists-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Visual Studio 2019 et C#');
    }
    
    //2 assertions
    public function testContenuPageTriNbformationsASC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#nbFormations-ASC')->link());
        $this->assertSelectorTextContains(('h5'), 'Cours Informatique embarquée');
    }

    //2 assertions 
    public function testContenuPageTriNbformationsDESC()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#nbFormations-DESC')->link());
        $this->assertSelectorTextContains(('h5'), 'Bases de la programmation (C#)');
    }
    
    //2 assertions
    public function testContenuPageFiltreCategorie()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => '2'
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    //2 assertions 
    public function testContenuPageFiltreCategorieVide()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $form = $crawler->filter('#form-categorie')->form([
            'recherche' => ''
        ]);
        $crawler = $client->submit($form);
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    
    //4 assertions 
    public function testContenuPageEditPlaylist()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#playlist-edit')->link());
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/playlists/edit/13', $uri);        
        $this->assertInputValueSame('playlist[name]', 'Bases de la programmation (C#)');
    }
    
    //4 assertions 
    public function testContenuPageAjoutFormation()
    {
        $client = $this->createAdminClient();
        $crawler = $client->request('GET', '/admin/playlists');
        $client->click($crawler->filter('#playlist-ajout')->link());
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/playlists/ajout', $uri);
        $this->assertInputValueSame('playlist[name]', '');
    }
}
