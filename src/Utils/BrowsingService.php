<?php

namespace App\Utils;

use App\Entity\_Class;
use App\Entity\Elem;
use App\Entity\Magic;
use App\Entity\Nation;
use App\Entity\Calendar;
use App\Entity\Genders;
use App\Entity\Sexes;
use App\Repository\CalendarService;
use App\Repository\CharacService;
use App\Repository\ClassService;
use App\Repository\ElemService;
use App\Repository\MagicService;
use App\Repository\MapService;
use App\Repository\NationLeaderService;
use App\Repository\NationService;
use App\Repository\QueryBuilders\CharacQueryBuilder;
use App\Repository\QueryBuilders\MagicQueryBuilder;
use App\Repository\QueryBuilders\MapQueryBuilder;
use App\Repository\QueryBuilders\NationQueryBuilder;
use App\Utils\PseudoEntities\SearchType;

class BrowsingService
{
    private CharacService $characService;
    private MagicService $magicService;
    private MapService $mapService;
    private NationService $nationService;
    private NationLeaderService $nationLeaderService;
    private CalendarService $calendarService;
    private ClassService $classService;
    private ElemService $elemService;
    private DateTimeService $dateTimeService;

    private CharacQueryBuilder $characQueryBuilder;
    private MagicQueryBuilder $magicQueryBuilder;
    private MapQueryBuilder $mapQueryBuilder;
    private NationQueryBuilder $nationQueryBuilder;

    public function __construct(CharacService $characService,
                                MagicService $magicService,
                                MapService $mapService,
                                NationService $nationService,
                                NationLeaderService $nationLeaderService,
                                CalendarService $calendarService,
                                ClassService $classService,
                                ElemService $elemService,
                                DateTimeService $dateTimeService,

                                CharacQueryBuilder $characQueryBuilder,
                                MagicQueryBuilder $magicQueryBuilder,
                                MapQueryBuilder $mapQueryBuilder,
                                NationQueryBuilder $nationQueryBuilder
    ) {
        $this->characService = $characService;
        $this->magicService = $magicService;
        $this->mapService = $mapService;
        $this->nationService = $nationService;
        $this->nationLeaderService = $nationLeaderService;
        $this->calendarService = $calendarService;
        $this->classService = $classService;
        $this->elemService = $elemService;
        $this->dateTimeService = $dateTimeService;

        $this->characQueryBuilder = $characQueryBuilder;
        $this->magicQueryBuilder = $magicQueryBuilder;
        $this->mapQueryBuilder = $mapQueryBuilder;
        $this->nationQueryBuilder = $nationQueryBuilder;
    }

    public function browse(string $research): array {
        $results = array(
            "characs" => array(),
            "magics" => array(),
            "maps" => array(),
            "nations" => array(),
            "leaders" => array()
        );

        if ($research == "") {
            return $results;
        } else {
            $research = "%" . $research . "%";
        }

        $results["characs"] = $this->characService->simpleSearch($research);
        $results["magics"] = $this->magicService->simpleSearch($research);
        $results["maps"] = $this->mapService->simpleSearch($research);
        $results["nations"] = $this->nationService->simpleSearch($research);
        $results["leaders"] = $this->nationLeaderService->simpleSearch($research);

        return $results;
    }

    public function browseAdvanced(SearchType $searchType, string $research, array $formData): array {
        $results = array();

        switch ($searchType) {
            case SearchType::Charac:
                $results = $this->browseAdvancedCharac($research, $formData);
                break;
            case SearchType::Magic:
                $results = $this->browseAdvancedMagic($research, $formData);
                break;
            case SearchType::Map:
                $results = $this->browseAdvancedMap($research, $formData);
                break;
            case SearchType::Nation:
                $results = $this->browseAdvancedNation($research, $formData);
                break;
            default:
                break;
        }

        return $results;
    }

