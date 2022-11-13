<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Menu;
use App\Entity\Message;
use App\Entity\Option;
use App\Entity\User;
use App\Entity\Video;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator){}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Back To Home', 'fa fa-undo', 'app_home');

        if ($this->isGranted('ROLE_AUTHOR')) {
            yield MenuItem::subMenu('Article', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('All articles', 'fas fa-newspaper', Article::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class)
            ]);

            yield MenuItem::subMenu('Media', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Media Library', 'fas fa-photo-video', Media::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Media::class)->setAction(Crud::PAGE_NEW),
            ]);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Comments', 'fas fa-comment', Comment::class);

            yield MenuItem::linkToCrud('Accounts', 'fas fa-user', User::class);
        }
    }
}
