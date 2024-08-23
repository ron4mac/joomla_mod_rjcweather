<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2024 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.0
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

$jdoc = Factory::getDocument();

$jdoc->addStylesheet('media/mod_rjcweather/css/weather.css');

$moduleID = $module->id;

$js = <<<JS
window.addEventListener('load', () => {
	let fd = new FormData();
	fd.append('option','com_ajax');
	fd.append('module','rjcweather');
	fd.append('modid', {$moduleID});
	fd.append('format','raw');
	let wdiv = document.getElementById('rjc_weather_id{$moduleID}');
	fetch('', {method:'POST', body: fd})
	.then(resp => { if (!resp.ok) throw new Error(`Network response was not OK (\${resp.status})`); return resp.text(); })
	.then(htm => wdiv.innerHTML = htm)
	.catch(err => wdiv.innerHTML = err);
	return false;
});
JS;

// add javascript to document head
$jdoc->addScriptDeclaration($js);

?>
<div id="rjc_weather_id<?php echo $moduleID; ?>">
	<div class="sp-preloader">
		<div> </div>
	</div>
</div>
