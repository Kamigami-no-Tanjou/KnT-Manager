<?php

namespace App\Utils;

use App\Repository\CalendarService;
use PDO;
use Symfony\Component\HttpFoundation\Request;

class DataInitialiser
{
    private CalendarService $calendarService;
    private PDO $context;

    public function __construct(CalendarService $calendarService,
                                PDOService $PDOService) {
        $this->context = $PDOService->GetPDO();
        $this->calendarService = $calendarService;
    }

    public function getBaseData(): array {
        $request = Request::createFromGlobals();
        $data = array();

        $data['headerCalendars'] = $this->calendarService->getAll();


        $data['viewCalendar'] = $request->cookies->get('viewCalendar');
        $data['functions'] = new FunctionContainer($this->context);

        return $data;
    }
}