<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\Twig;
use App\Domain\User\UserRepository;
use App\Application\Actions\Auth\LoginAction;
use App\Application\Middleware\AuthMiddleware;
use App\Domain\Auth\SessionInterface;
use App\Application\Actions\Lead\ViewLeadsAction;
use App\Application\Actions\Lead\CreateLeadAction;
use App\Application\Actions\Lead\UpdateLeadAction;
use App\Application\Actions\Lead\EditLeadAction;
use App\Application\Actions\Lead\DeleteLeadAction;
use App\Application\Actions\Lead\DashboardAction;
use App\Application\Actions\Lead\NewLeadAction;
use App\Application\Actions\Lead\ViewPipelineAction;
use App\Application\Actions\Lead\UpdateLeadStatusAction;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });


    $app->get('/users', function (Request $request, Response $response) use ($app) {
        $container = $app->getContainer();
    
        $userRepo = $container->get(UserRepository::class);
        $twig = $container->get(Twig::class);
    
        $users = $userRepo->findAll();
    
        return $twig->render($response, 'users.twig', [
            'users' => $users,
        ]);
    });

    $app->get('/', function ($request, $response) {
        $view = Twig::fromRequest($request);
        
        return $view->render($response, 'home.html.twig', [
            'name' => 'Test',
        ]);
    });
    
    $app->group('', function (Group $group) {
        $group->get('/login', [LoginAction::class, 'show'])->setName('login');
        $group->post('/login', [LoginAction::class, 'login']);
        $group->get('/logout', [LoginAction::class, 'logout'])->setName('logout');
    });

    $app->group('/admin', function (Group $group) {
        $group->get('/leads', ViewLeadsAction::class)->setName('leads');
        $group->get('/dashboard', DashboardAction::class)->setName('dashboard');
        $group->get('/leads/create', NewLeadAction::class)->setName('lead.create');
        $group->post('/leads', CreateLeadAction::class)->setName('lead.post');
        $group->get('/leads/{id}/edit', EditLeadAction::class)->setName('lead.edit');
        $group->post('/leads/{id}/update', UpdateLeadAction::class)->setName('lead.update');
        $group->get('/leads/{id}/delete', DeleteLeadAction::class)->setName('lead.delete');
        $group->get('/pipeline', ViewPipelineAction::class)->setName('pipeline');
        // $this->post('/lead/{id}/status', UpdateLeadStatusAction::class)->setName('lead.update_status');
    })->add(AuthMiddleware::class);

    $app->post('/leads/{id}/status', UpdateLeadStatusAction::class)
    ->setName('lead.update_status');
};
