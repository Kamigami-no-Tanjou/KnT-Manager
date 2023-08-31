<?php

namespace App\Utils;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceRegister
{
    public static function registerServices(ContainerBuilder $builder): void {
        // Adding PDOService for PDO DI
        $builder->register('PDO.service', 'App\Utils\PDOService');

        // Adding DateTimeService
        $builder->register('datetime.service', 'App\Utils\DateTimeService');

        // Adding ParseService
        $builder->register('parse.service', 'App\Utils\ParseService');

        // Adding MagicPowerCalculatorService
        $builder->register('magicpowercalculator.service', 'App\Utils\MagicPowerCalculatorService');

        // Adding FamilyTreeService
        $builder->register('familytree.service', 'App\Utils\FamilyTreeService');

        // Adding repository level services (DAO feeders)
        $builder->register('calendar.service', '\App\Repository\CalendarService');
        $builder->register('charac.service', '\App\Repository\CharacService');
        $builder->register('characevent.service', '\App\Repository\CharacEventService');
        $builder->register('characrelationship.service', '\App\Repository\CharacRelationshipService');
        $builder->register('class.service', '\App\Repository\ClassService');
        $builder->register('elem.service', '\App\Repository\ElemService');
        $builder->register('familystatus.service', '\App\Repository\FamilyStatusService');
        $builder->register('gender.service', 'App\Repository\GenderService');
        $builder->register('magic.service', '\App\Repository\MagicService');
        $builder->register('map.service', '\App\Repository\MapService');
        $builder->register('nation.service', '\App\Repository\NationService');
        $builder->register('nationleader.service', '\App\Repository\NationLeaderService');
        $builder->register('sex.service', 'App\Repository\SexService');

        $builder->register('charac.querybuilder.service', 'App\Repository\QueryBuilders\CharacQueryBuilder');
        $builder->register('magic.querybuilder.service', 'App\Repository\QueryBuilders\MagicQueryBuilder');
        $builder->register('map.querybuilder.service', 'App\Repository\QueryBuilders\MapQueryBuilder');
        $builder->register('nation.querybuilder.service', 'App\Repository\QueryBuilders\NationQueryBuilder');

        $builder->register('browsing.service', '\App\Utils\BrowsingService');
        $builder->register('datainitialiser.service', '\App\Utils\DataInitialiser');
    }
}