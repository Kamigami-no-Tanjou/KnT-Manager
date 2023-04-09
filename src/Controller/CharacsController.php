<?php

namespace App\Controller;

use App\Repository\CharacRelationshipService;
use App\Repository\CharacService;
use App\Repository\FamilyStatusService;
use App\Repository\GenderService;
use App\Repository\SexService;
use App\Utils\DataInitialiser;
use App\Utils\ParseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CharacsController extends AbstractController
{
    private CharacService $characService;
    private SexService $sexService;
    private ParseService $parseService;
    private GenderService $genderService;
    private CharacRelationshipService $characRelationshipService;
    private FamilyStatusService $familyStatusService;

    private DataInitialiser $dataInitialiser;

    public function __construct(CharacService $characService,
                                SexService $sexService,
                                GenderService $genderService,
                                CharacRelationshipService $characRelationshipService,
                                FamilyStatusService $familyStatusService,
                                ParseService $parseService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->characService = $characService;
        $this->sexService = $sexService;
        $this->genderService = $genderService;
        $this->characRelationshipService = $characRelationshipService;
        $this->familyStatusService = $familyStatusService;
        $this->parseService = $parseService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['characs'] = $this->characService->getAll();

        return $this->render('characs/index.html.twig', $data);
    }

    public function characsInfo(int $id): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['charac'] = $this->characService->getById($id);
        $data['hasPortrait'] = $this->characService->hasPortrait($data['charac']);

        $data['characSex'] = $this->sexService->getSexName($data['charac']->getSex());
        $data['characGender'] = $this->genderService->getGenderName($data['charac']->getGender());

        $data['characHeight'] = $this->parseService->parseHeight($data['charac']->getHeight());
        $data['characLoves'] = $this->parseService->parseTastes($data['charac']->getThingsLoved());
        $data['characHates'] = $this->parseService->parseTastes($data['charac']->getThingsHated());

        $data['relationships'] = $this->characRelationshipService->getByCharac($data['charac']);
        $data['familyStatusService'] = $this->familyStatusService;

        return $this->render('characs/infos.html.twig', $data);
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