<?php

namespace App\Repository;

interface IGetService
{
    public function getById(int $id): ?object;
    public function getAmount(int $amt): array;
    public function getAll(): array;

    function buildInstances(array $fetchedValues): array;
}