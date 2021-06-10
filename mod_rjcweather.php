<?php
defined('_JEXEC') or die;

JLoader::register('RJCWeatherHelper', __DIR__ . '/helper.php');

$moduleID = $module->id;
$weather = RJCWeatherHelper::getWeather($params);

if (!$weather) {
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_rjcweather', $params->get('layout', 'default'));
