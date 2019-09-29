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

defined('JPATH_BASE') or die;

JHTML::_('behavior.modal');

class JFormFieldTemplateimage extends JFormField {

	protected $type = 'Templateimage';

	protected function getInput() {

		$template = basename(dirname(dirname(dirname(__FILE__))));
		$directory = basename($this->element['directory']);

		$viewonclick = "if (document.getElementById('".$this->id."').value) { SqueezeBox.open('".Juri::root().'templates/'.$template.'/images/'.$directory."/'+document.getElementById('".$this->id."').value); };";
		$clearonclick = "document.getElementById('".$this->id."').value='';";

		$html = '<div class="input-prepend input-append">
			<a class="btn" title="View image" href="javascript:void(0);" onclick="'.$viewonclick.'" >
				<i class="icon-eye"> </i>
			</a>
			<input type="text" class="input-small" name="'.$this->name.'" id="'.$this->id.'" value="'.$this->value.'" />
			&nbsp;
			<a class="modal btn" title="Select an image"
				 href="index.php?option=com_jxtc&view=files&tmpl=component&fld='.$this->id.'&id='.JRequest::getInt('id').'&f='.$directory.'" 
				 rel="{handler: \'iframe\', size: {x: 755, y: 495}}">'.
				 JText::_('JSELECT').'
		  </a>';
		if (!$this->element['hide_none']) {
		 	$html .= '<a class="btn btn-danger" title="'.JText::_('JLIB_FORM_BUTTON_CLEAR').'" href="javascript:void(0);" onclick="'.$clearonclick.'">
				<i class="icon-remove icon-white"></i>
			</a>';
		}
		$html .= '</div>';
		
		return $html;
	}
}

