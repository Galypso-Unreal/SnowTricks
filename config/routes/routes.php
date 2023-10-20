<?php

use App\Controller\CommentController;
use App\Controller\TrickController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('trickform', '/trick/new')
        ->controller([TrickController::class, 'new'])
    ;
    $routes->add('getTricksPaged', '/trick/page/')
        ->controller([TrickController::class, 'getTricksPaged'])
    ;
    $routes->add('getCommentsPaged', '/comment/page')
        ->controller([CommentController::class, 'getCommentsPaged'])
    ;
    
};