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

class JFormFieldLayoutlist extends JFormField {

	protected	$type = 'Layoutlist';

	protected function getInput()	{

		jimport( 'joomla.filesystem.folder' );

		$live_site = JURI::root();
		$template = basename(dirname(dirname(dirname(__FILE__))));
		
		$document = JFactory::getDocument();
		$document->addStyleSheet($live_site."templates/$template/XTC/XTC.css",'text/css');

		if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_jxtc')) {
			JFactory::getApplication('administrator')->redirect('index.php', JText::_('XTC templates require the "XTC Framework helper" component. Please install it before using the template.'));
		}
		$path = JPATH_ROOT.'/templates/'.$template.'/layouts';
		$folders = JFolder::folders($path);
		sort($folders);
		$options=array();
		foreach ($folders as $folder) {
			$xmlFile = $path.'/'.$folder.'/config.xml';
			if (!is_readable($xmlFile)) { continue; }
			$xml = simplexml_load_file($xmlFile);
			$name = isset($xml->name) ? $xml->name : $folder;
			$description = isset($xml->description) ? trim($xml->description) : '';
			$options[] = JHTML::_('select.option', $folder, $name);
		}
		return empty($options) ? JText::_('No layouts found.') : JHTML::_('select.genericlist',  $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}