<?php

namespace App\Entity;

enum FamilyStatus: int
{
    case Father = 1;
    case Mother = 2;
    case Brother = 3;
    case Sister = 4;
    case Son = 5;
    case Daughter = 6;
    case Uncle = 7;
    case Aunt = 8;
    case CousinM = 9;
    case CousinF = 10;
    case GrandSon = 11;
    case GrandDaughter = 12;
    case GrandFather = 13;
    case GrandMother = 14;
    case FatherInLaw = 15;
    case MotherInLaw = 16;
    case BrotherInLaw = 17;
    case SisterInLaw = 18;
    case Nephew = 19;
    case Niece = 20;
    case Husband = 21;
    case Wife = 22;
    case SonInLaw = 23;
    case DaughterInLaw = 24;
}