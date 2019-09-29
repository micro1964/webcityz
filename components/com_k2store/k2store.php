<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//j3 compatibility
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

require_once (JPATH_COMPONENT.'/helpers/utilities.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/library/prices.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/helpers/strapper.php');
K2StoreStrapper::addJS();
K2StoreStrapper::addCSS();

jimport('joomla.filesystem.file');
jimport('joomla.html.parameter');
JLoader::register('K2StoreController', JPATH_COMPONENT.DS.'controllers'.DS.'controller.php');
JLoader::register('K2StoreModel',  JPATH_ADMINISTRATOR.'/components//com_k2store/models/model.php');
JLoader::register('K2StoreView',  JPATH_ADMINISTRATOR.'/components//com_k2store/views/view.php');

// Require specific controller if requested
$app = JFactory::getApplication();
$controller = $app->input->getWord('view', 'mycart');
$task = $app->input->getWord('task');

if (JFile::exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$classname = 'k2storeController'.$controller;
	$controller = new $classname();
	$controller->execute($task);
	$controller->redirect();
}
else {
	JError::raiseError(404, JText::_('K2STORE_NOT_FOUND'));
}