    public function browseAdvancedCharac(string $research, array $formData): array {
        $searchTokens = explode(" ", $research);
        $variables = array();

        // Let's care about the form data first:
        $calendars = $this->calendarService->getAll();
        $classes = $this->classService->getAll();
        $nations = $this->nationService->getAll();
        $magics = $this->magicService->getAll();


        if (isset($formData["charac-has-portrait"]) && $formData["charac-has-portrait"] == "true") {
            $this->characQueryBuilder->where("Portrait IS NOT NULL");
        } elseif (isset($formData["charac-has-portrait"])) {
            $this->characQueryBuilder->where("Portrait IS NULL");
        }

        foreach ($calendars as $cal) {
            /* @var $cal Calendar */
            if (!isset($formData["charac-cal-" . $cal->getId()])) {
                $this->characQueryBuilder->where("Calendar != " . $cal->getId());
            }
        }

        if ($formData["charac-living-after"] != "") {
            $this->characQueryBuilder->where("Deathdate >= :livingAfter");
            $variables["livingAfter"] = $this->dateTimeService->parseDateTime($formData["charac-living-after"])->format('Y-m-d');
        }
        if ($formData["charac-living-before"] != "") {
            $this->characQueryBuilder->where("Birthdate <= :livingBefore");
            $variables["livingBefore"] = $this->dateTimeService->parseDateTime($formData["charac-living-before"])->format('Y-m-d');
        }

        if ($formData["charac-potential-above"] != "") {
            $this->characQueryBuilder->where("MagicalPotential >= :potentialAbove");
            $variables["potentialAbove"] = $formData["charac-potential-above"];
        }
        if ($formData["charac-potential-below"] != "") {
            $this->characQueryBuilder->where("MagicalPotential <= :potentialBelow");
            $variables["potentialBelow"] = $formData["charac-potential-below"];
        }

        foreach ($classes as $_class) {
            /* @var $_class _Class */
            if (!isset($formData["charac-class-" . $_class->getId()])) {
                $this->characQueryBuilder->where("Class != " . $_class->getId());
            }
        }

        foreach (Sexes::cases() as $sex) {
            if (!isset($formData["charac-sex-" . $sex->value])) {
                $this->characQueryBuilder->where("Sex != " . $sex->value);
            }
        }

        foreach (Genders::cases() as $gender) {
            if (!isset($formData["charac-gender-" . $gender->value])) {
                $this->characQueryBuilder->where("Gender != " . $gender->value);
            }
        }

        foreach ($nations as $nation) {
            /* @var $nation Nation */
            if (!isset($formData["charac-nat-" . $nation->getId()])) {
                $this->characQueryBuilder->where("Origin != " . $nation->getId());
            }

            if (!isset($formData["charac-led-" . $nation->getId()])) {
                $this->characQueryBuilder->where("Nation != " . $nation->getId());
            }
        }

        if ($formData["charac-height-above"] != "") {
            $this->characQueryBuilder->where("Height >= :heightAbove");
            $variables["heightAbove"] = $formData["charac-height-above"];
        }
        if ($formData["charac-height-below"] != "") {
            $this->characQueryBuilder->where("Height <= :heightBelow");
            $variables["heightBelow"] = $formData["charac-height-below"];
        }

        foreach ($magics as $magic) {
            /* @var $magic Magic */
            if (!isset($formData["charac-mag-" . $magic->getId()])) {
                $this->characQueryBuilder->where("Magic != " . $magic->getId());
            }
        }

        if (isset($formData["charac-relation-type"]) && isset($formData["charac-relation-with"])) {
            $this->characQueryBuilder->where("TowardsCharac = :characRelationWith");
            $variables["characRelationWith"] = $formData["charac-relation-with"];

            if ($formData["charac-relation-type"] == "appreciates") {
                $this->characQueryBuilder->where("Appreciation > 50");
            } elseif ($formData["charac-relation-type"] == "not-appreciates") {
                $this->characQueryBuilder->where("Appreciation < 50");
            }
        }

        // And now it is time to care about the keywords.
        $this->characQueryBuilder->addKeyWords($searchTokens);

        return $this->characQueryBuilder->getMatches($variables);
    }

    public function browseAdvancedMagic(string $research, array $formData): array {
        $searchTokens = explode(" ", $research);
        $variables = array();

        // Let's care about the form data first:
        $elements = $this->elemService->getAll();

        foreach ($elements as $elem) {
            /* @var $elem Elem */
            if (!isset($formData["magic-elem-" . $elem->getId()])) {
                $this->magicQueryBuilder->where("Elems.ID != " . $elem->getId());
            }
        }

        // And now it is time to care about the keywords.
        $this->magicQueryBuilder->addKeyWords($searchTokens);

        return $this->magicQueryBuilder->getMatches($variables);
    }

    public function browseAdvancedMap(string $research, array $formData): array {
        $searchTokens = explode(" ", $research);
        $variables = array();

        // Let's care about the form data first:
        $nations = $this->nationService->getAll();

        foreach ($nations as $nation) {
            /* @var $nation Nation */
            if (!isset($formData["map-nation-" . $nation->getId()])) {
                $this->mapQueryBuilder->where("Nation != " . $nation->getId());
            }
        }

        // And now it is time to care about the keywords.
        $this->mapQueryBuilder->addKeyWords($searchTokens);

        return $this->mapQueryBuilder->getMatches($variables);
    }

    public function browseAdvancedNation(string $research, array $formData): array {
        $searchTokens = explode(" ", $research);
        $variables = array();

        // Let's care about the form data first:
        $calendars = $this->calendarService->getAll();

        foreach ($calendars as $cal) {
            /* @var $cal Calendar */
            if (!isset($formData["nation-cal-" . $cal->getId()])) {
                $this->nationQueryBuilder->where("Calendar != " . $cal->getId());
            }
        }

        if ($formData["nation-existing-after"] != "") {
            $this->nationQueryBuilder->where("DestructionDate >= :existingAfter");
            $variables["existingAfter"] = $this->dateTimeService->parseDateTime($formData["nation-existing-after"])->format('Y-m-d');
        }
        if ($formData["nation-existing-before"] != "") {
            $this->nationQueryBuilder->where("FoundationDate <= :existingBefore");
            $variables["existingBefore"] = $this->dateTimeService->parseDateTime($formData["nation-existing-before"])->format('Y-m-d');
        }

        // And now it is time to care about the keywords.
        $this->nationQueryBuilder->addKeyWords($searchTokens);

        return $this->nationQueryBuilder->getMatches($variables);
    }

    private function or(string $column, string $operator, string $condition, $value): string {
        if ($condition == "") {
            return $column . $operator . $value;
        } else {
            return $condition . " OR " . $column . $operator . $value;
        }
    }
}