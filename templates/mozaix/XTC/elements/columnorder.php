<?php
/*

XTC Template Framework 3.4.2

Copyright (c) 2010-2018 Monev Software LLC,  All Rights Reserved

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

class JFormFieldColumnorder extends JFormField {

	protected	$type = 'Columnorder';

	protected function getInput()	{

		if (!function_exists('recursive_permutations')) {
			function recursive_permutations($items,$perms = array(), &$list=array() )	{
				if (empty($items)) {
					$list[] = join(',', $perms);      
				}
				else {
					for ($i = count($items)-1;$i>=0;--$i) {
						$newitems = $items;
						$newperms = $perms;
						list($foo) = array_splice($newitems, $i, 1);
						array_unshift($newperms, $foo);
						recursive_permutations($newitems, $newperms, $list);
					};
					return $list;
				};
			}
		}

		$columns = explode(',',$this->element['columns']);
		array_walk($columns,'trim');
		$perms = recursive_permutations($columns);
		usort($perms,'strnatcmp');

		$options=array();
	
		foreach ($perms as $rec) {
			$display = implode(' ',explode(',',$rec));
			$options[] = JHTML::_('select.option', $rec, $display);
		}
		$perms=array();

		return JHTML::_('select.genericlist',  $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}
