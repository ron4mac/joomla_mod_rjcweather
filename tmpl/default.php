<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

Factory::getDocument()->addStylesheet('media/mod_rjcweather/css/weather.css');

$current = $weather['current'];
$now = reset($current->weather);
$forecasts = $weather['forecasts'];
$nighticon = $current->dt > $current->sunrise && $current->dt < $current->sunset ? false : true;

//$location = (trim($params->get('locationTranslated'))=='') ? $params->get('location') : $params->get('locationTranslated');
//$forecast = $data['forecasts'];
//echo'<xmp>';var_dump($weather);echo'</xmp>';
?>
<div id="mod-rjcw_id<?php echo $moduleID; ?>" class="mod-rjcw">

	<div class="mod-rjcw_c">
<?php if (true): ?>
		<div class="mod-rjcw_cleft">
			<img class="mod-rjcw-icon_big" src="<?php echo RJCWeatherHelper::icon($now->id, $params, $nighticon); ?>"
			height="96px"
			title="<?php echo $now->description;?>"
			alt="<?php echo $now->description; ?>" />

			<br style="clear:both" />
			<p class="mod-rjcw-current_temp">
				<?php echo RJCWeatherHelper::temp($current->temp, $params); ?>
			</p>
		</div>
<?php endif; ?>
<?php if (true): ?>
		<div class="mod-rjcw_cright">
			<?php if ($params->get('city')==1) { ?>
			<p class="mod-rjcw_city"><?php echo $location ?></p>
			<?php } ?>

			<?php if ($params->get('condition', 1)==1) { ?>
			<div class="mod-rjcw-row_head"><?php
			echo $now->main; ?></div>
			<?php } ?>

			<?php if ($params->get('humidity', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_HUMIDITY'); ?>: <?php echo $current->humidity; ?>%</div>
			<?php } ?>

			<?php if ($params->get('wind', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_WIND'); ?>: <?php

			$compass = array('N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW', 'N');

			$windir = $compass[round($current->wind_deg / 22.5)];

			echo $windir . Text::_('MOD_RJCWEATHER_AT') . round($current->wind_speed) . ' MPH'; ?></div>
			<?php } ?>

			<?php if ($params->get('wind_chill', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_FEELS'); ?>: <?php echo RJCWeatherHelper::temp($current->feels_like, $params); ?></div>
			<?php } ?>

			<?php if ($params->get('todayhi', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_HITODAY'); ?>: <?php echo RJCWeatherHelper::temp($forecasts[0]->temp->max, $params); ?></div>
			<?php } ?>
		</div>
		<div style="clear:both"></div>
<?php endif; ?>
	</div>

<?php if (true): ?>
	<div style="clear:both"></div>
	<?php if ($params->get('forecast') != 'disabled') { ?>
	<div class="mod-rjcw_forecasts">
		<?php
		$fcnt = (int) $params->get('forecast', 7);
		$j = 1;
		unset($forecasts[0]);

		foreach ($forecasts as $i=>$fcast ) {

			if ($fcnt<$j) break;

			$wcc = $fcast->weather[0]->id;
			$desc = $fcast->weather[0]->description;

			if ($params->get('tmpl_layout', 'list')=='list') { ?>
			<div class="list_<?php echo ($i%2 ? 'even' : 'odd') ?>">
				<span class="mod-rjcw_list_day">
					<?php echo RJCWeatherHelper::dow($fcast->dt); ?>
				</span>
				<span class="mod-rjcw_list_icon">
					<img class="mod-rjcw-icon" src="<?php echo RJCWeatherHelper::icon($wcc,$params); ?>"
					align="center"
					title="<?php echo $desc; ?>"
					alt="<?php echo $desc; ?>" />
					<?php if ($fcast->pop): ?>
					<span align="right"><?php echo $fcast->pop * 100 . '%'; ?></span>
					<?php else: ?>
					<?php endif; ?>
				</span>
				<span class="mod-rjcw_list_temp">
					<?php echo RJCWeatherHelper::temp($fcast->temp->max, $params) . '&nbsp;' . $params->get('separator', '/') . '&nbsp;' . RJCWeatherHelper::temp($fcast->temp->min, $params); ?>
				</span>
				<div style="clear:both"></div>
			</div>
			<?php } else { ?>
			<div class="block_<?php echo ($i%2 ? 'even' : 'odd') ?>" style="float:left;width:<?php echo round(100/$fcnt) ?>%">
				<span class="mod-rjcw_day">
					<?php echo RJCWeatherHelper::dow($fcast->dt); ?>
				</span>
				<br style="clear:both" />
				<span class="mod-rjcw_icon">
					<img class="mod-rjcw-icon" src="<?php echo RJCWeatherHelper::icon($wcc,$params); ?>"
					title="<?php echo 'TEXT'; ?>"
					alt="<?php echo 'TEXT'; ?>" />
				</span>
				<br style="clear:both" />
				<span class="mod-rjcw_temp">
					<?php echo RJCWeatherHelper::temp($fcast->temp->max, $params) . '&nbsp;' . $params->get('separator', '/') . '&nbsp;' . RJCWeatherHelper::dow($fcast->temp->min, $params); ?>
				</span>
			<br style="clear:both" />
		</div>
		<?php } ?>
		<?php

		$j++;
		} ?>
	</div>
	<?php } ?>
	<div style="clear:both"></div>
<?php endif; ?>
</div>