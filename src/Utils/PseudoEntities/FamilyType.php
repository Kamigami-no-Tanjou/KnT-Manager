<?php

namespace App\Utils\PseudoEntities;

enum FamilyType
{
    case ChildrenFamily;
    case SpouseFamily;
    case None;
}