<?php

namespace App\Entity;

enum Genders: int
{
    case Woman = 1;
    case Man = 2;
    case GenderFluid = 3;
}