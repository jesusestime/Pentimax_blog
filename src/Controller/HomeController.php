<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepo,CategoryRepository $categoryRepo): Response
    {
        $article = $articleRepo->findAll();
        $category = $categoryRepo->findAll();
        return $this->render('home/index.html.twig', compact('article','category'));
    }

}
