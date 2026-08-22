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

$wa->registerAndUseStyle('rjcw-style', 'media/mod_rjcweather/css/weather.css');
$wa->registerAndUseScript('rjcw-script', 'media/mod_rjcweather/js/weather.js');

$params = new Registry($module->params);

$moduleID = $module->id;
$checkDly = ($params->get('cache_time', 5)+1) * 60000;

$js = <<<JS
window.addEventListener('load', () => {
	mod_rjcw({$moduleID});
	const timerId = setInterval(() => {mod_rjcw({$moduleID});}, {$checkDly});
	return false;
});
JS;

// add javascript to document head
$wa->addInlineScript($js);

?>
<div id="rjc_weather_id<?=$moduleID?>">
	<div class="sp-preloader">
		<div> </div>
	</div>
</div>
