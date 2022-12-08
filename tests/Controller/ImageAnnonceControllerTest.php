<?php

namespace App\Test\Controller;

use App\Entity\ImageAnnonce;
use App\Repository\ImageAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageAnnonceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ImageAnnonceRepository $repository;
    private string $path = '/image/annonce/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(ImageAnnonce::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ImageAnnonce index');

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
            'image_annonce[lien]' => 'Testing',
            'image_annonce[status]' => 'Testing',
            'image_annonce[idAnnonce]' => 'Testing',
        ]);

        self::assertResponseRedirects('/image/annonce/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ImageAnnonce();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ImageAnnonce');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ImageAnnonce();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'image_annonce[lien]' => 'Something New',
            'image_annonce[status]' => 'Something New',
            'image_annonce[idAnnonce]' => 'Something New',
        ]);

        self::assertResponseRedirects('/image/annonce/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLien());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getIdAnnonce());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new ImageAnnonce();
        $fixture->setLien('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdAnnonce('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/image/annonce/');
    }
}
