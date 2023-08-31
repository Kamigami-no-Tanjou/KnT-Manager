<?php

namespace App\Utils;

use App\Entity\Charac;
use App\Entity\Genders;
use App\Entity\Sexes;
use App\Repository\CharacService;
use App\Utils\PseudoEntities\Family;
use App\Utils\PseudoEntities\FamilyType;
use DateTime;

class FamilyTreeService
{
    private int $anonymousId = 1;
    private string $head =
        "0 HEAD
        1 SOUR Family Echo
        2 WWW http://www.familyecho.com/\n";
    private string $gedcom =
        "1 DEST ANSTFILE
        1 GEDC
        2 VERS 5.5.1
        2 FORM LINEAGE-LINKED\n";

    private string $charset =
        "1 SUBN
        1 CHAR UTF-8\n";

    private string $fakeCharacterDescription = "This is a fake character";

    public function generateFamilyTreeLink(Charac $character, CharacService $characService): string {
        $family = $this->generateCharacGedcom($character, $characService);
        // $html = str_ireplace("\n", "<br>", $family);
        // echo $html;

        $format = "json";
        $focus = $this->getCharacterId($character);
        $hide_header = 1;
        $hide_navbar = 1;
        $params = compact("format", "family", "focus", "hide_header", "hide_navbar");
        $payload = "operation=temp_view";

        foreach ($params as $key => $value) {
            if (isset($value)) {
                $payload .= "&" . urlencode($key) . "=" . urlencode($value);
            }
        }

        $curl = curl_init('https://www.familyecho.com/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $result = json_decode(curl_exec($curl));
        curl_close($curl);

        return $result->{'url'};
    }

    private function generateCharacGedcom(Charac $character, CharacService $characService): string {
        $date = new DateTime();

        $file = $this->head
            . "1 FILE " . $character->getLastNames() . "\n"
            . "1 DATE " . strtoupper($date->format("j M Y")) . "\n"
            . $this->gedcom
            . "1 SUBM " . $this->getCharacterId($character) . "\n"
            . "2 NAME " . $character->getFirstNames() . "\n"
            . $this->charset
        ;

        $families = $this->generateFamilies(FamilyType::None, $character, 1, $characService);


        $file .= $this->getCharactersAsGedcom(FamilyType::None, $character);

        foreach ($families as $family) {
            /* @var $family Family */
            $file .=
                "0 " . $family->getFamilyId() . " FAM\n";

            if ($family->getHusband() != null) {
                $file .=
                    "1 HUSB " . $this->getCharacterId($family->getHusband()) . "\n";
            }

            if ($family->getWife() != null) {
                $file .=
                    "1 WIFE " . $this->getCharacterId($family->getWife()) . "\n";
            }

            foreach ($family->getChildren() as $child) {
                /* @var $child Charac */
                $file .=
                    "1 CHIL " . $this->getCharacterId($child) . "\n";
            }

            $file .=
                "1 MARR
                1 _CURRENT Y
                1 _PRIMARY Y\n";
        }

        // end tag
        $file .= "0 TRLR";

        return $file;
    }

    private function getCharacterId(Charac $character): string {
        if (!$this->isFake($character)) {
            return "@I" . $character->getId() . "@";
        }
        return "@A" . $character->getId() . "@";
    }

    private function generateFamilies(FamilyType $type, Charac $character, int $index, CharacService $characService): array {
        $families = array();

        switch ($type) {
            case FamilyType::None:
                $childrenFamily = $this->generateChildrenFamily($character, $characService);
                $spouseFamily = $this->generateSpouseFamily($character, $characService);

                if ($childrenFamily != null) {
                    $childrenFamilyIndex = "@F" . $index . "@";
                    $childrenFamily->setFamilyId($childrenFamilyIndex);
                    $families[$childrenFamilyIndex] = $childrenFamily;
                    $index++;

                    $familiesUp = $this->retrieveFamiliesUp($families[$childrenFamilyIndex], $character, $index, $characService);
                    $index += sizeof($familiesUp);
                    $families = array_merge($families, $familiesUp);
                }

                if ($spouseFamily != null) {
                    $spouseFamilyIndex = "@F" . $index . "@";
                    $spouseFamily->setFamilyId($spouseFamilyIndex);
                    $families[$spouseFamilyIndex] = $spouseFamily;
                    $index++;

                    $familiesDown = $this->retrieveFamiliesDown($families[$spouseFamilyIndex], $character, $index, $characService);
                    $index += sizeof($familiesDown);
                    $families = array_merge($families, $familiesDown);
                }
                break;

            case FamilyType::SpouseFamily:
                $childrenFamily = $this->generateChildrenFamily($character, $characService);

                if ($childrenFamily != null) {
                    $childrenFamilyIndex = "@F" . $index . "@";
                    $childrenFamily->setFamilyId($childrenFamilyIndex);
                    $families[$childrenFamilyIndex] = $childrenFamily;
                    $index++;

                    $familiesUp = $this->retrieveFamiliesUp($families[$childrenFamilyIndex], $character, $index, $characService);
                    $index += sizeof($familiesUp);
                    $families = array_merge($families, $familiesUp);
                }
                break;

            case FamilyType::ChildrenFamily:
                $spouseFamily = $this->generateSpouseFamily($character, $characService);

                if ($spouseFamily != null) {
                    $spouseFamilyIndex = "@F" . $index . "@";
                    $spouseFamily->setFamilyId($spouseFamilyIndex);
                    $families[$spouseFamilyIndex] = $spouseFamily;
                    $index++;

                    $familiesDown = $this->retrieveFamiliesDown($families[$spouseFamilyIndex], $character, $index, $characService);
                    $index += sizeof($familiesDown);
                    $families = array_merge($families, $familiesDown);
                }
                break;
        }

        return $families;
    }

    private function retrieveFamiliesUp(Family $currentFamily, Charac $character, int $index, CharacService $characService): array {
        $families = array();

        foreach ($currentFamily->getChildren() as $broOrSis) {
            if ($broOrSis->getId() != $character->getId()) {
                $broOrSisFamilies = $this->generateFamilies(FamilyType::ChildrenFamily, $broOrSis, $index, $characService);
                $index += sizeof($broOrSisFamilies);
                $families = array_merge($families, $broOrSisFamilies);
            }
        }

        $father = $currentFamily->getHusband();
        if ($father != null) {
            $fatherFamilies = $this->generateFamilies(FamilyType::SpouseFamily, $father, $index, $characService);
            $index += sizeof($fatherFamilies);
            $families = array_merge($families, $fatherFamilies);
        }

        $mother = $currentFamily->getWife();
        if ($mother != null) {
            $motherFamilies = $this->generateFamilies(FamilyType::SpouseFamily, $mother, $index, $characService);
            $index += sizeof($motherFamilies);
            $families = array_merge($families, $motherFamilies);
        }

        return $families;
    }

    private function retrieveFamiliesDown(Family $currentFamily, Charac $character, int $index, CharacService $characService): array {
        $families = array();

        foreach ($currentFamily->getChildren() as $child) {
            $childrenFamilies = $this->generateFamilies(FamilyType::ChildrenFamily, $child, $index, $characService);
            $index += sizeof($childrenFamilies);
            $families = array_merge($families, $childrenFamilies);
        }

        $father = $currentFamily->getHusband();
        if ($father != null && $father->getId() != $character->getId()) {
            $fatherFamilies = $this->generateFamilies(FamilyType::SpouseFamily, $father, $index, $characService);
            $index += sizeof($fatherFamilies);
            $families = array_merge($families, $fatherFamilies);
        }

        $mother = $currentFamily->getWife();
        if ($mother != null && $mother->getId() != $character->getId()) {
            $motherFamilies = $this->generateFamilies(FamilyType::SpouseFamily, $mother, $index, $characService);
            $index += sizeof($motherFamilies);
            $families = array_merge($families, $motherFamilies);
        }

        return $families;
    }

    private function generateChildrenFamily(Charac $character, CharacService $characService): ?Family {
        if ($this->isFake($character)) {
            return null;
        }

        $father = $characService->getFather($character);
        $mother = $characService->getMother($character);
        $brosAndSis = $characService->getBrothersAndSisters($character); // Shall not include the character itself

        // If we found none, we return null
        if ($father == null && $mother == null && sizeof($brosAndSis) == 0) {
            return null;
        } else if ($father == null && $mother == null) {
            // We create fake fathers and mothers in case both are null but there are children (otherwise they won't be
            // displayed)
            $father = $this->createFakeFather($character);
            $mother = $this->createFakeMother($character);
        }



        $family = new Family();
        $family->setHusband($father);
        $family->setWife($mother);
        $family->setChildren(array_merge(array($character), $brosAndSis));

        // Affecting the family
        if ($father != null) {
            $father->setSpouseFamily($family);
        }
        if ($mother != null) {
            $mother->setSpouseFamily($family);
        }
        foreach ($family->getChildren() as $child) {
            /* @var $child Charac */
            $child->setChildrenFamily($family);
        }

        return $family;
    }

    private function generateSpouseFamily(Charac $character, CharacService $characService): ?Family {
        if ($this->isFake($character)) {
            return null;
        }

        $spouse = $characService->getSpouse($character);
        $children = $characService->getChildren($character); // Ideally, this method should take both parents

        // If we found none, we return null
        if ($spouse == null && sizeof($children) == 0) {
            return null;
        }

        $family = new Family();
        $family->setHusband(($character->getSex() == Sexes::Masculine) ? $character : $spouse);
        $family->setWife(($character->getSex() == Sexes::Masculine) ? $spouse : $character); // Condition is made so to be sure to have both characters in the family at the end in the case they are both of the same sex
        $family->setChildren($children);

        // Affecting the family
        $character->setSpouseFamily($family);
        if ($spouse != null) {
            $spouse->setSpouseFamily($family);
        }
        foreach ($family->getChildren() as $child) {
            /* @var $child Charac */
            $child->setChildrenFamily($family);
        }

        return $family;
    }

    private function getCharactersAsGedcom(FamilyType $type, Charac $startingCharac): string {
        $characters = $this->getCharacAsGedcom($startingCharac);

        switch ($type) {
            case FamilyType::None:
                if ($startingCharac->getChildrenFamily() != null) {
                    $characters .= $this->childrenFamilyCharactersAsGedcom($startingCharac);
                }

                if ($startingCharac->getSpouseFamily() != null) {
                    $characters .= $this->spouseFamilyCharactersAsGedcom($startingCharac);
                }
                break;

            case FamilyType::SpouseFamily:
                if ($startingCharac->getChildrenFamily() != null) {
                    $characters .= $this->childrenFamilyCharactersAsGedcom($startingCharac);
                }
                break;

            case FamilyType::ChildrenFamily:
                if ($startingCharac->getSpouseFamily() != null) {
                    $characters .= $this->spouseFamilyCharactersAsGedcom($startingCharac);
                }
                break;
        }

        return $characters;
    }

    private function childrenFamilyCharactersAsGedcom(Charac $character): string {
        $characters = "";

        foreach ($character->getChildrenFamily()->getChildren() as $broOrSis) {
            if ($broOrSis->getId() != $character->getId()) {
                $characters .= $this->getCharactersAsGedcom(FamilyType::ChildrenFamily, $broOrSis);
            }
        }

        $father = $character->getChildrenFamily()->getHusband();
        if ($father != null) {
            $characters .= $this->getCharactersAsGedcom(FamilyType::SpouseFamily, $father);
        }

        $mother = $character->getChildrenFamily()->getWife();
        if ($mother != null) {
            $characters .= $this->getCharactersAsGedcom(FamilyType::SpouseFamily, $mother);
        }

        return $characters;
    }

    private function spouseFamilyCharactersAsGedcom(Charac $character): string {
        $characters = "";

        foreach ($character->getSpouseFamily()->getChildren() as $child) {
            $characters .= $this->getCharactersAsGedcom(FamilyType::ChildrenFamily, $child);
        }

        $father = $character->getSpouseFamily()->getHusband();
        if ($father != null && $father->getId() != $character->getId()) {
            $characters .= $this->getCharactersAsGedcom(FamilyType::SpouseFamily, $father);
        }

        $mother = $character->getSpouseFamily()->getWife();
        if ($mother != null && $mother->getId() != $character->getId()) {
            $characters .= $this->getCharactersAsGedcom(FamilyType::SpouseFamily, $mother);
        }

        return $characters;
    }

    private function getCharacAsGedcom(Charac $character): string {
        $gedcom =
            "0 " . $this->getCharacterId($character) . " INDI
            1 NAME " . $character->getFirstNames() . " /" . $character->getLastNames() . "/
            2 GIVN " . $character->getFirstNames() . "
            2 SURN " . $character->getLastNames() . "
            2 _MARN " . $this->getMarriageName($character) . "
            1 SEX " . $this->getGedcomSex($character) . "
            1 BIRT
            2 DATE " . strtoupper($character->getBirthdate()->format("j M Y")) . "
            2 PLAC " . $character->getOrigin()->getName() . "\n";

        if ($character->getDeathdate() != null) {
            $gedcom .=
                "1 DEAT Y
                2 DATE " . strtoupper($character->getDeathdate()->format("j M Y")) . "\n";
        }

        if ($character->getChildrenFamily() != null) {
            $gedcom .=
                "1 FAMC " . $character->getChildrenFamily()->getFamilyId() . "\n";
        }

        if ($character->getSpouseFamily() != null) {
            $gedcom .=
                "1 FAMS " . $character->getSpouseFamily()->getFamilyId() . "\n";
        }

        return $gedcom;
    }

    private function getMarriageName(Charac $character): string {
        $name = $character->getLastNames();

        if ($character->getSpouseFamily() != null && $character->getSpouseFamily()->getHusband() != null &&
            $character->getSpouseFamily()->getHusband()->getId() != $character->getId()) {
            $name = $character->getSpouseFamily()->getHusband()->getLastNames();
        }

        return $name;
    }

    private function getGedcomSex(Charac $character): string {
        return match ($character->getSex()) {
            Sexes::Feminine => 'F',
            Sexes::Masculine => 'M',
            Sexes::Unknown => 'O'
        };
    }

    private function createFakeCharacter(Charac $character): Charac {
        $fakeCharacter = new Charac();
        $fakeCharacter->setId($this->anonymousId);
        $fakeCharacter->setLastNames("" . $this->anonymousId);
        $fakeCharacter->setFirstNames("Anonyme");
        $fakeCharacter->setDescription($this->fakeCharacterDescription);
        $fakeCharacter->setCalendar($character->getCalendar());
        $fakeCharacter->setBirthdate(new DateTime());
        $fakeCharacter->setDeathdate(null);
        $fakeCharacter->setOrigin($character->getOrigin());

        $fakeCharacter->setChildrenFamily(null);
        $fakeCharacter->setSpouseFamily(null);

        $this->anonymousId++;

        return $fakeCharacter;
    }

    private function createFakeFather(Charac $character): Charac {
        $father = $this->createFakeCharacter($character);
        $father->setSex(Sexes::Masculine);
        $father->setGender(Genders::Man);

        return $father;
    }

    private function createFakeMother(Charac $character): Charac {
        $mother = $this->createFakeCharacter($character);
        $mother->setSex(Sexes::Feminine);
        $mother->setGender(Genders::Woman);

        return $mother;
    }

    private function isFake(Charac $character): bool {
        return $character->getDescription() == $this->fakeCharacterDescription;
    }
}