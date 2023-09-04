<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(AdminRepository $adminRepository): Response
    {
        $utilisateur = $this->getUser();
        if ($utilisateur instanceof Admin) {

            return $this->render('admin/adminMain.html.twig');
        } else {

            return $this->render('main/index.html.twig');
        }
    }
}
