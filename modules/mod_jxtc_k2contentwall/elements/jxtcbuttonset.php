<?php
/*
	JoomlaXTC jxtcbuttonset

	version 1.0.0

	Copyright (C) 2012 Monev Software LLC.	All Rights Reserved.

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

	THIS LICENSE IS NOT EXTENSIVE TO ACCOMPANYING FILES UNLESS NOTED.

	See COPYRIGHT.txt for more information.
	See LICENSE.txt for more information.

	Monev Software LLC
	www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;

jimport( 'joomla.html.parameter' );

jimport('joomla.form.formfield');

class JFormFieldJxtcbuttonset extends JFormField {

	protected	$_name = 'Jxtcbuttonset';

	protected function getInput()	{
		$live_site = JURI::root();
		$document = JFactory::getDocument();
    JHTML::_('behavior.modal');

		$directory = $this->element['directory'];

		$dirname = basename(dirname(dirname(__FILE__)));
		$document->addScript($live_site."modules/$dirname/elements/easypopup.js");
		$jxtc = uniqid('jxtc');
    $document->addStyleDeclaration("
      .xtc_easypopup{display:inline-block;cursor:pointer;margin-left:10px;float:left}
      .ep_pop {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        background-color: #FFFFFF;
        border-radius: 5px;
        padding: 10px;
        position: absolute;
        text-align: left;
				visibility:hidden;
      }
			.ep_close {
				position:absolute;
				width:30px;
				height:30px;
				right:-15px;
				top:-15px;
				background:url(".JURI::root(true)."/media/system/images/modal/closebox.png) no-repeat top left;
				border:none;
				cursor:pointer;
			}
			.demoWrap {
				width:auto;
				height:380px;
				overflow:scroll;
			}
			.demoBkg {
				text-align:center;
				vertical-align:middle;
				padding:0px;
			}
			.demoImg {
				border:1px solid #dddddd;
				/*background:url(".$live_site."modules/$dirname/images/buttonBkg.gif);*/
				padding:3px;
			}
			.demoName,.setName {
			 font-weight:bold;
			 text-align:center;
			}
			.demoName {padding-bottom:8px;}
			.ep_dark {
			  position: absolute;
			  visibility: hidden;
			  top: 0;
			  left: 0;
			  background: #fff;
			}
			");
    $document->addScriptDeclaration("
			function selector(setname) {
				document.getElementById('".$this->id."').value=setname;
				document.getElementById('".$this->id."prev').src='$live_site"."$directory/'+setname+'/prev.png';
				document.getElementById('".$this->id."next').src='$live_site"."$directory/'+setname+'/next.png';
				document.getElementById('jxtc_ep_close').click();
			}

			window.addEvent('domready', function(){
	      new xtcEasyPopup({
	        targets: '.xtc_easypopup',
	        closeOpened: true,
	        centered: true,
	        margin: 0,
	        fade: true,
	        duration: 100,
	        dark: false
	      });
      });");
            
		$sets = JFolder::folders(JPATH_ROOT.DIRECTORY_SEPARATOR.$directory);
		$selectorHtml = '<div class="demoWrap"><table class="demoTable" border="0" cellspacing="0">';
		foreach ($sets as $set) {
			$selectorHtml .= '<tr onclick="javascript:selector(\''.$set.'\')">
			<td class="demoBkg">
				<img class="demoImg" src="'.$live_site.$directory.'/'.$set.'/prev.png" />
			</td>
			<td class="demoBkg">
				<img class="demoImg" src="'.$live_site.$directory.'/'.$set.'/next.png" />
			</td>
			<tr>
				<td class="demoName" colspan="2">'.$set.'</td>
			</tr>';
		}		
		$selectorHtml .= '</table></div>';
		
		$html = '
				<table id="'.$this->id.'table" border="0" cellpadding="0" cellspacing="3">
					<tr>
						<td>
							<div class="well">
								<img class="setImg" id="'.$this->id.'prev" src="'.$live_site.$directory.'/'.$this->value.'/prev.png" />
								<img class="setImg" id="'.$this->id.'next" src="'.$live_site.$directory.'/'.$this->value.'/next.png" />
							</div>
						</td>
						<td>
							<div class="xtc_easypopup" rel="'.$jxtc.'popup">
								<button type="button" class="hasTip btn btn-small" title="Select a button set.">'.JText::_('JSelect').'</button>
							</div>
							<div id="'.$jxtc.'popup" class="ep_pop">'.$selectorHtml.'</div>
						</td>
					</tr>
				</table>
			<input type="hidden" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" >
		';

		return $html;
	}
}