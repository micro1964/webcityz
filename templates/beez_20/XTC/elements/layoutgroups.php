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
jimport( 'joomla.filter.filterinput' );

class JFormFieldLayoutgroups extends JFormField {

	protected	$type = 'Layoutgroups';

	protected function getInput()	{

		if (!function_exists('xtcsortfn')) {
				function xtcsortfn($a, $b) {
			   return strnatcmp($a[0],$b[0]);
			}
		}

		jimport( 'joomla.filesystem.folder' );
		JHTML::_( 'behavior.modal', 'a.modal' );

		$template = basename(dirname(dirname(dirname(__FILE__))));
		$live_site = JURI::root();
		$document = JFactory::getDocument();
		$id = JRequest::getInt('id');

		$document->addStyleSheet($live_site."templates/$template/XTC/XTC.css",'text/css');
		$path = JPATH_ROOT.'/templates/'.$template;
		$folders = JFolder::folders($path.'/layouts');
    
		$layouts = array();
		foreach ($folders as $folder) { // Parse parameter files
			$xmlFile = $path.'/layouts/'.$folder.'/config.xml';
			if (is_readable($xmlFile)) {
				$xml = simplexml_load_file($xmlFile);
				$name = isset($xml->name) ? $xml->name : $folder;
				$description = isset($xml->description) ? trim($xml->description) : '';
			}
			else {
				$name = $folder;
				$description = '';
			}
			$thumbnail = '';
			if (file_exists($path.'/layouts/'.$folder.'/thumbnail.gif')) { $thumbnail = 'thumbnail.gif'; }
			elseif (file_exists($path.'/layouts/'.$folder.'/thumbnail.jpg')) {$thumbnail = 'thumbnail.jpg'; }
			elseif (file_exists($path.'/layouts/'.$folder.'/thumbnail.png')) {$thumbnail = 'thumbnail.png'; }

			$fullimage = '';
			if (file_exists($path.'/layouts/'.$folder.'/layout.gif')) { $fullimage = 'layout.gif'; }
			elseif (file_exists($path.'/layouts/'.$folder.'/layout.jpg')) {$fullimage = 'layout.jpg'; }
			elseif (file_exists($path.'/layouts/'.$folder.'/layout.png')) {$fullimage = 'layout.png'; }

			$layouts[] = array($name,$folder,$description,$thumbnail,$fullimage);
		}

		usort($layouts, "xtcsortfn");

		$stringFilter = JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
		require_once $path.'/XTC/XTC_library.php';
		$templateParameters = xtcLoadParams($id);
		
		$html = '';
		$navHtml = '';
		$tabHtml = '';
		
		$count = count($layouts);
		$openClass = ($count==1) ? ' open' : '';
		foreach ($layouts as $i => $layout) { // Draw layouts

			list($name,$folder,$description,$thumbnail,$fullimage) = $layout;
			$params = isset($templateParameters->group->$folder) ? $templateParameters->group->$folder : new stdClass();

			$tabid = $folder.$i;

			// Parameter Tab
			$active = ($i ==0) ? ' class="active"' : '';
			$navHtml .= '<li'.$active.'>';
			$navHtml .= '<a href="#'.$tabid.'" data-toggle="tab">';
			if ($thumbnail) {
				$thumbnailURL = $live_site.'templates/'.$template.'/layouts/'.$folder.'/'.$thumbnail;
				$navHtml .= '<img class="img-rounded" src="'.$thumbnailURL.'" alt="" /><br/>';
			}
			$navHtml .= '<center>'.$name.'</center>';
			$navHtml .= '</a>';
			$navHtml .= '</li>';

			// Parameter Tab contents
			$form = new JForm('jxtc');
			$form->addFieldPath(JPATH_ROOT.'/templates/'.$template.'/XTC/elements');
			$xmlFile = $path.'/layouts/'.$folder.'/config.xml';
			$form->loadFile($xmlFile);
			$getFieldsets = $form->getFieldsets();
		
			$active = ($i ==0) ? ' active' : '';
			$tabHtml .= '<div class="tab-pane'.$active.'" id="'.$tabid.'">';
			$tabHtml .= '<div class="xtc row-fluid">';
			$tabHtml .= '<div class="span5">';

			foreach ($getFieldsets as $fieldsets => $fieldset) {
				$label = trim($fieldset->label);
				if (empty($label)) $label = trim($fieldset->name);
				$fieldsetDescription = isset($fieldset->description) ? trim($fieldset->description) : '';

				if (count($getFieldsets) > 1) {
					$tabHtml .= '<fieldset>';
					if ($label) { $tabHtml .= '<legend>'.$label.'</legend>'; }
					if ($fieldsetDescription) { $tabHtml .= '<div class="well">'.$fieldsetDescription.'</div>'; }
				}

				foreach($form->getFieldset($fieldset->name) as $field) {
					$xtcName = substr($field->name,7,-1);
					if (isset($params->$xtcName)) { $field->value = $params->$xtcName; }
					$field->name = str_replace('params[','xtcparam[layout]['.$folder.'][',$field->name);
					$field->id = $stringFilter->clean($field->name, 'cmd');

					$tabHtml .= '<div class="control-group">';
					$tabHtml .= '<div class="control-label">'.$field->getLabel().'</div>';
					$tabHtml .= '<div class="controls">'.$field->getInput().'</div>';
					$tabHtml .= '</div>';
				}

				if (count($getFieldsets) > 1) {
					$tabHtml .= '</fieldset>';
				}
			}

			$tabHtml .= '</div>';	// span5

			if ($description || $fullimage) {
				$tabHtml .= '<div class="span1"></div>';
				$tabHtml .= '<div class="span4"><div class="well">';
				$tabHtml .= '<h3>'.$name.'</h3>';
				if ($description) $tabHtml .= $description.'<br/><br/>';
				if ($fullimage) {
					$fullimageURL = $live_site.'templates/'.$template.'/layouts/'.$folder.'/'.$fullimage;
					$headerimg_size = getimagesize($path.'/layouts/'.$folder.'/'.$fullimage);
					$width = $headerimg_size[0];
					$height = $headerimg_size[1]; 
					$onclick = "onclick=\"MyWindow=window.open('$fullimageURL','_blank','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=$width,height=$height'); return false;\"";
					$tabHtml .= '<a class="btn btn-primary" href="#" '.$onclick.'>'.JText::_('TPL_JXTC_VIEWLAYOUT').'</a>';
				}
				$tabHtml .= '</div></div>';
			}

			$tabHtml .= '</div>'; // row-fluid
			$tabHtml .= '</div>'; // tab-pane
		}

		$html .= '<div class="tabbable">';
		$html .= '<ul class="nav nav-tabs">'.$navHtml.'</ul>';
		$html .= '<div class="tab-content">'.$tabHtml.'</div>';
		$html .= '</div>';

		return $html;
	}

	protected function getLabel() {
		return '';
		$label = '';

		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = JText::_($text);

//		$label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" >' . $text . '</label>';

		if (!empty($this->description)) {
			$label .= '<div class="xtcDescription">'. JText::_($this->description) .'</div>';
		}

		return $label;
	}}
