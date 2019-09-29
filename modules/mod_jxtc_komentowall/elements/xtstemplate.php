<?php
/*
	JoomlaXTC XTStemplate
	
	Version 1.0.0

	Copyright (C) 2012 Monev Software LLC. All Rights Reserved.

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

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');

class JFormFieldXtstemplate extends JFormField {

  protected $_name = 'Xtstemplate';

  protected function getInput() {

		$modulefield = $this->element['module']; if (empty($modulefield)) { $modulefield = 'moduletemplate'; }
		$elementfield = $this->element['element']; if (empty($elementfield)) { $elementfield = 'itemtemplate'; }
		$morefield = $this->element['more']; if (empty($morefield)) { $morefield = 'moretemplate'; }
		$cssfield = $this->element['css']; if (empty($cssfield)) { $cssfield = 'css'; }

		$path = dirname(dirname(__FILE__)).'/templates';
		$templates = array(JHTML::_('select.option', '', JText::_('None')));
		$templatecode = array();
		$folders = JFolder::folders($path);
		foreach ($folders as $folder) {
	    $templates[] = JHTML::_('select.option', $folder, $folder);

	    if (JFile::exists($path.'/'.$folder.'/module.html')) {
				$templatecode[$folder]['module'] = JFile::read($path.'/'.$folder.'/module.html');
	    }
	    if (JFile::exists($path.'/'.$folder.'/element.html')) {
				$templatecode[$folder]['element'] = JFile::read($path.'/'.$folder.'/element.html');
	    }
	    if (JFile::exists($path.'/'.$folder.'/more.html')) {
				$templatecode[$folder]['more'] = JFile::read($path.'/'.$folder.'/more.html');
	    }
	    if (JFile::exists($path.'/'.$folder.'/template.css')) {
				$templatecode[$folder]['css'] = JFile::read($path.'/'.$folder.'/template.css');
	    }
		}

		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
			var JSONobject =' . json_encode($templatecode) . ';
	
			function tmplchange() {
				var btnclass = document.getElementById("'.$this->id.'").value == "" ? "hasTip btn btn-small btn-primary disabled" : "hasTip btn btn-small btn-primary";
				document.getElementById("templateload").setAttribute("class", btnclass)
			}
	
			function applyxts() {
				var template = document.getElementById("' . $this->id . '").value;
				if (template != "") {
					var flag = false;
					if (JSONobject[template].module != undefined) {
						document.adminForm.jform_params_' . $modulefield . '.value = JSONobject[template].module;
						flag = true;
					}
					if (JSONobject[template].element != undefined) {
						document.adminForm.jform_params_' . $elementfield . '.value = JSONobject[template].element;
						flag = true;
					}
					if (JSONobject[template].more != undefined) {
						document.adminForm.jform_params_' . $morefield . '.value = JSONobject[template].more;
						flag = true;
					}
					if (JSONobject[template].css != undefined) {
						document.adminForm.jform_params_' . $cssfield . '.value = JSONobject[template].css;
						flag = true;
					}
					if (flag == true) {
						document.getElementById("' . $this->id . '").value = "";
						var btnclass = "hasTip btn btn-small btn-primary disabled";
						document.getElementById("templateload").setAttribute("class", btnclass)
					}
				}
			}
		');

		return	'<table><tr><td style="vertical-align:top">'.
						JHTML::_('select.genericlist', $templates, $this->name, ' onchange="javascript:tmplchange()" ', 'value', 'text', $this->value, $this->id).
						'</td><td style="vertical-align:top">'.
						'&nbsp;&nbsp;<button id="templateload" type="button" class="hasTip btn btn-small btn-primary disabled" title="Load template code into parameters for editing." onclick="javascript:applyxts();">'.JText::_('JACTION_EDIT').'</button>'.
						'</td></tr></table>';
  }
}
