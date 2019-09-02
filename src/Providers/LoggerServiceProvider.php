<?php

/*
 * This file is part of the mouyong/puyingcloud-sdk.
 *
 * (c) 牟勇 <my24251325@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\Tongbushi\Providers;

use Hanson\Foundation\Log;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Cblink\Tongbushi\Tongbushi;

class LoggerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['logger'] = function (Tongbushi $pimple) {
            return Log::getLogger();
        };
    }
}
