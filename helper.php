<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Cache\Cache;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;

class RJCWeatherHelper
{
	static protected $apiurl = 'https://api.openweathermap.org/data/2.5/onecall?lat={LAT}&lon={LNG}&exclude=minutely,hourly&APPID={APIKEY}';

//	https://api.weatherbit.io/v2.0/current?lat=35.7796&lon=-78.6382&units=I&key=API_KEY
//	https://api.weatherbit.io/v2.0/forecast/daily?lat=35.7796&lon=-78.6382&units=I&days=7&key=API_KEY

	static protected $iconpath = 'media/mod_rjcweather/icons/';


	static public function getWeather ($params)
	{
		// get the selected weather data source (openweathermap or weatherbit)
		$source = $params->get('source', 'ow');

		if (!$params->get('apikey_'.$source)) {
			return false;
		}

		$options = [
			'lifetime' => 15,
			'storage' => 'file',
			'defaultgroup' => 'rjcweather',
			'caching' => true
		];

	//	return self::loadWeatherInformation($params);				// @@@@@@@@@@@ UNCOMMENT TO RETURN WITHOUT CACHING

		$cache = Cache::getInstance('callback', $options);

		$weather = $cache->get('RJCWeatherHelper::'.$source.'LoadWeatherInformation', [$params]);

		// Delete cache if loading didn't work
		if ($weather === false) {
			$cache->clean();
		}

		return $weather;
	}


	static public function owLoadWeatherInformation ($params)
	{
		$apiurl = 'https://api.openweathermap.org/data/2.5/onecall?lat={LAT}&lon={LNG}&exclude=minutely,hourly&APPID={APIKEY}';

		// get the 2 character current Joomla language code
		$lang = substr(Factory::getLanguage()->get('tag'),0,2);

		$http = HttpFactory::getHttp();

		$apikey = $params->get('apikey_ow');

		if (empty($apikey)) {
			return false;
		}

		$url = str_replace(['{APIKEY}','{LAT}','{LNG}'], [$apikey,$params->get('lat'),$params->get('lng')], $apiurl);

		switch ($params->get('unit')) {
			case 'imperial':
			case 'metric':
				$url .= '&units=' . $params->get('unit');
				break;
		}

		$result = $http->get($url);					//file_put_contents('WEATHER.TXT',print_r($result,true));

		if ($result->code != 200) {
			return false;
		}

		$content = new Registry($result->body);		//file_put_contents('WEATHER.TXT',print_r($content,true),FILE_APPEND);

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


	static public function wbLoadWeatherInformation ($params)
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

		$result = $http->get($url);					//file_put_contents('WEATHER.TXT',print_r([$url,$result],true));

		if ($result->code != 200) {
			return false;
		}

		$content = new Registry($result->body);
		$current = $content->get('data')[0];		//file_put_contents('WEATHER.TXT',print_r($current,true),FILE_APPEND);

		// make surise/sunset useable
		$current->sunrise = str_replace(':','',$current->sunrise);
		$current->sunset = str_replace(':','',$current->sunset);
		if ($current->sunset < $current->sunrise) $current->sunset += 2400;

		$url = str_replace(['{APIKEY}','{LAT}','{LNG}','{UNIT}','{DAYS}'], [$apikey,$params->get('lat'),$params->get('lng'),$unit,7], $apiurl_f);

		$result = $http->get($url);					//file_put_contents('WEATHER.TXT',print_r([$url,$result],true));

		if ($result->code != 200) {
			return false;
		}

		$content = new Registry($result->body);
		$forecasts = $content->get('data');			//file_put_contents('WEATHER.TXT',print_r($forecasts,true),FILE_APPEND);

		if (empty($forecasts) || !is_array($forecasts)) {
			return false;
		}

//		if (empty($current->weather) || !is_array($current->weather) || !isset($current->temp)) {
//			return false;
//		}

		return ['current'=>$current,'forecasts'=>$forecasts];
	}


	static public function temp ($value, $params)
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


	static public function icon ($wcc, $params, $nt=false)
	{
		include_once 'iconincs/is03.php';

		return static::$iconpath . getIcon($wcc, $nt, $params);
	}


	static public function dow ($dt)
	{
		return date('l', $dt);
	}

}
