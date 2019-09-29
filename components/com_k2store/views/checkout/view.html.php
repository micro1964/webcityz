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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the K2Store component
 *
 * @static
 * @package		Joomla
 * @subpackage	K2Store
 * @since 1.0
 */
class K2StoreViewCheckout extends K2StoreView
{
	 function display($tpl = null) {

		$params = JComponentHelper::getParams('com_k2store');
		$this->assignRef('params',$params);

		JHTML::_('behavior.formvalidation');

		parent::display($tpl);
	}



}
