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
        if ($map == null) {
            return $this->render('errors/404.html.twig', $this->dataInitialiser->getBaseData());
        }

        $headers = array(
            'Content-Type' => "image/png"
        );

        return new Response($map, 200, $headers);
    }

    public function mapInfo(int $id) {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data["map"] = $this->mapService->getById($id);
        if ($data['map'] == null) {
            return $this->render("errors/404.html.twig", $data);
        }

        return $this->render('maps/infos.html.twig', $data);
    }
}