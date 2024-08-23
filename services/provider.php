<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2024 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.0
*/
defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\Helper\HelperFactoryInterface;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\\RJCreations\\Module\\RjcWeather'));
        $container->registerServiceProvider(new HelperFactory('\\RJCreations\\Module\\RjcWeather\\Site\\Helper'));

        $container->registerServiceProvider(new Module());
    }
};
