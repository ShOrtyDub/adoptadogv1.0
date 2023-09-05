<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\CommentaireRepository;
use App\Repository\CorrespondanceRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request                     $request, EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $passwordHasher, FileUploaderService $file_uploader,
                                                    $publicUploadDir): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $utilisateur->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $utilisateur,
                $plaintextPaswword
            );
            $file = $form['photo']->getData();
            if ($file) {
                $this->doUpload($file, $utilisateur, $file_uploader, $publicUploadDir);
            }

            $utilisateur->setRoles(["ROLE_VISITEUR"]);
            $utilisateur->setPassword($hashedPassword);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur, $id = null): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
            'id' => $id,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request                     $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager,
                         UserPasswordHasherInterface $passwordHasher, UtilisateurRepository $utilisateurRepository,
                         FileUploaderService $file_uploader, $publicUploadDir, $deleteFolder): Response
    {
        $ancienUtilisateur = $utilisateurRepository->find($utilisateur->getId());
        $photo = $ancienUtilisateur->getPhoto();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $utilisateur->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $utilisateur,
                $plaintextPaswword
            );

            $file = $form['photo']->getData();
            if ($file !== null) {
                @unlink($deleteFolder . $photo);
                $this->doUpload($file, $utilisateur, $file_uploader, $publicUploadDir);
            }

            $utilisateur->setRoles(["ROLE_VISITEUR"]);
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            if ($utilisateur instanceof Admin) {
                return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_utilisateur_show', [
                    'utilisateur' => $utilisateur,
                    'id' => $utilisateur->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request               $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager,
                           CommentaireRepository $commentaireRepository, CorrespondanceRepository $correspondanceRepository): Response
    {
        $admin = $this->getUser();
        $commentaires = $commentaireRepository->findBy(['fk_id_utilisateur' => $utilisateur->getId()]);
        $correspondances = $correspondanceRepository->findBy(['fk_id_utilisateur' => $utilisateur->getId()]);

        foreach ($commentaires as $commentaire) {
            $commentaire->setFkIdUtilisateur(null);
        }

        foreach ($correspondances as $correspondance) {
            $correspondance->setFkIdUtilisateur(null);
        }


        if ($admin instanceof Admin) {
            if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
                $entityManager->remove($utilisateur);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);

        } elseif ($admin instanceof Utilisateur) {
            if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
                $this->container->get('security.token_storage')->setToken(null);
                $entityManager->remove($utilisateur);
                $entityManager->flush();
            }
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    private function doUpload($file, $chien, $file_uploader, $publicUploadDir): void
    {
        $file_name = $file_uploader->upload($file);
        if ($file_name !== null) {
            $file_path = $publicUploadDir . '/' . $file_name;
            $chien->setPhoto($file_path);
        }
    }
}


