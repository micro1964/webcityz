<?php
/*
	JoomlaXTC Color Input Element

	version 1.7.0

	Copyright (C) 2009,2010  Monev Software LLC.	All Rights Reserved.

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
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.

	WARNING:

	Other required files of this extension might not be freely distributable and are
	noted as such.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

jimport('joomla.form.formfield');
JHtml::_('jquery.framework');

class JFormFieldColorinput extends JFormField {

	protected	$_name = 'Colorinput';

	protected function getInput()	{

		$live_site = JURI::base();
		$document = JFactory::getDocument();

		$dirname = basename(dirname(dirname(__FILE__)));

		$document->addStyleSheet($live_site."../modules/$dirname/elements/colorpicker.css");
		$document->addScript($live_site."../modules/$dirname/elements/colorpicker.js");

		$onfocus = "jQuery.noConflict(); jQuery(this).ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				hex=hex.toUpperCase();
				jQuery(el).val(hex);
				jQuery(el).ColorPickerHide();
				document.getElementById('".$this->id."COLORBOX').style.backgroundColor = '#'+hex;
			},
			onBeforeShow: function () {
				jQuery(this).ColorPickerSetColor(this.value);
			}
		})";

		$output = '<div class="input-append">
		<input id="'.$this->id.'" class="input-medium" name="'.$this->name.'" type="text" value="'.$this->value.'" onfocus="'.$onfocus.'"/>
		<span id="'.$this->id.'COLORBOX" class="add-on hasTip" title="Click on field to change color" style="background-color:#'.$this->value.'">&nbsp;&nbsp;&nbsp;</span>
		</div>';
	return $output;
	}
}
?>