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
defined('_JEXEC') or die('Restricted access');

class JElementHeader extends JElement {
	var	$_name = 'header';

	function fetchElement($name, $value, &$node, $control_name){
		// Output
		return '
		<div style="font-weight:normal;font-size:12px;color:#fff;padding:4px;margin:0;background:#0B55C4;">
			'.JText::_($value).'
		</div>
		';
	}

	function fetchTooltip($label, $description, &$node, $control_name, $name){
		// Output
		return '&nbsp;';
	}

}
