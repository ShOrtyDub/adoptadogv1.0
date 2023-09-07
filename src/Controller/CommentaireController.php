<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Form\CommentaireType;
use App\Repository\ChienRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    /**
     * Crée un nouveau commentaire fait par un utilisateur ou un admin pour un chien.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ChienRepository $chienRepository
     * @param $id
     * @return Response
     * @throws \Exception
     */
    #[Route('/new/{id}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request         $request, EntityManagerInterface $entityManager,
                        ChienRepository $chienRepository, $id = null): Response
    {
        $commentaire = new Commentaire();
        $chien = $chienRepository->find($id);
        $utilisateur = $this->getUser();

        if ($utilisateur instanceof Utilisateur) {
            $commentaire->setFkIdUtilisateur($utilisateur);
        } else {
            $commentaire->setFkIdAdmin($utilisateur);
        }

        $commentaire->setFkIdChien($chien);

        $dateActuelle = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $commentaire->setDateCreation($dateActuelle);

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_chien_show', [
                'chien'=> $chien,
                'id' => $chien->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
            'chien'=> $chien,
        ]);
    }
}
