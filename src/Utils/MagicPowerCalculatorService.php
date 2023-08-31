<?php

namespace App\Utils;

use App\Entity\Charac;

class MagicPowerCalculatorService
{
    public function magicFunction(float $potential, float $age): float {
        if ($age < 20) {
            $magicPower = 0.1 + (0.9 / sqrt(20)) * sqrt($age) * (-$age / 40 + 1.5);
        } else if ($age < 25) {
            $magicPower = 1;
        } else {
            $magicPower = (625 * (log($age) - log(25)) + 25 * $age) / ($age * $age);
        }

        return $magicPower * $potential;
    }

    public function multipleMagicFunction(float $potential, float $age, int $amount): array {
        $results = array();
        $results[] = $this->magicFunction($potential, $age);

        for ($i = 1; $i < $amount; $i++) {
            $results[] = $results[count($results) - 1] / 2;
        }

        return $results;
    }

    public function multipleMagicFunctionFromCharac(Charac $charac, float $age): array {
        $results = array();
        $results[] = $this->magicFunction($charac->getMagicalPotential(), $age);

        for ($i = 1; $i < count($charac->getMagics()); $i++) {
            $results[] = $results[count($results) - 1] / 2;
        }

        return $results;
    }
}