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

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // $app->group('/users', function (Group $group) {
    //     $group->get('', ListUsersAction::class);
    //     $group->get('/{id}', ViewUserAction::class);
    // });

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
        // Protected route - accessible only if authenticated
        $group->get('/dashboard', function (Request $request, Response $response, $args) {
            /** @var SessionInterface $session */
            $session = $this->get(SessionInterface::class); 
            
            $user = $session->get('user'); 
            
            if (!$user) {
                
                $routeParser = \Slim\Routing\RouteContext::fromRequest($request)->getRouteParser();
                return $response
                    ->withHeader('Location', $routeParser->urlFor('login')) 
                    ->withStatus(302);
            }
    
            $view = Twig::fromRequest($request);
            return $view->render($response, 'admin/dashboard.twig', [
                'user' => $user
            ]);
        })->setName('dashboard');
    })->add(AuthMiddleware::class);
};
