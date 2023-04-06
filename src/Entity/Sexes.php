<?php

namespace App\Entity;

enum Sexes: int
{
    case Feminine = 1;
    case Masculine = 2;
    case Unknown = 3;
}