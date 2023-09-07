<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    /**
     * Affiche le tableau de tous les admins.
     * @param AdminRepository $adminRepository
     * @return Response
     */
    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    /**
     * Crée un nouvel admin.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request                     $request, EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $passwordHasher): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $admin->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $admin,
                $plaintextPaswword
            );

            $admin->setRoles(["ROLE_ADMIN"]);
            $admin->setPassword($hashedPassword);

            $entityManager->persist($admin);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    /**
     * Affiche la vue du profil de l'admin sélectionné.
     * @param Admin $admin
     * @return Response
     */
    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    /**
     * Modifie le profil d'un admin.
     * @param Request $request
     * @param Admin $admin
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request                     $request, Admin $admin, EntityManagerInterface $entityManager,
                         UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $admin->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $admin,
                $plaintextPaswword
            );

            $admin->setRoles(["ROLE_ADMIN"]);
            $admin->setPassword($hashedPassword);

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    /**
     * Supprime un admin.
     * @param Request $request
     * @param Admin $admin
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        $adminConnecte = $this->getUser();

        if ($adminConnecte === $admin) {
            if ($this->isCsrfTokenValid('Delete' . $admin->getId(), $request->request->get('_token'))) {
                $this->container->get('security.token_storage')->setToken(null);
                $entityManager->remove($admin);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);

        } else {
            if ($this->isCsrfTokenValid('Delete' . $admin->getId(), $request->request->get('_token'))) {
                $entityManager->remove($admin);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
