<?php

class K2StoreVersion {

public static function getPreviousVersion() {

	jimport('joomla.filesystem.file');
	$target = JPATH_ADMINISTRATOR.'/components/com_k2store/pre-version.txt';
	if(JFile::exists($target)) {
		$rawData = JFile::read($target);
		$info = explode("\n", $rawData);
		$version = trim($info[0]);
	} else {
		//if no file is found then assume its latest
		$version = '3.1.0';
	}
	return $version;

}

/**
	 * Populates global constants holding the Akeeba version
	 */
	public static function load_version_defines()
	{
		if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.'/version.php'))
		{
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/version.php');
		}

		if(!defined('K2STORE_VERSION')) define("K2STORE_VERSION", "svn");
		if(!defined('K2STORE_PRO')) define('K2STORE_PRO', false);
		if(!defined('K2STORE_DATE')) {
			jimport('joomla.utilities.date');
			$date = new JDate();
			define( "K2STORE_DATE", $date->format('Y-m-d') );
		}
		if(!defined('K2STORE_ATTRIBUTES_MIGRATED')) define('K2STORE_ATTRIBUTES_MIGRATED', false);
	}

}