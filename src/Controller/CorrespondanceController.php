<?php

namespace App\Controller;

use App\Entity\Correspondance;
use App\Form\CorrespondanceType;
use App\Repository\ChienRepository;
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
    /**
     * Affiche les correspondances trouvées avec les choix de l'utilisateur.
     * @param CorrespondanceRepository $correspondanceRepository
     * @param UtilisateurRepository $utilisateurRepository
     * @param Request $request
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'app_correspondance_index', methods: ['GET'])]
    public function index(CorrespondanceRepository $correspondanceRepository, UtilisateurRepository $utilisateurRepository, Request $request, $id = null): Response
    {
        $recherche = 0;

        if ($nouvelleRecherche = $request->query->get('autreRecherche')) {
            $recherche = $nouvelleRecherche;
        }

        $utilisateur = $utilisateurRepository->find($id);
        $correspondances = $correspondanceRepository->findBy(['fk_id_utilisateur' => $id]);
        $chiens = [];
        foreach ($correspondances as $correspondance) {
            $chiens[] = $correspondance->getFkIdChien();
        }

        return $this->render('correspondance/index.html.twig', [
            'correspondances' => $correspondanceRepository->findAll(),
            'chiens' => $chiens,
            'utilisateur' => $utilisateur,
            'recherche' => $recherche,
        ]);
    }

    /**
     * Fait une nouvelle recherche de correspondance avec des chiens.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CorrespondanceRepository $correspondanceRepository
     * @param UtilisateurRepository $utilisateurRepository
     * @param ChienRepository $chienRepository
     * @param $id
     * @return Response
     */
    #[Route('/new/{id}', name: 'app_correspondance_new', methods: ['GET', 'POST'])]
    public function new(Request               $request, EntityManagerInterface $entityManager, CorrespondanceRepository $correspondanceRepository,
                        UtilisateurRepository $utilisateurRepository, ChienRepository $chienRepository, $id = null): Response
    {
        $autreRecherche = 1;

        $correspondance = new Correspondance();
        $utilisateur = $utilisateurRepository->find($id);

        $form = $this->createForm(CorrespondanceType::class, $correspondance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $correspondanceRepository->deleteOldCorrespondence($utilisateur);

            $couleur = $correspondance->getCouleur();
            $race = $correspondance->getRace();
            $caractere = $correspondance->getCaractere();

            switch (true) {
                case $couleur === null && $race === null && $caractere === null:
                    // Aucun critère spécifié, renvoie tous les chiens
                    $chiens = $chienRepository->findAll();
                    break;

                case $couleur === null && $race !== null && $caractere !== null:
                    // Seule la race et le caractère sont spécifiés
                    $chiens = $chienRepository->findBy(['race' => $race, 'caractere' => $caractere]);
                    break;

                case $couleur !== null && $race === null && $caractere !== null:
                    // Seule la couleur et le caractère sont spécifiés
                    $chiens = $chienRepository->findBy(['couleur' => $couleur, 'caractere' => $caractere]);
                    break;

                case $couleur !== null && $race !== null && $caractere === null:
                    // Seule la couleur et la race sont spécifiées
                    $chiens = $chienRepository->findBy(['couleur' => $couleur, 'race' => $race]);
                    break;

                case $couleur !== null && $race === null && $caractere === null:
                    // Seule la couleur est spécifiée
                    $chiens = $chienRepository->findBy(['couleur' => $couleur]);
                    break;

                case $couleur === null && $race !== null && $caractere === null:
                    // Seule la race est spécifiée
                    $chiens = $chienRepository->findBy(['race' => $race]);
                    break;

                case $couleur === null && $race === null && $caractere !== null:
                    // Seul le caractère est spécifié
                    $chiens = $chienRepository->findBy(['caractere' => $caractere]);
                    break;

                case $couleur !== null && $race !== null && $caractere !== null:
                    // Couleur, race et caractère sont spécifiés
                    $chiens = $chienRepository->findBy(['couleur' => $couleur, 'race' => $race, 'caractere' => $caractere]);
                    break;
            }

            foreach ($chiens as $chien) {
                $nouvelleCorrespondance = new Correspondance();
                $nouvelleCorrespondance->setCouleur($couleur);
                $nouvelleCorrespondance->setRace($race);
                $nouvelleCorrespondance->setCaractere($caractere);
                $nouvelleCorrespondance->setFkIdChien($chien);
                $nouvelleCorrespondance->setFkIdUtilisateur($utilisateur);

                $entityManager->persist($nouvelleCorrespondance);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_correspondance_index', [
                'chiens' => $chiens,
                'utilisateur' => $utilisateur,
                'id' => $id,
                'autreRecherche' => $autreRecherche,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('correspondance/new.html.twig', [
            'correspondance' => $correspondance,
            'form' => $form,
        ]);
    }
}
