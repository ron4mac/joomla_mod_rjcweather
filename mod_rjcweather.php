<?php
defined('_JEXEC') or die;

JLoader::register('RJCWeatherHelper', __DIR__ . '/helper.php');

$moduleName = basename(dirname(__FILE__));
$moduleID = $module->id;
//$weather = RJCWeatherHelper::getWeather($params);

//if (!$weather) {
//	return;
//}

$layout = $params->get('layout', 'ajax');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath($moduleName, $layout);
