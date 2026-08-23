<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2026 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.5
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

$wa = Factory::getDocument()->getWebAssetManager();

$wa->registerAndUseStyle('rjcw-style', 'mod_rjcweather/weather.css');
$wa->registerAndUseScript('rjcw-script', 'mod_rjcweather/weather.js');

$params = new Registry($module->params);

$moduleID = $module->id;
// no sense in refreshing the display until cache has expired
$checkDly = ($params->get('cache_time', 5)+1) * 60000;

$jsd = <<<JS
mod_rjcw({$moduleID});
setInterval(() => mod_rjcw({$moduleID}), {$checkDly});
JS;

// add javascript to document head .. declare as module so execution gets defered
$wa->addInlineScript($jsd, [], ['type' => 'module']);

?>
<div id="rjc_weather_id<?=$moduleID?>">
	<div class="sp-preloader">
		<div> </div>
	</div>
</div>
