<?php

use App\Controller\TrickController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('trickform', '/trick/new')
        ->controller([TrickController::class, 'new'])
    ;
    $routes->add('getTricksPaged', '/go')
        ->controller([TrickController::class, 'getTricksPaged'])
    ;
    // $routes->add('getCommentsPaged', '/go')
    //     ->controller([TrickController::class, 'getTricksPaged'])
    // ;
    
};