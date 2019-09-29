<?php
/**
 * @author ElasticEmail
 * @date: 2019-04-10
 *
 * @copyright  Copyright (C) 2010-2019 elasticemail.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

if (!defined('EESENDERLIBRARIES'))
{
	define('EESENDERLIBRARIES', '0.9.0');

	// Register the CMandrill autoloader
	require_once __DIR__ . '/EEsenderAutoload.php';
	EEsenderAutoloader::init();
}
