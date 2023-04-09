<?php

namespace App\Controller;

use App\Repository\MapService;
use App\Utils\DataInitialiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MapsController extends AbstractController
{
    private MapService $mapService;

    private DataInitialiser $dataInitialiser;

    public function __construct(MapService $mapService, DataInitialiser $dataInitialiser) {
        $this->mapService = $mapService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function mapImage(int $id): Response {
        $map = $this->mapService->getMapById($id);
        $headers = array(
            'Content-Type' => "image/png"
        );

        return new Response($map, 200, $headers);
    }
}