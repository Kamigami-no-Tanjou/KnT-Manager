<?php

namespace App\Controller;

use App\Entity\Charac;
use App\Entity\CharacEvent;
use App\Repository\CharacEventService;
use App\Repository\CharacService;
use App\Utils\DataInitialiser;
use App\Utils\DateTimeService;
use App\Utils\ParseService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CharacEventsController extends AbstractController
{
    private CharacEventService $characEventService;
    private CharacService $characService;
    private DateTimeService $dateTimeService;
    private ParseService $parseService;

    private DataInitialiser $dataInitialiser;

    public function __construct(CharacEventService $characEventService,
                                CharacService $characService,
                                DateTimeService $dateTimeService,
                                ParseService $parseService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->characEventService = $characEventService;
        $this->characService = $characService;
        $this->dateTimeService = $dateTimeService;
        $this->parseService = $parseService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['characs'] = $this->characService->getAllWithEvents();
        $data['dateError'] = false;

        if (isset($_GET['from']) && isset($_GET['to'])) {
            $data['from'] = $_GET['from'];
            $data['to'] = $_GET['to'];

            try {
                $dateTimeFrom = $this->dateTimeService->parseDateTime($data['from']);
                $dateTimeTo = $this->dateTimeService->parseDateTime($data['to']);
                $data['events'] = $this->characEventService->getByPeriod($dateTimeFrom, $dateTimeTo);

                foreach ($data['events'] as $event) {
                    /* @var $event CharacEvent */
                    $data['ages'][$event->getId() . '-' . $event->getCharac()->getId()] = $this->dateTimeService->computeYearsAge(
                        $event->getCharac()->getBirthdate(),
                        $event->getEndingDate()
                    );
                }
            } catch (Exception) {
                $data['dateError'] = true;
                $data['events'] = null;
            }
        } else {
            $data['from'] = null;
            $data['to'] = null;
            $data['events'] = null;
        }

        return $this->render("events/index.html.twig", $data);
    }

    public function forCharac($characId): Response
    {
        $data = $this->dataInitialiser->getBaseData();
        $data['charac'] = $this->characService->getById($characId);
        if ($data['charac'] == null) {
            return $this->render("errors/404.html.twig", $data);
        }

        $data['family'] = $this->characService->getCharacFamily($data['charac']);
        $data['events'] = $this->characEventService->getByCharac($data['charac']);

        $data['extras'] = array();
        if (isset($_GET['extra']) && $_GET['extra'] != "") {
            $extraIds = $this->parseService->parseIdList($_GET['extra']);
            foreach($extraIds as $id) {
                $data['extras'][] = $this->characService->getById($id);
            }
        }

        if (isset($_GET['new'])) {
            $data['extras'][] = $this->characService->getById((int) $_GET['new']);
        }

        foreach ($data['events'] as $event) {
            /* @var $event CharacEvent */
            $data['ages'][$event->getId() . '-' . $event->getCharac()->getId()] = $this->dateTimeService->computeYearsAge(
                $event->getCharac()->getBirthdate(),
                $event->getEndingDate()
            );

            foreach($data['family'] as $charac) {
                /* @var $charac Charac */
                if ($charac->getBirthdate() > $event->getEndingDate()) {
                    $data['ages'][$event->getId() . '-' . $charac->getId()] = "";
                } else {
                    $data['ages'][$event->getId() . '-' . $charac->getId()] = $this->dateTimeService->computeYearsAge(
                        $charac->getBirthdate(),
                        $event->getEndingDate()
                    );
                }
            }

            foreach($data['extras'] as $charac) {
                /* @var $charac Charac */
                if ($charac != null && $charac->getBirthdate() > $event->getEndingDate()) {
                    $data['ages'][$event->getId() . '-' . $charac->getId()] = "";
                } elseif ($charac != null) {
                    $data['ages'][$event->getId() . '-' . $charac->getId()] = $this->dateTimeService->computeYearsAge(
                        $charac->getBirthdate(),
                        $event->getEndingDate()
                    );
                }
            }
        }

        $data['characList'] = $this->characService->getAllIdAndNames();

        return $this->render("events/charac.html.twig", $data);
    }
}