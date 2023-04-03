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
}