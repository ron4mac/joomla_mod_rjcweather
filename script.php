<?php
/**
* @package		mod_rjcweather
* @copyright	Copyright (C) 2015-2024 RJCreations. All rights reserved.
* @license		GNU General Public License version 3 or later; see LICENSE.txt
* @since		1.2.0
*/
defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerScript;

class mod_rjcweatherInstallerScript extends InstallerScript
{
	protected $minimumJoomla = '4.0';
	protected $deleteFiles = [
		'/modules/mod_rjcweather/mod_rjcweather.php',
		'/modules/mod_rjcweather/helper.php'];

	public function install ($parent) 
	{
	}

	public function uninstall ($parent) 
	{
	}

	public function update ($parent) 
	{
	}

	public function preflight ($type, $parent) 
	{
	}

	public function postflight ($type, $parent) 
	{
		if ($type === 'update') {
			$this->removeFiles();
		}
		return true;
	}

}
