<?php

namespace App\Utils;

use PDO;
use Symfony\Component\Yaml\Yaml;

class PDOService
{
    public function GetPDO() : PDO {
        $params = Yaml::parse(file_get_contents( '../config/pdo.yaml'))["db"];
        $pdo = new PDO(
            "mysql:host=" . $params['host'] . ";dbname=" . $params['dbname'],
            $params['user'],
            $params['pass']
        );

        return $pdo;
    }
}