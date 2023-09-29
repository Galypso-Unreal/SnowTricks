<?php

use App\Controller\TrickController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('trickform', '/trick/new')
        // the controller value has the format [controller_class, method_name]
        ->controller([TrickController::class, 'new'])

        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)
    ;
};