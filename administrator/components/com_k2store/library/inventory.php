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

//class to manage inventory

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2StoreInventory {


public static function validateStock($product_id) {

	return true;
}


public static	function isAllowed($item) {

		$params = JComponentHelper::getParams('com_k2store');

		//set the result object
		$result = new JObject();
		$result->backorder = false;
		//we always want to allow users to buy. so initialise to 1.
		$result->can_allow = 1;

		return $result;

	}
}