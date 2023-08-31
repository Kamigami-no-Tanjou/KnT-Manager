<?php

namespace App\Controller;

use App\Entity\Genders;
use App\Entity\NationLeader;
use App\Entity\Charac;
use App\Entity\Sexes;
use App\Repository\CharacService;
use App\Repository\ClassService;
use App\Repository\ElemService;
use App\Repository\GenderService;
use App\Repository\MagicService;
use App\Repository\NationService;
use App\Repository\SexService;
use App\Utils\BrowsingService;
use App\Utils\DataInitialiser;
use App\Utils\PseudoEntities\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BrowsingController extends AbstractController
{
    private CharacService $characService;
    private ClassService $classService;
    private SexService $sexService;
    private GenderService $genderService;
    private NationService $nationService;
    private MagicService $magicService;
    private ElemService $elemService;
    private BrowsingService $browser;
    private DataInitialiser $dataInitialiser;

    public function __construct(CharacService $characService,
                                ClassService $classService,
                                SexService $sexService,
                                GenderService $genderService,
                                NationService $nationService,
                                MagicService $magicService,
                                ElemService $elemService,
                                BrowsingService $browser,
                                DataInitialiser $dataInitialiser) {
        $this->characService = $characService;
        $this->classService = $classService;
        $this->sexService = $sexService;
        $this->genderService = $genderService;
        $this->nationService = $nationService;
        $this->magicService = $magicService;
        $this->elemService = $elemService;
        $this->browser = $browser;

        $this->dataInitialiser = $dataInitialiser;
    }

    public function browse(): Response {
        // Data retrieval
        $data = $this->dataInitialiser->getBaseData();
        if (isset($_GET["search"])) {
            $data["search"] = $_GET["search"];
        } else {
            $data["search"] = "";
        }

        $data["result"] = $this->browser->browse($data["search"]); // Has to be done in any case
        $data["hasPortrait"] = array();
        $data["hasPortraitLeader"] = array();

        foreach (SearchType::cases() as $type) {
            $data[$type->value . "Results"] = array();
        }

        if (isset($_GET["type"])) {
            $searchType = SearchType::from($_GET["type"]);
            $data[$searchType->value . "Results"] = $this->browser->browseAdvanced($searchType, $data["search"], $_GET);
            $data["searchType"] = $searchType->value;
        } else {
            $data["searchType"] = SearchType::None->value;
        }

        foreach ($data["result"]["characs"] as $charac) {
            /* @var $charac Charac */
            $data["hasPortrait"][$charac->getId()] = $this->characService->hasPortrait($charac);
        }
        foreach ($data["result"]["leaders"] as $leader) {
            /* @var $leader NationLeader */
            $data["hasPortraitLeader"][$leader->getLeader()->getId()] = $this->characService->hasPortrait($leader->getLeader());
        }
        foreach ($data[SearchType::Charac->value . "Results"] as $charac) {
            /* @var $charac Charac */
            $data["hasPortrait"][$charac->getId()] = $this->characService->hasPortrait($charac);
        }

        // Form data
        $data["classes"] = $this->classService->getAll();
        $data["sexes"] = array(
            1 => $this->sexService->getSexName(Sexes::Feminine),
            2 => $this->sexService->getSexName(Sexes::Masculine),
            3 => $this->sexService->getSexName(Sexes::Unknown)
        );
        $data["genders"] = array(
            1 => $this->genderService->getGenderName(Genders::Woman),
            2 => $this->genderService->getGenderName(Genders::Man),
            3 => $this->genderService->getGenderName(Genders::GenderFluid),
        );
        $data["nations"] = $this->nationService->getAll();
        $data["magics"] = $this->magicService->getAll();
        $data["characList"] = $this->characService->getAllIdAndNames();
        $data["elements"] = $this->elemService->getAll();

        $data["get"] = $_GET;

        return $this->render("browse/browse.html.twig", $data);
    }
}