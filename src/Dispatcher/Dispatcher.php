<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2024 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.0
*/
namespace RJCreations\Module\RjcWeather\Site\Dispatcher;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

/* this is apparently not needed ... left here for reference
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();
        $data['list'] = $this->getHelperFactory()->getHelper('RjcweatherHelper')->getWeather($data['params'], $this->getApplication());
        return $data;
    }
*/

}
