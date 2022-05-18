<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {
   
    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $repo){
        //permet d'afficher tous les articles
        $ads = $repo->findAll();
        //Permet d'afficher les derniers articles ajoutés au site depuis le AdRepository
        $lastProducts = $repo->findLastProductAdded();

        return $this->render('home.html.twig', [
            'ads' => $ads,
            'lastProducts' => $lastProducts
        ]);
    }
}


?>