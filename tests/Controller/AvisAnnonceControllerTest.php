<?php

namespace App\Test\Controller;

use App\Entity\AvisAnnonce;
use App\Repository\AvisAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvisAnnonceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AvisAnnonceRepository $repository;
    private string $path = '/avis/annonce/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(AvisAnnonce::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AvisAnnonce index');

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
            'avis_annonce[message]' => 'Testing',
            'avis_annonce[rating]' => 'Testing',
            'avis_annonce[idUser]' => 'Testing',
            'avis_annonce[idAnnonce]' => 'Testing',
        ]);

        self::assertResponseRedirects('/avis/annonce/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new AvisAnnonce();
        $fixture->setMessage('My Title');
        $fixture->setRating('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('AvisAnnonce');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new AvisAnnonce();
        $fixture->setMessage('My Title');
        $fixture->setRating('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'avis_annonce[message]' => 'Something New',
            'avis_annonce[rating]' => 'Something New',
            'avis_annonce[idUser]' => 'Something New',
            'avis_annonce[idAnnonce]' => 'Something New',
        ]);

        self::assertResponseRedirects('/avis/annonce/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMessage());
        self::assertSame('Something New', $fixture[0]->getRating());
        self::assertSame('Something New', $fixture[0]->getIdUser());
        self::assertSame('Something New', $fixture[0]->getIdAnnonce());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new AvisAnnonce();
        $fixture->setMessage('My Title');
        $fixture->setRating('My Title');
        $fixture->setIdUser('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/avis/annonce/');
    }
}
