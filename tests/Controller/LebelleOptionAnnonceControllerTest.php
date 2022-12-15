<?php

namespace App\Test\Controller;

use App\Entity\LebelleOptionAnnonce;
use App\Repository\LebelleOptionAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LebelleOptionAnnonceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private LebelleOptionAnnonceRepository $repository;
    private string $path = '/lebelle/option/annonce/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(LebelleOptionAnnonce::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LebelleOptionAnnonce index');

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
            'lebelle_option_annonce[value]' => 'Testing',
            'lebelle_option_annonce[status]' => 'Testing',
            'lebelle_option_annonce[idCompany]' => 'Testing',
        ]);

        self::assertResponseRedirects('/lebelle/option/annonce/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new LebelleOptionAnnonce();
        $fixture->setValue('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdCompany('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LebelleOptionAnnonce');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new LebelleOptionAnnonce();
        $fixture->setValue('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdCompany('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'lebelle_option_annonce[value]' => 'Something New',
            'lebelle_option_annonce[status]' => 'Something New',
            'lebelle_option_annonce[idCompany]' => 'Something New',
        ]);

        self::assertResponseRedirects('/lebelle/option/annonce/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getValue());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getIdCompany());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new LebelleOptionAnnonce();
        $fixture->setValue('My Title');
        $fixture->setStatus('My Title');
        $fixture->setIdCompany('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/lebelle/option/annonce/');
    }
}
