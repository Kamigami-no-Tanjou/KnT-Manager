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

        // Adding repository level services (DAO feeders)
        $builder->register('calendar.service', '\App\Repository\CalendarService');
        $builder->register('charac.service', '\App\Repository\CharacService');
        $builder->register('characevent.service', '\App\Repository\CharacEventService');
        $builder->register('characrelationship.service', '\App\Repository\CharacRelationshipService');
        $builder->register('class.service', '\App\Repository\ClassService');
        $builder->register('elem.service', '\App\Repository\ElemService');
        $builder->register('magic.service', '\App\Repository\MagicService');
        $builder->register('map.service', '\App\Repository\MapService');
        $builder->register('nation.service', '\App\Repository\NationService');
        $builder->register('nationleader.service', '\App\Repository\NationLeaderService');
    }
}