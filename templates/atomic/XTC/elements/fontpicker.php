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

class JFormFieldFontpicker extends JFormField {

	protected	$type = 'Component';

	protected function getInput()	{

defined( '_JEXEC' ) or die;
JHtml::_('jquery.framework');

class JElementFontPicker extends JElement {

	function fetchElement($name, $value, &$node, $control_name) {
		$live_site = JURI::root();
		$template = basename(dirname(dirname(dirname(__FILE__))));
		
		$document = JFactory::getDocument();
		$document->addStyleSheet($live_site."templates/$template/XTC/XTC.css",'text/css');
		$document->addStyleSheet($live_site."templates/$template/XTC/elements/fontpicker.css");
		$document->addScript($live_site."templates/$template/XTC/elements/fontpicker.js");

		$script = "jQuery.noConflict();jQuery(this).FontPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				hex=hex.toUpperCase();
				jQuery(document.getElementById('".$control_name.$name."')).val('#'+hex);
				jQuery(el).FontPickerHide();
				el.style.backgroundColor = '#'+hex
			},
			onBeforeShow: function () {
				jQuery(this).FontPickerSetColor(document.getElementById('".$control_name.$name."').value);
			},
			mainID: '".$control_name.$name."'
		})";
		$output  = '<input id="'.$control_name.$name.'" name="'.$control_name.'['.$name.']" type="text" size="14" maxlength="11" value="'.$value.'" />';
		$output .= '<br/><span id="'.$control_name.$name.'DEMO" >Demo text</span>';
		$output .= '&nbsp;&nbsp;';
		$output .= '<span id="'.$control_name.$name.'COLORBOX" style="display:inline-block;margin:0;padding:0;cursor:pointer;border:1px solid silver;background-color:'.$value.'" onmousedown="'.$script.'">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
	return $output;
	}
}
?>