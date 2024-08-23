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
		2 => 'tstorms',
		210 => 'chancetstorms',
		3 => 'chancerain',
		5 => 'rain',
		511 => 'sleet',
		6 => 'snow',
		600 => 'chanceflurries',
		602 => 'flurries',
		61 => 'sleet',
		612 => 'chancesleet',
		615 => 'chancesleet',
		620 => 'chancesleet',
		62 => 'sleet',
		7 => 'fog',
		800 => 'clear',
		801 => 'mostlysunny',
		802 => 'mostlycloudy',
		803 => 'cloudy',
		804 => 'cloudy'
	];

	$icon = 'unknown.svg';

	if (isset($c2icons[$cnd])) {
		$icon = $c2icons[$cnd] . '.svg';
	} elseif (isset($c2icons[(int) ($cnd / 10)])) {
		$icon = $c2icons[(int) ($cnd / 10)] . '.svg';
	} elseif (isset($c2icons[(int) ($cnd / 100)])) {
		$icon = $c2icons[(int) ($cnd / 100)] . '.svg';
	}

	$pfx = $nt ? 'nt_' : '';
	return 'dist/icons/'.$params->get('color', 'solid-black').'/svg/' . $pfx . $icon;
}
