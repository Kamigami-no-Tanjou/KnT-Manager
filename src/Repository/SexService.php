<?php

namespace App\Repository;

use App\Entity\Sexes;

class SexService
{
    public function getSexName(Sexes $sex): string {
        return match ($sex) {
            Sexes::Feminine => "Féminin",
            Sexes::Masculine => "Masculin",
            Sexes::Unknown => "Inconnu"
        };
    }
}