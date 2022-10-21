<?php

namespace App\Test\Controller;

use App\Entity\DocumentProprietaire;
use App\Repository\DocumentProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentProprietaireControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private DocumentProprietaireRepository $repository;
    private string $path = '/document/proprietaire/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(DocumentProprietaire::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('DocumentProprietaire index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'document_proprietaire[lien]' => 'Testing',
            'document_proprietaire[status]' => 'Testing',
            'document_proprietaire[idUser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/document/proprietaire/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new DocumentProprietaire();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('DocumentProprietaire');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new DocumentProprietaire();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'document_proprietaire[lien]' => 'Something New',
            'document_proprietaire[status]' => 'Something New',
            'document_proprietaire[idUser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/document/proprietaire/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLien());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getIdUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new DocumentProprietaire();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/document/proprietaire/');
    }
}
