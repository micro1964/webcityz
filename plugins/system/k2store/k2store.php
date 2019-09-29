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

jimport( 'joomla.plugin.plugin' );
jimport('joomla.html.parameter');

class plgSystemK2Store extends JPlugin {

	function plgSystemK2Store( &$subject, $config ){
		parent::__construct( $subject, $config );
		//load default language
		$lang = JFactory::getLanguage();
		$lang->load('com_k2store', JPATH_SITE);
	}

	function onAfterRoute() {

		$mainframe = JFactory::getApplication();
		$document =JFactory::getDocument();
		require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/popup.php');
		require_once (JPATH_SITE.'/components/com_k2store/helpers/modules.php');
		//JHtml::_('behavior.framework');
		JHtml::_('behavior.modal');
		$baseURL = JURI::root();
		$script = "
		if(typeof(k2storeURL) == 'undefined') {
		var k2storeURL = '{$baseURL}';
		}
		";
		$document->addScriptDeclaration($script);

	} //end of function
}
