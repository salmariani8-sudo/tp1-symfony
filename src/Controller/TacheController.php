<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\TacheRepository;
use App\Entity\Tache;
use Doctrine\ORM\EntityManagerInterface;
final class TacheController extends AbstractController
{
    #[Route('/taches/{id}', name: 'app_tache_detail', requirements: ['id' => '\d+'])]
    public function detail(Tache $tache): Response
    {
        return $this->render('tache/detail.html.twig', ['tache' => $tache]);
    }
    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Ma première tâche');
        $tache->setDescription('Description de la tâche.');
        $tache->setTerminee(false);
        $tache->setDateCreation(new \DateTime());

        $em->persist($tache);
        $em->flush();

        return new Response("Tâche créée avec id " . $tache->getId());
    }
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $repo): Response
    {
        $taches = $repo->findBy([], ['terminee' => 'ASC']); // non terminées en premier
        return $this->render('tache/index.html.twig', ['taches' => $taches]);
    }
    #[Route('/taches/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\d+'])]
    public function terminer(Tache $tache, EntityManagerInterface $em): Response
    {
        $tache->setTerminee(true);
        $em->flush();

        return $this->redirectToRoute('app_taches');
    }

}
