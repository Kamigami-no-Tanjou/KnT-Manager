<?php

namespace App\Utils\PseudoEntities;

enum SearchType: string
{
    case None = "none";
    case Charac = "charac";
    case Magic = "magic";
    case Map = "map";
    case Nation = "nation";
}