<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Chien;
use App\Entity\Utilisateur;
use App\Form\ChienType;
use App\Repository\AdminRepository;
use App\Repository\ChienRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CorrespondanceRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chien')]
class ChienController extends AbstractController
{
    /**
     * Affiche la liste des chiens.
     * @param ChienRepository $chienRepository
     * @param UtilisateurRepository $utilisateurRepository
     * @param $id
     * @return Response
     */
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
        return $this->render('chien/index.html.twig', [
            'chiens' => $chienRepository->findAll(),
        ]);
    }

    /**
     * Crée un nouveau profil de chien.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AdminRepository $adminRepository
     * @param FileUploaderService $file_uploader
     * @param $publicUploadDir
     * @param $id
     * @return Response
     */
    #[Route('/new/{id}', name: 'app_chien_new', methods: ['GET', 'POST'])]
    public function new(Request             $request, EntityManagerInterface $entityManager, AdminRepository $adminRepository,
                        FileUploaderService $file_uploader, $publicUploadDir, $id = null): Response
    {
        $chien = new Chien();
        $idAmdin = $adminRepository->find($id);
        $chien->setFkIdAdmin($idAmdin);

        $form = $this->createForm(ChienType::class, $chien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();
            if ($file) {
                $this->doUpload($file, $chien, $file_uploader, $publicUploadDir);
            }

            $entityManager->persist($chien);
            $entityManager->flush();

            return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chien/new.html.twig', [
            'chien' => $chien,
            'form' => $form,
        ]);
    }

    /**
     * Affiche le profil d'un chien.
     * @param Chien $chien
     * @param CommentaireRepository $commentaireRepository
     * @param $id
     * @return Response
     */
    #[Route('/profil/{id}', name: 'app_chien_show', methods: ['GET'])]
    public function show(Chien $chien, CommentaireRepository $commentaireRepository, $id = null): Response
    {
        $commentaire = $commentaireRepository->findBy(['fk_id_chien' => $id]);
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

    /**
     * Modifie le profil d'un chien.
     * @param Request $request
     * @param Chien $chien
     * @param EntityManagerInterface $entityManager
     * @param ChienRepository $chienRepository
     * @param FileUploaderService $file_uploader
     * @param $publicUploadDir
     * @param $deleteFolder
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_chien_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request, Chien $chien, EntityManagerInterface $entityManager,
                         ChienRepository $chienRepository, FileUploaderService $file_uploader,
                                         $publicUploadDir, $deleteFolder): Response
    {
        $ancienChien = $chienRepository->find($chien->getId());
        $photo = $ancienChien->getPhoto();

        $form = $this->createForm(ChienType::class, $chien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();

            if ($file !== null) {
                @unlink($deleteFolder . $photo);
                $this->doUpload($file, $chien, $file_uploader, $publicUploadDir);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chien/edit.html.twig', [
            'chien' => $chien,
            'form' => $form,
        ]);
    }

    /**
     * Supprime le profil d'un chien, ses commentaires et ses correspondances.
     * @param Request $request
     * @param Chien $chien
     * @param EntityManagerInterface $entityManager
     * @param CorrespondanceRepository $correspondanceRepository
     * @param CommentaireRepository $commentaireRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_chien_delete', methods: ['POST'])]
    public function delete(Request                  $request, Chien $chien, EntityManagerInterface $entityManager,
                           CorrespondanceRepository $correspondanceRepository, CommentaireRepository $commentaireRepository): Response
    {
        $idChien = $chien->getId();
        $correspondances = $correspondanceRepository->findBy(['fk_id_chien' => $idChien]);
        $commentaires = $commentaireRepository->findBy(['fk_id_chien' => $idChien]);

        foreach ($correspondances as $correspondance) {
            $entityManager->remove($correspondance);
        }

        foreach ($commentaires as $commentaire) {
            $entityManager->remove($commentaire);
        }

        if ($this->isCsrfTokenValid('delete' . $chien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Upload la photo enregistrée par l'admin.
     * @param $file
     * @param $chien
     * @param $file_uploader
     * @param $publicUploadDir
     * @return void
     */
    private function doUpload($file, $chien, $file_uploader, $publicUploadDir): void
    {
        $file_name = $file_uploader->upload($file);

        if ($file_name !== null) {
            $file_path = $publicUploadDir . '/' . $file_name;
            $chien->setPhoto($file_path);
        }
    }
}
