<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use App\Event\MyCustomEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddEventAliasesPass([
            MyCustomEvent::class => 'my_custom_event',
        ]));
    }
}
