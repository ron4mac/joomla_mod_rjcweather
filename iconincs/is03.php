<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2024 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.0
*/

function getIcon ($cnd=900, $nt=true, $params=null)
{
	$c2icons = [
		200 => ['Thunderstorm with light rain',		't01d', 't01n'],
		201 => ['Thunderstorm with rain',			't02d', 't02n'],
		202 => ['Thunderstorm with heavy rain',		't03d', 't03n'],
		230 => ['Thunderstorm with light drizzle',	't04d', 't04n'],
		231 => ['Thunderstorm with drizzle',		't04d', 't04n'],
		232 => ['Thunderstorm with heavy drizzle',	't04d', 't04n'],
		233 => ['Thunderstorm with Hail',			't05d', 't05n'],
		300 => ['Light Drizzle',					'd01d', 'd01n'],
		301 => ['Drizzle',							'd02d', 'd02n'],
		302 => ['Heavy Drizzle',					'd03d', 'd03n'],
		500 => ['Light Rain',						'r01d', 'r01n'],
		501 => ['Moderate Rain',					'r02d', 'r02n'],
		502 => ['Heavy Rain',						'r03d', 'r03n'],
		511 => ['Freezing rain',					'f01d', 'f01n'],
		520 => ['Light shower rain',				'r04d', 'r04n'],
		521 => ['Shower rain',						'r05d', 'r05n'],
		522 => ['Heavy shower rain',				'r06d', 'r06n'],
		600 => ['Light snow',						's01d', 's01n'],
		601 => ['Snow',								's02d', 's02n'],
		602 => ['Heavy Snow',						's03d', 's03n'],
		610 => ['Mix snow/rain',					's04d', 's04n'],
		611 => ['Sleet',							's05d', 's05n'],
		612 => ['Heavy sleet',						's05d', 's05n'],
		621 => ['Snow shower',						's01d', 's01n'],
		622 => ['Heavy snow shower',				's02d', 's02n'],
		623 => ['Flurries',							's06d', 's06n'],
		700 => ['Mist',								'a01d', 'a01n'],
		711 => ['Smoke',							'a02d', 'a02n'],
		721 => ['Haze',								'a03d', 'a03n'],
		731 => ['Sand/dust',						'a04d', 'a04n'],
		741 => ['Fog',								'a05d', 'a05n'],
		751 => ['Freezing Fog',						'a06d', 'a06n'],
		800 => ['Clear sky',						'c01d', 'c01n'],
		801 => ['Few clouds',						'c02d', 'c02n'],
		802 => ['Scattered clouds',					'c02d', 'c02n'],
		803 => ['Broken clouds',					'c03d', 'c03n'],
		804 => ['Overcast clouds',					'c04d', 'c04n'],
		900 => ['Unknown Precipitation',			'u00d', 'u00n']
	];

	if (!isset($c2icons[$cnd])) $cnd = 900;
	return 'icons/'.$c2icons[$cnd][$nt?2:1].'.png';
}
