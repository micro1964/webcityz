<?php
/*

XTC Template Framework 3.3.0

Copyright (c) 2010-2014 Monev Software LLC,  All Rights Reserved

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA

See COPYRIGHT.txt for more information.
See LICENSE.txt for more information.

www.joomlaxtc.com

*/

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldComponent extends JFormField {

	protected	$type = 'Component';

	protected function getInput()	{

		$live_site = JURI::root();
		$db	= JFactory::getDBO();

		$q = "SELECT distinct extension_id as id, element as name FROM #__extensions WHERE type='component' AND enabled=1 ORDER BY element";
		$db->setquery($q);
		$result=$db->loadObjectList();
		array_unshift($result,(object)array('id'=>'none','name'=>'NO COMPONENTS'));
		array_unshift($result,(object)array('id'=>'all','name'=>'ALL COMPONENTS'));
		$size = count($result);
		$size = ceil($size/10);
		if ($size < 10) $size = 10;
		if ($size > 20) $size = 20;
		return JHTML::_('select.genericlist',  $result, $this->name.'[]',' multiple="multiple" size="'.$size.'" class="inputbox"', 'id', 'name', explode('|',$this->value), $this->id);
	}
}