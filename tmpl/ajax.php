<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

$jdoc = Factory::getDocument();

$jdoc->addStylesheet('media/mod_rjcweather/css/weather.css');

$js = <<<JS
(function ($) {
	$(document).ready(function () {
		var request = {
			'option': 'com_ajax',
			'module': 'rjcweather',
			'modid': {$moduleID},
			'format': 'raw'
		};
		$.ajax({
			type: 'POST',
			data: request,
			success: function (resp) {
				$('#rjc_weather_id{$moduleID}').html(resp);
			}
		});
		return false;
	});
})(jQuery);
JS;

// add javascript to document head
$jdoc->addScriptDeclaration($js);

?>
<div id="rjc_weather_id<?php echo $moduleID; ?>">
	<div class="sp-preloader">
		<div> </div>
	</div>
</div>
