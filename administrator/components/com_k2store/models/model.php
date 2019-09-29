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

jimport('joomla.application.component.model');

if (version_compare(JVERSION, '3.0', 'ge'))
{
	class K2StoreModel extends JModelLegacy
	{
		public static function addIncludePath($path = '', $prefix = '')
		{
			return parent::addIncludePath($path, $prefix);
		}

	}

}
else 
{
	class K2StoreModel extends JModel
	{
		public static function addIncludePath($path = '', $prefix = '')
		{
			return parent::addIncludePath($path, $prefix);
		}

	}

}