<?php

namespace App\Test\Controller;

use App\Entity\OptionAnnonce;
use App\Repository\OptionAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OptionAnnonceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private OptionAnnonceRepository $repository;
    private string $path = '/option/annonce/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(OptionAnnonce::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('OptionAnnonce index');

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
            'option_annonce[valeur]' => 'Testing',
            'option_annonce[idAnnonce]' => 'Testing',
            'option_annonce[idLibelle]' => 'Testing',
        ]);

        self::assertResponseRedirects('/option/annonce/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new OptionAnnonce();
        $fixture->setValeur('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdLibelle('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('OptionAnnonce');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new OptionAnnonce();
        $fixture->setValeur('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdLibelle('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'option_annonce[valeur]' => 'Something New',
            'option_annonce[idAnnonce]' => 'Something New',
            'option_annonce[idLibelle]' => 'Something New',
        ]);

        self::assertResponseRedirects('/option/annonce/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getValeur());
        self::assertSame('Something New', $fixture[0]->getIdAnnonce());
        self::assertSame('Something New', $fixture[0]->getIdLibelle());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new OptionAnnonce();
        $fixture->setValeur('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdLibelle('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/option/annonce/');
    }
}
