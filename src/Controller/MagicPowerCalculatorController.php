<?php

namespace App\Controller;

use App\Repository\CharacService;
use App\Utils\DataInitialiser;
use App\Utils\MagicPowerCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MagicPowerCalculatorController extends AbstractController
{
    private MagicPowerCalculatorService $magicPowerCalculatorService;
    private CharacService $characService;
    private DataInitialiser $dataInitialiser;

    public function __construct(MagicPowerCalculatorService $magicPowerCalculatorService,
                                CharacService $characService,
                                DataInitialiser $dataInitialiser
    ) {
        $this->magicPowerCalculatorService = $magicPowerCalculatorService;
        $this->characService = $characService;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function index(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        $data['characs'] = $this->characService->getAllIdAndNames();

        if (isset($_GET['magicalPotential']) && isset($_GET['age']) && isset($_GET['displayAmount'])) {
            $data['magicalPotential'] = $_GET['magicalPotential'];
            $data['age'] = $_GET['age'];
            $data['displayAmount'] = $_GET['displayAmount'];
            $data['charac'] = null;

            $data['freeResults'] = $this->magicPowerCalculatorService->multipleMagicFunction($data['magicalPotential'], $data['age'], $data['displayAmount']);
            $data['results'] = array();
        } elseif (isset($_GET['charac']) && isset($_GET['age'])) {
            $data['magicalPotential'] = null;
            $data['age'] = $_GET['age'];
            $data['displayAmount'] = 2;
            $data['charac'] = $this->characService->getById($_GET['charac']);

            $data['freeResults'] = array();
            if ($data['charac']->getMagicalPotential() != null) {
                $data['results'] = $this->magicPowerCalculatorService->multipleMagicFunctionFromCharac($data['charac'], $data['age']);
            } else {
                $data['results'] = array();
            }
        } else {
            $data['magicalPotential'] = null;
            $data['age'] = null;
            $data['displayAmount'] = 2;
            $data['charac'] = null;

            $data['freeResults'] = array();
            $data['results'] = array();
        }

        return $this->render("magic_power_calculator/index.html.twig", $data);
    }
}