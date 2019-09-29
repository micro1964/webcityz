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

class TableOrderdetails extends JTable
{
	
	/** @var int Primary key */
	var $id = null;
	
	/** @var int */
	var $user_id = null;
	
	/** @var datetime */
	var $order_date = null;
		
	/** @var datetime */
	var $order_id = null;
	
	/** @var int */
	var $itemid	= null;
	
	/** @var string */
	var $itemname 	= null;
	
	/** @var float */
	var $itemprice 	= null;
	
	/** @var int */
	var $quantity	= null;
	
	/** @var float */
	var $total 	= null;
	
	/** @var float */
	var $tax_total 	= null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct('#__k2store_orderdetails', 'id', $db );
	}
	
	
}
?>
