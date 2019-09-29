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

class JFormFieldStylegroups extends JFormField {

	protected	$type = 'Stylegroups';

	protected function getInput()	{

		if (!function_exists('xtcsortfn')) {
				function xtcsortfn($a, $b) {
			   return strnatcmp($a[0],$b[0]);
			}
		}

		jimport( 'joomla.filesystem.folder' );
		JHTML::_( 'behavior.modal', 'a.modal' );

		$live_site = JURI::root();
		$template = basename(dirname(dirname(dirname(__FILE__))));
		$document = JFactory::getDocument();
		$id = JRequest::getInt('id');

		$document->addStyleSheet($live_site."templates/$template/XTC/XTC.css",'text/css');
		$path = JPATH_ROOT.'/templates/'.$template;
		$files = JFolder::files($path.'/parameters','xml');
		$onopen = "var iSize;
    var iFrame = this.asset;
    iFrame.addEvent('load', function(){
      var iBody = (iFrame.contentDocument) ? iFrame.contentDocument.getElementsByTagName('body')[0] : iFrame.contentWindow.document.getElementsByTagName('body')[0];

	 iSize = {x:this.size.x, y:iBody.offsetHeight};
      var per = (window.innerHeight * 0.9).toInt();
      iSize.y = (iSize.y > per) ? per : iSize.y ;
      iFrame.setStyles({'height':'100%'});

      this.resize(iSize, false);
    }.bind(this));";

		$parmsfound=false;
		$prefix = trim($this->element['group']);
		$label = trim($this->element['label']);
		$parameters = array();
		foreach ($files as $file) {
			@list($filename,$extension)=explode('.',$file,2);
			if ($extension != 'xml') continue; // Not an XML
			if (strpos($filename,$prefix) !== 0) continue; // Not the right prefix
			$xmlFile = $path.'/parameters/'.$file;
			if (is_readable($xmlFile)) {
				$xml = simplexml_load_file($xmlFile);
				$name = isset($xml->name) ? $xml->name : $filename;
				$description = isset($xml->description) ? trim($xml->description) : '';
			}
			else {
				$name = $filename;
				$description = '';
			}

			$thumbnail = '';
			if (file_exists($path.'/parameters/'.$filename.'.gif')) {$thumbnail = $filename.'.gif'; }
			elseif (file_exists($path.'/parameters/'.$filename.'.jpg')) {$thumbnail = $filename.'.jpg'; }
			elseif (file_exists($path.'/parameters/'.$filename.'.png')) {$thumbnail = $filename.'.png'; }

			$fullimage = '';
			if (file_exists($path.'/parameters/'.$filename.'_full.gif')) {$fullimage = $filename.'_full.gif'; }
			elseif (file_exists($path.'/parameters/'.$filename.'_full.jpg')) {$fullimage = $filename.'_full.jpg'; }
			elseif (file_exists($path.'/parameters/'.$filename.'_full.png')) {$fullimage = $filename.'_full.png'; }
			$parameters[] = array($name,$filename,$description,$thumbnail,$fullimage);
		}

		usort($parameters, "xtcsortfn");

		$stringFilter = JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
		require_once $path.'/XTC/XTC_library.php';
		$templateParameters = xtcLoadParams($id);

		$html = '';
		$navHtml = '';
		$tabHtml = '';
		
		$count = count($parameters);
		$openClass = ($count ==1) ? ' open' : '';
		foreach ($parameters as $i => $parameter) { // Draw parameters

			list($name,$filename,$description,$thumbnail,$fullimage) = $parameter;
			$params = isset($templateParameters->group->$filename) ? $templateParameters->group->$filename : new stdClass();

			$tabid = $filename.$i;

			// Parameter Tab
			$active = ($i ==0) ? ' class="active"' : '';
			$navHtml .= '<li'.$active.'>';
			$navHtml .= '<a href="#'.$tabid.'" data-toggle="tab">';
			if ($thumbnail) {
				$thumbnailURL = $live_site.'templates/'.$template.'/parameters/'.$thumbnail;
				$navHtml .= '<img class="img-rounded" src="'.$thumbnailURL.'" alt="" /><br/>';
			}
			$navHtml .= '<center>'.$name.'</center>';
			$navHtml .= '</a>';
			$navHtml .= '</li>';

			// Parameter Tab contents
			$form = new JForm('jxtc');
			$form->addFieldPath(JPATH_ROOT.'/templates/'.$template.'/XTC/elements');
			$xmlFile = $path.'/parameters/'.$filename.'.xml';
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
					if ($label) { $tabHtml .='<legend>'.$label.'</legend>'; }
					if ($fieldsetDescription) { $tabHtml .= '<div class="well">'.$fieldsetDescription.'</div>'; }
				}

				foreach($form->getFieldset($fieldset->name) as $field) {
					$xtcName = substr($field->name,7,-1);
					if (isset($params->$xtcName)) { $field->value = $params->$xtcName; }

					$field->name = str_replace('params[','xtcparam['.$prefix.']['.$filename.'][',$field->name);
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
					$fullimageURL = $live_site.'templates/'.$template.'/parameters/'.$fullimage;
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
	
	protected function getLabel()	{
		return '';
	}
}
