<?php

namespace App\Controller;

use App\Entity\NationLeader;
use App\Repository\NationLeaderService;
use App\Utils\DataInitialiser;
use App\Utils\DateTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NationLeadersController extends AbstractController
{
    private NationLeaderService $nationLeaderService;
    private DateTimeService $dateTimeService;

    private DataInitialiser $dataInitialiser;

    public function __construct(NationLeaderService $nationLeaderService,
                                DateTimeService $dateTimeService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->nationLeaderService = $nationLeaderService;
        $this->dateTimeService = $dateTimeService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['leaders'] = $this->nationLeaderService->getAll();

        foreach ($data['leaders'] as $leader) {
            /* @var $leader NationLeader */
            if ($leader->getLeadEndDate() != null) {
                $data['times'][$leader->getId() . '-' . $leader->getLeader()->getId()] = $this->dateTimeService->computeYearsAge(
                    $leader->getLeadStartDate(),
                    $leader->getLeadEndDate()
                );
            }
        }

        return $this->render('leaders/index.html.twig', $data);
    }
}