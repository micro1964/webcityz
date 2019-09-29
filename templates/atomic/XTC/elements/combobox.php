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
JHtml::_('jquery.framework');

class JFormFieldCombobox extends JFormField {

	protected	$type = 'Combobox';

	protected function getInput()	{

		$template = basename(dirname(dirname(dirname(__FILE__))));

		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true)."/templates/$template/XTC/elements/jquery.simpleCombo.js");
		$document->addScriptDeclaration("jQuery(document).load(function () {
			jQuery(function() {
				jQuery('select.combo').simpleCombo();
			});
		});");

		$options = array(); $custom = true;
		foreach ($this->element->children() as $option) {
			if ($option->getName() != 'option') { continue; }

			$tmp = JHtml::_('select.option', (string) $option['value'], JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text');
			if ($option['value'] == $this->value) $custom = false;
			$tmp->class = (string) $option['class'];
			$tmp->onclick = (string) $option['onclick'];
			$options[] = $tmp;
		}

		if ($custom)	{ $options[] = JHtml::_('select.option', $this->value, $this->value, 'value', 'text'); }

		$html = JHtml::_('select.genericlist', $options, $this->name, 'class="combo inputbox"', 'value', 'text', $this->value, $this->id);

		return $html;
	}
}
