<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Chien;
use App\Entity\Utilisateur;
use App\Form\ChienType;
use App\Repository\AdminRepository;
use App\Repository\ChienRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chien')]
class ChienController extends AbstractController
{
    #[Route('/{id}', name: 'app_chien_index', methods: ['GET'])]
    public function index(ChienRepository $chienRepository, UtilisateurRepository $utilisateurRepository, $id = null): Response
    {
        if ($id !== null) {
            $idUtilisateur = $utilisateurRepository->find($id);
            if ($idUtilisateur instanceof Utilisateur) {
                return $this->render('utilisateur/listeChienUtilisateur.html.twig', [
                    'chiens' => $chienRepository->findAll(),
                ]);
            }
        }
        //Si public alors
        return $this->render('chien/index.html.twig', [
            'chiens' => $chienRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_chien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AdminRepository $adminRepository, $id = null): Response
    {
        $chien = new Chien();
        $idAmdin = $adminRepository->find($id);
        $chien->setFkIdAdmin($idAmdin);
        $form = $this->createForm(ChienType::class, $chien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chien);
            $entityManager->flush();

            return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chien/new.html.twig', [
            'chien' => $chien,
            'form' => $form,
        ]);
    }

    #[Route('/profil/{id}', name: 'app_chien_show', methods: ['GET'])]
    public function show(Chien $chien, CommentaireRepository $commentaireRepository, $id = null): Response
    {
        $commentaire = $commentaireRepository->findBy(['fk_id_chien' => $id]);
        //TODO faire un redirect sur la page adminShowChien en checkant si c'est bien un admin.
        //user->getuserid
        $admin = $this->getUser();
        if ($admin instanceof Admin) {
            return $this->render('admin/adminProfilChien.html.twig', [
                'chien' => $chien,
                'commentaires' => $commentaire
            ]);
        } else {
            return $this->render('chien/show.html.twig', [
                'chien' => $chien,
                'commentaires' => $commentaire
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'app_chien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chien $chien, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChienType::class, $chien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chien/edit.html.twig', [
            'chien' => $chien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chien_delete', methods: ['POST'])]
    public function delete(Request $request, Chien $chien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
    }
}
