<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use App\Domain\Auth\SessionInterface;

class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private Twig $twig,
        private SessionInterface $session
    ) {}

    public function show(Request $request, Response $response): Response
    {
        if($this->session->has('user')){
            return $this->twig->render($response, 'admin/dashboard.twig');
        } else {
            return $this->twig->render($response, 'login.twig');
        }
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody() ?? [];

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $password === '') {
            return $this->twig->render($response, 'login.twig', [
                'error' => 'Username and password are required.',
            ]);
        }

        $user = $this->userRepository->findByUsername($username);

        if ($user && password_verify($password, $user->password)) {
            $this->session->set('user', [
                'username' => $user->getUsername()
            ]);
        
            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            return $response
                ->withHeader('Location', $routeParser->urlFor('dashboard'))
                ->withStatus(302);
        }

        return $this->twig->render($response, 'login.twig', [
            'error' => 'Invalid username or password.',
        ]);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->session->remove('user');

        return $response
            ->withHeader('Location', '/login')
            ->withStatus(302);
    }
}
