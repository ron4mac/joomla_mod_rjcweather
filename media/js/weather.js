/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2026 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.5
*/
var mod_rjcw = function (mID) {
	let fd = new FormData();
	fd.append('option','com_ajax');
	fd.append('module','rjcweather');
	fd.append('modid', mID);
	fd.append('format','raw');
	let wdiv = document.getElementById('rjc_weather_id'+mID);
	fetch('', {method:'POST', body: fd})
	.then(resp => { if (!resp.ok) throw new Error(`Network response was not OK (${resp.status})`); return resp.text(); })
	.then(htm => wdiv.innerHTML = htm)
	.catch(err => wdiv.innerHTML = err);
};