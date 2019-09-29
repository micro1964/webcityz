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

class JFormFieldXtcparameters extends JFormField {

	protected	$type = 'Xtcparameters';

	protected function getInput()	{

		jimport( 'joomla.html.parameter' );
		jimport('joomla.html.pane');

		$id = JRequest::getInt('id');
		$live_site = JURI::root();
		$template = basename(dirname(dirname(dirname(__FILE__))));
		$path = JPATH_ROOT.'/templates/'.$template;
		
		$document = JFactory::getDocument();
		$document->addStyleSheet($live_site."templates/$template/XTC/XTC.css",'text/css');

		$xmlFile = JPATH_ROOT.'/templates/'.$template.'/XTC/XTC_config.xml';

		if (!is_readable($xmlFile)) {
			return "No parameters found.";
		}
		else {
			if (is_readable($xmlFile)) {
				$xml = simplexml_load_file($xmlFile);
				$name = isset($xml->name) ? $xml->name : '';
				$description = isset($xml->description) ? trim($xml->description) : '';
			}
			else {
				$name = '';
				$description = '';
			}
		}
		$form = new JForm('jxtc');
		$form->addFieldPath(JPATH_ROOT.'/templates/'.$template.'/XTC/elements');
		$form->loadFile($xmlFile);
		$getFieldsets = $form->getFieldsets();
		
		require_once $path.'/XTC/XTC_library.php';
		$templateParameters = xtcLoadParams($id);

		$html = '';
		$html .= '<div class="xtc row-fluid">';
		$html .= '<div class="span5">';

		foreach ($getFieldsets as $fieldsets => $fieldset) {
			$label = trim($fieldset->label);
			if (empty($label)) $label = trim($fieldset->name);
			$fieldsetDescription = isset($fieldset->description) ? trim($fieldset->description) : '';
			if (count($getFieldsets) > 1) { // Split in xtc fieldsets
				$html .= '<fieldset>';
				if ($label) { $html .= '<legend>'.$label.'</legend>'; }
				if ($fieldsetDescription) { $html .= '<div>'.$fieldsetDescription.'</div>'; }
			}

			foreach($form->getFieldset($fieldset->name) as $field) {
				$xtcName = substr($field->name,7,-1);
				if (isset($templateParameters->$xtcName)) { $field->value = $templateParameters->$xtcName; }
				$field->name = str_replace('params[','jform[params][',$field->name);
				$html .= '<div class="control-group">';
				$html .= '<div class="control-label">'.$field->getLabel().'</div>';
				$html .= '<div class="controls">'.$field->getInput().'</div>';
				$html .= '</div>';
			}

			if (count($getFieldsets) > 1) {
				$html .= '</fieldset>';
			}
		}

		$html .= '</div>'; //span

		if ($description) {
			$html .= '<div class="span1"></div>';
			$html .= '<div class="span4">';
			$html .= '<h3>'.$name.'</h3>';
			$html .= '<div class="well">'.$description.'</div></div>';
		}
		
		$html .= '</div>'; // row-fluid
		
		$html .= '<script type="text/javascript">
original = Joomla.submitbutton;
Joomla.submitbutton = function(task) {
	if (task == "style.apply" || task == "style.save" || task == "style.save2copy") {
		form = document.getElementById("style-form");
		form.action="index.php?option=com_jxtc&id='.JRequest::getInt('id').'"
		form.task.value = task.substr(6);
		form.submit();
	}
	else {
		original(task);
	}
}

function switchpane(name) {
 pane = document.getElementById(name);
 if (pane.className == "xtcPane") {
	pane.setAttribute("class", "xtcPane open");
 }
 else {
	pane.setAttribute("class", "xtcPane");
 }
}
</script>';
		return $html;
	}

	protected function getLabel()	{
		return '';
	}
}
