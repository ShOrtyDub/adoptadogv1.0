<?php

namespace App\Controller;

use App\Entity\Correspondance;
use App\Form\CorrespondanceType;
use App\Repository\CorrespondanceRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/correspondance')]
class CorrespondanceController extends AbstractController
{
    #[Route('/{id}', name: 'app_correspondance_index', methods: ['GET'])]
    public function index(CorrespondanceRepository $correspondanceRepository, UtilisateurRepository $utilisateurRepository, $id = null): Response
    {
        // TODO réussir à avoir la fkchien et la fkutilisateur pour afficher la vue du match
        $correspondances = $correspondanceRepository->findBy(['fk_id_utilisateur' => 59]);

        $chiens = [];
        $utilisateur = $utilisateurRepository->find($id);
        foreach ($correspondances as $correspondance) {
            $chiens[] = $correspondance->getFkIdChien();
        }

        return $this->render('correspondance/index.html.twig', [
            'correspondances' => $correspondanceRepository->findAll(),
            'chiens' => $chiens,
            'utilisateur' => $utilisateur
        ]);
    }

    #[Route('/new/{id}', name: 'app_correspondance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository, $id = null): Response
    {
        // TODO créer la correspondance avec des chiens. Récupérer l'id de l'user pour cela.
        $correspondance = new Correspondance();
        $utilisateur = $utilisateurRepository->find($id);
        $form = $this->createForm(CorrespondanceType::class, $correspondance);
//        dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($correspondance);
            $entityManager->flush();

            return $this->redirectToRoute('app_correspondance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('correspondance/new.html.twig', [
            'correspondance' => $correspondance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_correspondance_show', methods: ['GET'])]
    public function show(Correspondance $correspondance): Response
    {
        return $this->render('correspondance/show.html.twig', [
            'correspondance' => $correspondance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_correspondance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Correspondance $correspondance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CorrespondanceType::class, $correspondance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_correspondance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('correspondance/edit.html.twig', [
            'correspondance' => $correspondance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_correspondance_delete', methods: ['POST'])]
    public function delete(Request $request, Correspondance $correspondance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $correspondance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($correspondance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_correspondance_index', [], Response::HTTP_SEE_OTHER);
    }
}
