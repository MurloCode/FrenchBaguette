<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager)
    {
        //Requêtes en DQL (Doctrine Query Language) permet de récupérer des entités sous forme d'objets le nombre d'utilisateurs
        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        //permet de récupérer le nombre d'articles créer
        $articles = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();

        return $this->render('admin/dashboard/index.html.twig', [
            'seeUsers' => $users,
            'articles' => $articles
        ]);
    }
}
