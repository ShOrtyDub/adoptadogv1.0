<?php

namespace App\Controller;

use App\Entity\Chien;
use App\Form\ChienType;
use App\Repository\ChienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chien')]
class ChienController extends AbstractController
{
    #[Route('/', name: 'app_chien_index', methods: ['GET'])]
    public function index(ChienRepository $chienRepository): Response
    {
        return $this->render('chien/index.html.twig', [
            'chiens' => $chienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chien = new Chien();
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

    #[Route('/{id}', name: 'app_chien_show', methods: ['GET'])]
    public function show(Chien $chien): Response
    {
        return $this->render('chien/show.html.twig', [
            'chien' => $chien,
        ]);
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
        if ($this->isCsrfTokenValid('delete'.$chien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chien_index', [], Response::HTTP_SEE_OTHER);
    }
}
