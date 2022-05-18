<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /** afficher la liste des annonces
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(AnnonceType::class, $ad);
        // handlerequest gère la requète
        $form->handleRequest($request);
        // Si le formulaire est soumis et que le formulaire est valide alors on persist l'entité puis on flush 
        // ouvre une transaction et enregistre toutes les entités qui t'ont été données depuis le dernier flush
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();
            // ajoute un message Flash de confirmation sur la page
            $this->addFlash(
                'success',
                "L'annonce <trong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param EntityManager $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été éffacée !"
        );

        return $this->redirectToRoute('admin_ads_index');
    }
}
