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
JHTML::_('behavior.tooltip');
jimport('joomla.application.component.controller');
$app = JFactory::getApplication();

//j3 compatibility
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
JLoader::register('K2StoreController', JPATH_COMPONENT.'/controllers/controller.php');
JLoader::register('K2StoreView', JPATH_COMPONENT.'/views/view.php');
JLoader::register('K2StoreModel', JPATH_COMPONENT.'/models/model.php');
require_once (JPATH_SITE.'/components/com_k2store/helpers/utilities.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/helpers/strapper.php');
require_once (JPATH_COMPONENT.'/helpers/toolbar.php');
require_once (JPATH_COMPONENT.'/helpers/version.php');
$version = new K2StoreVersion();
$version->load_version_defines();
K2StoreStrapper::addJS();
K2StoreStrapper::addCSS();

//handle live update
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'liveupdate'.DS.'liveupdate.php';
if($app->input->getCmd('view','') == 'liveupdate') {
	LiveUpdate::handleRequest();
	return;
}

$controller = $app->input->getWord('view', 'cpanel');
if (JFile::exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php')
		&& $controller !='countries' && $controller !='zones'
		&& $controller !='country' && $controller !='zone'
		&& $controller !='taxprofiles' && $controller !='taxprofile'
		&& $controller !='taxrates' && $controller !='taxrate'
		&& $controller !='geozones' && $controller !='geozone'
		&& $controller !='geozonerules' && $controller !='geozonerule'
		&& $controller !='storeprofiles' && $controller !='storeprofile'
)

{
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$classname = 'K2StoreController'.$controller;
	$controller = new $classname();

} else {
	$controller = JControllerLegacy::getInstance('K2Store');
}
$controller->execute($app->input->getWord('task'));
$controller->redirect();