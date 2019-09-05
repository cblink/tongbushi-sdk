<?php


namespace Cblink\Tongbushi\Providers;

use Pimple\Container;
use Cblink\Tongbushi\Client;
use Pimple\ServiceProviderInterface;

class TongbushiServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['tongbushi'] = function (Container $app) {
            return new Client($app);
        };
    }
}
