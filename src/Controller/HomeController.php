<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
   
    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $repo){
        $lastProducts = $repo->findLastProductAdded();

        return $this->render('home.html.twig', [
            'lastProducts' => $lastProducts
        ]);
    }
}

?>