<?php

namespace App\Controller;

use App\Repository\CharacService;
use App\Repository\MapService;
use App\Repository\NationLeaderService;
use App\Repository\NationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NationsController extends AbstractController
{
    private NationService $nationService;
    private NationLeaderService $nationLeaderService;
    private CharacService $characService;

    private MapService $mapService;

    public function __construct(NationService $nationService,
                                NationLeaderService $nationLeaderService,
                                CharacService $characService,
                                MapService $mapService
    ) {
        $this->nationService = $nationService;
        $this->nationLeaderService = $nationLeaderService;
        $this->characService = $characService;
        $this->mapService = $mapService;
    }

    public function index(): Response {
        // Data retrieval
        $data = array();
        $data['nations'] = $this->nationService->getAll();

        return $this->render("nations/index.html.twig", $data);
    }

    public function nationInfo(int $id)
    {
        // Data retrieval
        $data = array();
        $data['nation'] = $this->nationService->getById($id);

        $data['leaders'] = $this->nationLeaderService->getByNation($data['nation']);

        $data['characs'] = $this->characService->getByNation($data['nation']);

        $data['maps'] = $this->mapService->getByNation($data['nation']);

        return $this->render("nations/info.html.twig", $data);
    }
}