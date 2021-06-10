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
//	static protected $apiurl = 'https://api.openweathermap.org/data/2.5/onecall?id={CITYID}&APPID={APIKEY}';
	static protected $apiurl = 'https://api.openweathermap.org/data/2.5/onecall?lat={LAT}&lon={LNG}&exclude=minutely,hourly&APPID={APIKEY}';

//                            [lat] => 27.2939
//                            [lon] => -80.3503

	static protected $iconpath = 'media/mod_rjcweather/icons/';


	static public function getWeather ($params)
	{
		if (!$params->get('apikey')) {
			return false;
		}

		$options = [
			'lifetime' => 15,
			'storage' => 'file',
			'defaultgroup' => 'rjcweather',
			'caching' => true
		];

	//	return self::loadWeatherInformation($params);				// @@@@@@@@@@@ RETURN WITHOUT CACHING

		$cache = Cache::getInstance('callback', $options);

		$weather = $cache->get('RJCWeatherHelper::loadWeatherInformation', [$params]);

		// Delete cache if loading didn't work
		if ($weather === false) {
			$cache->clean();
		}

		return $weather;
	}


	static public function loadWeatherInformation ($params)
	{
		$http = HttpFactory::getHttp();

		$city_id = $params->get('city_id');
		$apikey = $params->get('apikey');

		if (empty($city_id) || empty($apikey)) {
			return false;
		}

		$url = str_replace(['{CITYID}','{APIKEY}','{LAT}','{LNG}'], [$city_id,$apikey,$params->get('lat'),$params->get('lng')], static::$apiurl);

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
