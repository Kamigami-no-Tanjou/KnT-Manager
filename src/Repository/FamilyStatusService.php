<?php

namespace App\Repository;

use App\Entity\FamilyStatus;

class FamilyStatusService
{
    public function getFamilyStatusName(FamilyStatus $familyStatus): string {
        return match ($familyStatus) {
            FamilyStatus::Father => "le père",
            FamilyStatus::Mother => "la mère",
            FamilyStatus::Brother => "le frère",
            FamilyStatus::Sister => "la sœur",
            FamilyStatus::Son => "le fils",
            FamilyStatus::Daughter => "la fille",
            FamilyStatus::Uncle => "l'oncle",
            FamilyStatus::Aunt => "la tante",
            FamilyStatus::CousinM => "le cousin",
            FamilyStatus::CousinF => "la cousine",
            FamilyStatus::GrandSon => "le petit-fils",
            FamilyStatus::GrandDaughter => "la petite-fille",
            FamilyStatus::GrandFather => "le grand-père",
            FamilyStatus::GrandMother => "la grand-mère",
            FamilyStatus::FatherInLaw => "le beau-père",
            FamilyStatus::MotherInLaw => "la belle-mère",
            FamilyStatus::BrotherInLaw => "le beau-frère",
            FamilyStatus::SisterInLaw => "la belle-sœur",
            FamilyStatus::Nephew => "le neveu",
            FamilyStatus::Niece => "la nièce",
            FamilyStatus::Husband => "le mari",
            FamilyStatus::Wife => "la femme",
        };
    }

    public function nullableFrom(?int $value): ?FamilyStatus {
        if ($value == null) {
            return null;
        }

        return FamilyStatus::from($value);
    }
}