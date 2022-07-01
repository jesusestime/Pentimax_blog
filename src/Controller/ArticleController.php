<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_show')]
    public function show(Article $article,UserRepository $userRepo,EntityManagerInterface $em,Request $request): Response
    {
        $comment=new Comment();

        $commentForm=$this->createForm(CommentType::class,$comment);

        $commentForm->handleRequest($request);
              
        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $user=$this->getUser();
            $comment->setUser($user);
            $comment->setArticle($article);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('article_show',['slug'=>$article->getSlug()]);
        }

        return $this->renderForm('article/show.html.twig', compact('article','commentForm'));
    }
}
