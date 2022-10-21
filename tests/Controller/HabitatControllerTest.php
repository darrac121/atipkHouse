<?php

namespace App\Test\Controller;

use App\Entity\Habitat;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HabitatControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private HabitatRepository $repository;
    private string $path = '/habitat/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Habitat::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Habitat index');

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
            'habitat[description]' => 'Testing',
            'habitat[localisation]' => 'Testing',
            'habitat[photo]' => 'Testing',
            'habitat[prix]' => 'Testing',
            'habitat[surface]' => 'Testing',
        ]);

        self::assertResponseRedirects('/habitat/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Habitat();
        $fixture->setDescription('My Title');
        $fixture->setLocalisation('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setPrix('My Title');
        $fixture->setSurface('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Habitat');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Habitat();
        $fixture->setDescription('My Title');
        $fixture->setLocalisation('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setPrix('My Title');
        $fixture->setSurface('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'habitat[description]' => 'Something New',
            'habitat[localisation]' => 'Something New',
            'habitat[photo]' => 'Something New',
            'habitat[prix]' => 'Something New',
            'habitat[surface]' => 'Something New',
        ]);

        self::assertResponseRedirects('/habitat/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getLocalisation());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getSurface());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Habitat();
        $fixture->setDescription('My Title');
        $fixture->setLocalisation('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setPrix('My Title');
        $fixture->setSurface('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/habitat/');
    }
}
