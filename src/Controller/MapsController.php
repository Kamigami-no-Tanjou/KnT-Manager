<?php

namespace App\Controller;

use App\Repository\MapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MapsController extends AbstractController
{
    private MapService $mapService;

    public function __construct(MapService $mapService) {
        $this->mapService = $mapService;
    }

    public function mapImage(int $id): Response {
        $map = $this->mapService->getMapById($id);
        $headers = array(
            'Content-Type' => "image/png"
        );

        return new Response($map, 200, $headers);
    }
}