<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Monbloger');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Go back to website', 'fa fa-undo',"app_home");
        
        if($this->isGranted('ROLE_AUTHOR')){
        yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('All Articles','fas fa-newspaper',Article::class),
            MenuItem::linkToCrud('Add article','fas fa-plus',Article::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Medias', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('All medias','fas fa-photo-video',Media::class),
            MenuItem::linkToCrud('Add medias','fas fa-plus',Media::class)->setAction(Crud::PAGE_NEW),

        ]);
        }

        if($this->isGranted('ROLE_ADMIN')){
            yield MenuItem::linkToCrud('Comments','fas fa-comment',Comment::class);

            yield MenuItem::subMenu('Account', 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud('All account','fas fa-user-friends',User::class),
                MenuItem::linkToCrud('Add account','fas fa-plus',User::class)->setAction(Crud::PAGE_NEW),
            ]);

        }

        


    }
}
