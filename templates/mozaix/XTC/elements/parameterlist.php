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

class JFormFieldParameterlist extends JFormField {

	protected	$type = 'Parameterlist';

	protected function getInput()	{

		if (!function_exists('xtcsortoptions')) {
				function xtcsortoptions($a, $b) {
			   return strnatcmp($a->text,$b->text);
			}
		}

		jimport( 'joomla.filesystem.folder' );

		$live_site = JURI::root();
		$template = basename(dirname(dirname(dirname(__FILE__))));
		
		$path = JPATH_ROOT.'/templates/'.$template;
		$files = JFolder::files($path.'/parameters','xml');

		$prefix = trim($this->element['group']);
		$options=array();
		foreach ($files as $file) {
			@list($filename,$extension)=explode('.',$file);
			if ($extension != 'xml') continue; // Not an XML
			if (strpos($filename,$prefix) !== 0) continue; // Not my robot
			$xmlFile = $path.'/parameters/'.$file;
			if (!is_readable($xmlFile)) { continue; }

			$xml = simplexml_load_file($xmlFile);
			$options[] = JHTML::_('select.option', $filename, $xml->name);
		}

		usort($options, "xtcsortoptions");

		return (empty($options)) ? JText::sprintf('No parameters of group "%s" found.', $prefix) : JHTML::_('select.genericlist',  $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}
