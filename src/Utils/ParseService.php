<?php

namespace App\Utils;

class ParseService
{
    public function parseList(string $input): array {
        return explode($input, ',');
    }

    public function parseIdList(string $input): array {
        $data = explode(',', $input);

        $ids = array();
        foreach ($data as $id) {
            $ids[] = (int) $id;
        }

        return $ids;
    }

    public function parseHeight(?int $height): string {
        if ($height == null) {
            return "Inconnue";
        }

        if ($height < 100) {
            return $height . "cm";
        }

        return floor($height / 100) . "m" . $height - 100;
    }
    public function parseTastes(?string $tastes): array {
        if ($tastes == null) {
            return array();
        }

        return explode('; ', $tastes);
    }
}