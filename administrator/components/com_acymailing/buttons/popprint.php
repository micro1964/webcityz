<?php
/**
 * @package	Acymailing for Joomla!
 * @version	4.0.0
 * @author	acyba.com
 * @copyright	(C) 2009-2012 ACYBA S.A.RL. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class JButtonPopprint extends JButton
{
	var $_name = 'Popprint';


	function fetchButton( $type='Popprint', $namekey = '', $id = 'Popprint' )
	{
?>
	<script>
	function getURL(href){
			chartType = new Array('ColumnChart', 'LineChart');
			for(var i=0; i < chartType.length; i++){
				var newType = document.getElementById('display_charttype'+chartType[i]).checked;
				if(newType){
					displayType = document.getElementById('display_charttype'+chartType[i]).value;
				}
			}
			datemin = document.getElementById('display_datemin').value;
			datemax = document.getElementById('display_datemax').value;

			intervalType = new Array('day', 'month', 'year');
			for(var i=0; i < intervalType.length; i++){
				var newType = document.getElementById('display_interval'+intervalType[i]).checked;
				if(newType){
					interval = document.getElementById('display_interval'+intervalType[i]).value;
				}
			}
			href = href+ '&display[datemin]='+datemin+ '&display[datemax]='+datemax+ '&display[interval]='+interval+ '&display[charttype]='+displayType;
			if(document.getElementById('compares_lists').checked){ href = href+'&compares[lists]=lists'; }
			if(document.getElementById('compares_years').checked){ href = href+'&compares[years]=years'; }
			return (href);
	}
	</script>
<?php
		if(!ACYMAILING_J30) {
			JHTML::_('behavior.modal');
			return '<a href="index.php?option=com_acymailing&ctrl=diagram&task=printnewsletter&tmpl=component" target="_blank" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 590}}" ' .
				'onclick="this.href=getURL(this.href);SqueezeBox.fromElement(this,{parse: \'rel\'});" class="toolbar"><span class="icon-32-acyprint" title="'.JText::_('ACY_PRINT',true).'"></span>'.JText::_('ACY_PRINT').'</a>';
		}

		$html = '<button class="btn btn-small modal" data-toggle="modal" data-target="#modal-'.$id.'"><i class="icon-14-acyprint"></i> '.JText::_('ACY_PRINT',true).'</button>';
		$params['title']  = JText::_('ACY_PRINT',true);
		$params['url']    = 'index.php?option=com_acymailing&ctrl=diagram&task=printnewsletter&tmpl=component';
		$params['height'] = 800;
		$params['width']  = 590;
		$html .= JHtml::_('bootstrap.renderModal', 'modal-'.$id, $params);
		$html .= "<script>\n";
		$html .= 'jQuery(document).ready(function(){jQuery("#modal-'.$id.'").appendTo(jQuery(document.body));});';
		$html .= "jQuery('#modal-" . $id . "').on('show', function () {\n";
		$html .= "document.getElementById('modal-" . $id . "-container').innerHTML = '<div class=\"modal-body\"><iframe class=\"iframe\" src=\"'+getURL('" . $params['url'] . "')+'\" height=\"" . $params['height'] . "\" width=\"" . $params['width'] . "\"></iframe></div>';\n";
		$html .= "});\n";
		$html .= "</script>";
		return $html;
	}


	function fetchId( $type='Pophelp', $html = '', $id = 'pophelp' )
	{
		return $this->_name.'-'.$id;
	}
}

class JToolbarButtonPopprint extends JButtonPopprint {}
