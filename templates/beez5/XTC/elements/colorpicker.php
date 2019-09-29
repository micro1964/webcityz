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

class JFormFieldColorpicker extends JFormField {

	protected	$type = 'Colorpicker';

	protected function getInput()	{

		$template = basename(dirname(dirname(dirname(__FILE__))));
		
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true)."/templates/$template/XTC/elements/colorpicker.css");
		$document->addScript(JURI::root(true)."/templates/$template/XTC/elements/colorpicker.js");

		$onfocus = "jQuery.noConflict(); jQuery(this).ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				hex='#'+hex.toUpperCase();
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
				document.getElementById('".$this->id."COLORBOX').style.backgroundColor = hex;
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
			}
		})";

		$output = '<div class="input-append">
		<input id="'.$this->id.'" class="input-medium" name="'.$this->name.'" type="text" size="10" value="'.$this->value.'" onfocus="'.$onfocus.'"/>
		<span id="'.$this->id.'COLORBOX" class="add-on hasTip" title="Click on field to change color" style="background-color:'.$this->value.'">&nbsp;&nbsp;&nbsp;</span>
		</div>';

		return $output;

	}
}
?>