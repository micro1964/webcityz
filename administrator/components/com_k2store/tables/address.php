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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
JLoader::register( 'K2StoreTable', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables'.DS.'_base.php' );

class TableAddress extends K2StoreTable
{

	/**
	 * @param database A database connector object
	 */

	function __construct(&$db)
	{

		parent::__construct('#__k2store_address', 'id', $db );
	}


	/**
	 * Checks the entry to maintain DB integrity
	 * @return unknown_type
	 */
	function check()
	{

	}

}