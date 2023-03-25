<?php

use Ecotone\Lite\EcotoneLiteApplication;

require __DIR__ . '/vendor/autoload.php';

$ecotoneLite = EcotoneLiteApplication::bootstrap();

$commandBus = $ecotoneLite->getCommandBus();
$queryBus = $ecotoneLite->getQueryBus();

