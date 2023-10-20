<?php

declare(strict_types=1);

namespace PostHog\PostHogBundle\Tests\End2End\App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function main(): Response
    {
        return new Response(<<<HTML
<html lang="en">
<head>
<title>PostHog - Demo</title>
</head>
<body>
<h1>Welcome</h1>
<p>Welcome to your new experience matey.</p>
</body>
</html>
HTML);
    }
}
