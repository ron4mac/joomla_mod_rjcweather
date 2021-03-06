<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Cache\Cache;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;

class modRJCWeatherHelper
{
	// AJAX call made by COM_AJAX 
	public static function getAjax ()
	{
		$moduleName = basename(dirname(__FILE__));
		$moduleID = Factory::getApplication()->input->post->getString('modid');
		$module = JModuleHelper::getModuleById($moduleID);
		$params = new Registry($module->params);
		$weather = RJCWeatherHelper::getWeather($params);

		if (!$weather) {
			echo Text::_(RJCWeatherHelper::$error);
			return;
		}

		require JModuleHelper::getLayoutPath($moduleName, 'default');
	}

}


class RJCWeatherHelper
{

	public static $error;

	public static function getWeather ($params)
	{
		// get the selected weather data source (openweathermap or weatherbit)
		$source = $params->get('source', 'ow');

		if (!$params->get('apikey_'.$source)) {
			return false;
		}

		$options = [
			'lifetime' => $params->get('cache_time','15'),
			'storage' => 'file',
			'defaultgroup' => 'rjcweather',
			'caching' => true
		];

		$cache = Cache::getInstance('callback', $options);

		$weather = $cache->get('RJCWeatherHelper::'.$source.'LoadWeatherInformation', [$params]);

		// Delete cache if loading didn't work
		if ($weather === false) {
			$cache->clean();
		}

		return $weather;
	}


	public static function owLoadWeatherInformation ($params)
	{
		$apiurl = 'https://api.openweathermap.org/data/2.5/onecall?lat={LAT}&lon={LNG}&exclude=minutely,hourly&APPID={APIKEY}';

		// get the 2 character current Joomla language code
		$lang = substr(Factory::getLanguage()->get('tag'), 0, 2);

		$http = HttpFactory::getHttp();

		$apikey = $params->get('apikey_ow');

		if (empty($apikey)) {
			self::$error = 'MOD_RJCWEATHER_NOAPIKEY';
			return false;
		}

		$url = str_replace(['{APIKEY}','{LAT}','{LNG}'], [$apikey,$params->get('lat'),$params->get('lng')], $apiurl);

		switch ($params->get('unit')) {
			case 'imperial':
			case 'metric':
				$url .= '&units=' . $params->get('unit');
				break;
		}

		$result = $http->get($url);

		$content = new Registry($result->body);

		if ($result->code != 200) {
			self::$error = $content->get('message', 'MOD_RJCWEATHER_ERROR');
			return false;
		}

		$forecasts = $content->get('daily', []);

		if (empty($forecasts) || !is_array($forecasts)) {
			return false;
		}

		$current = $content->get('current', []);

		if (empty($current->weather) || !is_array($current->weather) || !isset($current->temp)) {
			return false;
		}

		return ['current'=>$current,'forecasts'=>$forecasts];
	}


	public static function wbLoadWeatherInformation ($params)
	{
		$apiurl_c = 'https://api.weatherbit.io/v2.0/current?lat={LAT}&lon={LNG}&units={UNIT}&key={APIKEY}';
		$apiurl_f = 'https://api.weatherbit.io/v2.0/forecast/daily?lat={LAT}&lon={LNG}&units={UNIT}&days={DAYS}&key={APIKEY}';

		$wb_units = ['standard'=>'S','imperial'=>'I','metric'=>'M'];

		// get the 2 character current Joomla language code
		$lang = substr(Factory::getLanguage()->get('tag'),0,2);

		$http = HttpFactory::getHttp();

		$apikey = $params->get('apikey_wb');

		if (empty($apikey)) {
			return false;
		}

		$unit = $wb_units[$params->get('unit', 'imperial')];

		$url = str_replace(['{APIKEY}','{LAT}','{LNG}','{UNIT}'], [$apikey,$params->get('lat'),$params->get('lng'),$unit], $apiurl_c);

		$result = $http->get($url);

		$content = new Registry($result->body);

		if ($result->code != 200) {
			self::$error = $content->get('error', 'MOD_RJCWEATHER_ERROR');
			return false;
		}

		$current = $content->get('data')[0];

		// make surise/sunset useable
		$current->sunrise = str_replace(':','',$current->sunrise);
		$current->sunset = str_replace(':','',$current->sunset);
		if ($current->sunset < $current->sunrise) $current->sunset += 2400;

		$url = str_replace(['{APIKEY}','{LAT}','{LNG}','{UNIT}','{DAYS}'], [$apikey,$params->get('lat'),$params->get('lng'),$unit,7], $apiurl_f);

		$result = $http->get($url);

		if ($result->code != 200) {
			return false;
		}

		$content = new Registry($result->body);
		$forecasts = $content->get('data');

		if (empty($forecasts) || !is_array($forecasts)) {
			return false;
		}

		return ['current'=>$current,'forecasts'=>$forecasts];
	}


	public static function temp ($value, $params)
	{
		$shortunit = 'KELVIN';
		switch ($params->get('unit')) {
			case 'imperial':
				$shortunit = 'FAHRENHEIT';
				break;
			case 'metric':
				$shortunit = 'CELCIUS';
				break;
		}
		return Text::sprintf('MOD_RJCWEATHER_PARAM_UNIT_' . $shortunit . '_SHORT', round($value));
	}


	public static function icon ($wcc, $params, $nt=false)
	{
		include_once 'iconincs/is03.php';

		return 'media/mod_rjcweather/icons/' . getIcon($wcc, $nt, $params);
	}


	public static function dow ($dt)
	{
		return date('l', $dt);
	}

}
