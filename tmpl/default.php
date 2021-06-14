<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

Factory::getDocument()->addStylesheet('media/mod_rjcweather/css/weather.css');

$owm = $params->get('source', 'ow') == 'ow';

$current = $weather['current'];
$now = $owm ? reset($current->weather) : $current->weather;
$forecasts = $weather['forecasts'];
$cwtime = $owm ? $current->dt : gmdate('Hi', $current->ts);
if (!$owm && $cwtime < 200) $cwtime += 2400;
$nighticon = $cwtime > $current->sunrise && $cwtime < $current->sunset ? false : true;
//echo $cwtime.' - '.$current->sunrise.' - '.$current->sunset;

//echo'<xmp>';var_dump($weather);echo'</xmp>';
?>
<div id="mod-rjcw_id<?php echo $moduleID; ?>" class="mod-rjcw">

	<div class="mod-rjcw_c">
<?php if (true): ?>
		<div class="mod-rjcw_cleft">
			<img class="mod-rjcw-icon_big" src="<?php echo RJCWeatherHelper::icon($owm ? $now->id : $now->code, $params, $nighticon); ?>"
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
			echo $owm ? $now->main : $now->description; ?></div>
			<?php } ?>

			<?php if ($params->get('humidity', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_HUMIDITY'); ?>: <?php echo $owm ? $current->humidity : $current->rh; ?>%</div>
			<?php } ?>

			<?php if ($params->get('wind', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_WIND'); ?>: <?php

			$compass = array('N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW', 'N');

			$windir = $compass[round(($owm ? $current->wind_deg : $current->wind_dir) / 22.5)];

			echo $windir . Text::_('MOD_RJCWEATHER_AT') . round($owm ? $current->wind_speed : $current->wind_spd) . ' MPH'; ?></div>
			<?php } ?>

			<?php if ($params->get('wind_chill', 1)==1) { ?>
			<div class="mod-rjcw-row"><?php echo Text::_('MOD_RJCWEATHER_FEELS'); ?>: <?php echo RJCWeatherHelper::temp($owm ? $current->feels_like : $current->app_temp, $params); ?></div>
			<?php } ?>

			<?php if ($owm && $params->get('todayhi', 1)==1) { ?>
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
		//ignore today's forecast
		unset($forecasts[0]);

		foreach ($forecasts as $i=>$fcast ) {

			if ($fcnt<$j) break;

			$wcc = $owm ? $fcast->weather[0]->id : $fcast->weather->code;
			$desc = $owm ? $fcast->weather[0]->description : $fcast->weather->description;
?>
			<div class="list_<?php echo ($i%2 ? 'even' : 'odd') ?>">
				<span class="mod-rjcw_list_day">
					<?php echo RJCWeatherHelper::dow($owm ? $fcast->dt : $fcast->ts); ?>
				</span>
				<span class="mod-rjcw_list_icon">
					<img class="mod-rjcw-icon" src="<?php echo RJCWeatherHelper::icon($wcc,$params); ?>"
					align="center"
					title="<?php echo $desc; ?>"
					alt="<?php echo $desc; ?>" />
					<?php if ($fcast->pop): ?>
					<span align="right"><?php echo $fcast->pop * ($owm ? 100 : 1) . '%'; ?></span>
					<?php else: ?>
					<?php endif; ?>
				</span>
				<span class="mod-rjcw_list_temp">
					<?php
					$htmp = $owm ? $fcast->temp->max : $fcast->high_temp;
					$ltmp = $owm ? $fcast->temp->min : $fcast->low_temp;
					echo RJCWeatherHelper::temp($htmp, $params) . '&nbsp;' . $params->get('separator', '/') . '&nbsp;' . RJCWeatherHelper::temp($ltmp, $params);
					?>
				</span>
				<div style="clear:both"></div>
			</div>
		<?php $j++;
		} ?>
	</div>
	<?php } ?>
	<div style="clear:both"></div>
<?php endif; ?>
</div>