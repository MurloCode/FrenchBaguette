<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index()
    {

        $repo = $this->GetDoctrine()->getRepository(User::class);

        $users = $repo->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition de profil
     * 
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(AccountType::class, $user);
        // handlerequest gère la requète
        $form->handleRequest($request);
        // Si le formulaire est soumis et que le formulaire est valide alors on persist l'entité puis on flush 
        // ouvre une transaction et enregistre toutes les entités qui t'ont été données depuis le dernier flush
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            // ajoute un message Flash de confirmation sur la page
            $this->addFlash(
                'success',
                "L'utilisateur <trong>{$user->getFirstName()}</strong> a bien été enregistrée"
            );
        }

        return $this->render('admin/user/edituser.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager) {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur <strong>{$user->getFirstName()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("ads_index");
    }
}
