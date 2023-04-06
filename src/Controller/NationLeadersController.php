<?php

namespace App\Controller;

use App\Entity\NationLeader;
use App\Repository\NationLeaderService;
use App\Utils\DateTimeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NationLeadersController extends AbstractController
{
    private NationLeaderService $nationLeaderService;
    private DateTimeService $dateTimeService;

    public function __construct(NationLeaderService $nationLeaderService,
                                DateTimeService $dateTimeService
    ) {
        $this->nationLeaderService = $nationLeaderService;
        $this->dateTimeService = $dateTimeService;
    }

    public function index(): Response {
        // Data retrieval
        $data = array();
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