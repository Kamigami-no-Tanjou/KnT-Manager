<?php

use App\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

//Service container initialisation:
$builder = new ContainerBuilder();
\App\Utils\ServiceRegister::registerServices($builder);

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
