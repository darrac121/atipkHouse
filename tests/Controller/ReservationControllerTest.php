<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

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
            'reservation[DateDebut]' => 'Testing',
            'reservation[DateFin]' => 'Testing',
            'reservation[NbNuit]' => 'Testing',
            'reservation[Total]' => 'Testing',
            'reservation[Statue]' => 'Testing',
            'reservation[StatuePayment]' => 'Testing',
            'reservation[idAnnonce]' => 'Testing',
            'reservation[idUser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setNbNuit('My Title');
        $fixture->setTotal('My Title');
        $fixture->setStatue('My Title');
        $fixture->setStatuePayment('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setNbNuit('My Title');
        $fixture->setTotal('My Title');
        $fixture->setStatue('My Title');
        $fixture->setStatuePayment('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[DateDebut]' => 'Something New',
            'reservation[DateFin]' => 'Something New',
            'reservation[NbNuit]' => 'Something New',
            'reservation[Total]' => 'Something New',
            'reservation[Statue]' => 'Something New',
            'reservation[StatuePayment]' => 'Something New',
            'reservation[idAnnonce]' => 'Something New',
            'reservation[idUser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateDebut());
        self::assertSame('Something New', $fixture[0]->getDateFin());
        self::assertSame('Something New', $fixture[0]->getNbNuit());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getStatue());
        self::assertSame('Something New', $fixture[0]->getStatuePayment());
        self::assertSame('Something New', $fixture[0]->getIdAnnonce());
        self::assertSame('Something New', $fixture[0]->getIdUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservation();
        $fixture->setDateDebut('My Title');
        $fixture->setDateFin('My Title');
        $fixture->setNbNuit('My Title');
        $fixture->setTotal('My Title');
        $fixture->setStatue('My Title');
        $fixture->setStatuePayment('My Title');
        $fixture->setIdAnnonce('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservation/');
    }
}
