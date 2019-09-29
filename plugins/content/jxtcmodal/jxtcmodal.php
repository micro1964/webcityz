<?php
/*
	JoomlaXTC Modal Plugin

	version 1.0.1
	
	Copyright (C) 2009.2011  Monev Software LLC.	All Rights Reserved.
	
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
	
	THIS LICENSE MIGHT NOT APPLY TO OTHER FILES CONTAINED IN THE SAME PACKAGE.
	
	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentjxtcmodal extends JPlugin {

	function onContentPrepare( $context, &$article, &$params, $limitstart ) {
  	global $app;

		$boole=array('false','true');
		$live_site = JURI::base();

		$jxtc = uniqid('jxtc');
		$enable = 0;
		while (($ini = strpos(strtolower($article->text),"{modal")) !== false) {
			$fin = strpos(strtolower($article->text),"{/modal}",$ini);
			$text = substr($article->text,$ini+6,$fin-$ini-6);
			$split = strrpos($text,"}");
			$parms= trim(substr($text,0,$split));
			$link = trim(substr($text,$split+1));
			//add class
			$cini = strpos(strtolower($parms),"class");
			if ($cini === false) {
				$parms .= ' class="'.$jxtc.'"';
			}
			else {
				$cfin = strpos($parms,'"',$cini);
				$cfin = strpos($parms,'"',$cfin+1);
				$parms=substr($parms,0,$cfin).' '.$jxtc.'"';
			}
			$html = '<a '.$parms.'>'.$link.'</a>';
			$article->text = substr_replace($article->text,$html,$ini,$fin+8-$ini);
			$enable = 1;
		}
		if ($enable) {
			JHTML::_('behavior.modal',  "a.$jxtc", array('handler'=>'iframe'));
		}
	}
}
?>