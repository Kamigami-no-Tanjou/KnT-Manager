<?php

namespace App\Controller;

use App\Repository\CharacService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CharacsController extends AbstractController
{
    private CharacService $characService;

    public function __construct(CharacService $characService) {
        $this->characService = $characService;
    }

    public function index(): Response {
        // Data retrieval
        $data = array();
        $data['characs'] = $this->characService->getAll();

        return $this->render('characs/index.html.twig', $data);
    }



    public function characPortrait(int $id): Response {
        $portrait = $this->characService->getPortraitById($id);

        if ($portrait == null) {
            return new Response(null, 204);
        }

        $headers = array(
            'Content-Type' => "image/png"
        );

        return new Response($portrait, 200, $headers);
    }
}