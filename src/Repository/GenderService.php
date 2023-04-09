<?php

namespace App\Repository;

use App\Entity\Genders;

class GenderService
{
    public function getGenderName(Genders $gender): string {
        return match ($gender) {
            Genders::Woman => "Femme",
            Genders::Man => "Homme",
            Genders::GenderFluid => "Gender-fluid"
        };
    }
}