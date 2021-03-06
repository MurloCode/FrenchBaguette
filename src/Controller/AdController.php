<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {

        $ads = $repo->findAll();
        
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
    
    /**
     * Permet de créer une annonce
     * 
     * @Route("/ads/new" , name="ads_create")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */

    //ManagerRegistry $managerRegistry en remplacement de Objectmanager $manager -> https://stackoverflow.com/questions/59240233/symfony-4-cannot-autowire-argument-manager-of-it-references-interface-do
    public function create(Request $request, ManagerRegistry $managerRegistry){
        $ad = new Ad();

        $form = $this->createForm(AnnonceType::class, $ad);

        //handleRequest parcour la requête et extrait les informations du form
        $form->handleRequest($request);

        // vérifie le submit et permet de savoir si le formulaire est valide par raport aux rêgles en place
        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image) {
                $em = $managerRegistry->getManager();
                $image->setAd($ad);
                $em->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $em = $managerRegistry->getManager();
            $em->persist($ad);
            $em->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }


        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="La page article ne peut étre modifier que par l'utilisateur l'ayant créer ")
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ManagerRegistry $managerRegistry){

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image) {
                $em = $managerRegistry->getManager();
                $image->setAd($ad);
                $em->persist($image);
            }

            $em = $managerRegistry->getManager();
            $em->persist($ad);
            $em->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'article <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );

            
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad){
        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer un produit
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user ==ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("ads_index");
    }
}
