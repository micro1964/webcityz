<?php
/*
	JoomlaXTC Wall Renderer

	version 1.2.2

	Copyright (C) 2010,2011,2012,2013,2014  Monev Software LLC.	All Rights Reserved.

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

// Render
JHtml::_('bootstrap.loadCss', true);
JHtml::_('bootstrap.framework');
JHtml::_('behavior.framework', true);

$jxtc = 'jxtcwall'.$module->id;

$transmode = $params->get( 'transmode', "hslide" );/* showcase */
$transpause = $params->get( 'transpause', 4000 );
$transspeed = $params->get( 'transspeed', 1500 );
$transflow = $params->get('transflow', 0);
$transtype = $params->get('transtype', 1);
$transease = $params->get('transease', 3);
$translayer = $params->get('translayer', 0);
$button	= $params->get('button','gray');
$width = $params->get('width',200);
$height = $params->get('height',200);
$sbFX = $params->get('npslideitfx','BSI'); /* SlideBox */
$sbXi = $params->get('npslixin',0);
$sbXo = $params->get('npslixout',0);
$sbYi = $params->get('npsliyin',0);
$sbYo = $params->get('npsliyout',0);
$sbObj = "{xi:$sbXi,xo:$sbXo,yi:$sbYi,yo:$sbYo}";
$sbAnim = $params->get('npsliAnim','Quad');
$sbEase = $params->get('npsliEase','easeIn');
$sbFps = $params->get('npslidi',50);
$sbDura = $params->get('npslido',800);
$sbTran = ($sbAnim == 'linear') ? "{fxtype:new Fx.Transition(Fx.Transitions.$sbAnim),dura:$sbDura,frames:$sbFps}" : "{fxtype:new Fx.Transition(Fx.Transitions.$sbAnim.$sbEase),dura:$sbDura,frames:$sbFps}";
$nptipoi = $params->get('nptipoi',1);	/* Jxtctips */
$nptipoo = $params->get('nptipoo',0);
$nptipvi = $params->get('nptipvi',0);
$nptipvo = $params->get('nptipvo',0);
$nptiphi = $params->get('nptiphi',0);
$nptipho = $params->get('nptipho',0);
$nptipdi = $params->get('nptipdi',550);
$nptipdo = $params->get('nptipdo',550);
$nptipfi = $params->get('nptipfi',0);
$nptipfo = $params->get('nptipfo',0);
$nptpause = $params->get('nptpause',1000);
$nptipAnim = $params->get('nptipAnim','Quad');
$nptipEase = $params->get('nptipEase','easeIn');
$nptipCenter = $params->get('nptipCenter',1);
$hoi = $params->get('hoifx','#CECECE'); /* Jxtc Hover */
$hoo = $params->get('hoofx','#FFFFFF');
$onclick = $params->get('onclick',0);

if ($transmode == 'wind' || $transmode == 'winz') { $pages = 1; }

$pageshtml = '';
$mainareahtml = '<div id="wallview'.$jxtc.'" class="wallviewbootstrap columns-'.$columns.' rows-'.$rows.'" style="overflow:hidden"><div class="wallspinner"></div><div id="wallslider'.$jxtc.'" class="wallslider"><div class="wallsliderrow">';
$spanClass = 'span'.floor((12/$columns));

$index = 1;
$itemPages = array_chunk(array_slice($items,0,($columns*$rows*$pages)),($rows * $columns)); $itemPagesCount = count($itemPages);
$p=1;
foreach ($itemPages as $page) {

	$mainareahtml .= '<div class="wallslidercell">';
	if ($itemPagesCount == 1) { $pageclass = 'single'; }	// Page class
	elseif ($p == 1) { $pageclass = 'first'; }
	elseif ($p == $itemPagesCount) { $pageclass = 'last'; }
	else { $pageclass = 'center'; }
	$pageclass = ($p%2) ? 'oddpage '.$pageclass : 'evenpage '.$pageclass;

	$pageshtml .= '<span class="pag'.$jxtc.' pagebutton '.$pageclass.'button pag-'.$p.'" ><span>&nbsp;'.$p.'&nbsp;</span></span>';
	$mainareahtml .= '<div class="wallpage '.$pageclass.'page page-'.$p.'" >';

	$itemRows = array_chunk($page,$columns); $itemRowsCount = count($itemRows);
	$r=1;
	foreach ($itemRows as $row) {
		if ($itemRowsCount == 1) { $rowclass = 'singlerow'; }	// Row class
		elseif ($r == 1) { $rowclass = 'firstrow'; }
		elseif ($r == $itemRowsCount) { $rowclass = 'lastrow'; }
		else { $rowclass = 'centerrow'; }
		$rowclass .= ($r%2) ? ' oddrow' : ' evenrow';

		$mainareahtml .= '<div class="row-fluid '.$rowclass.' row-'.$r.'">';

		$itemColumnCount = count($row);
		$c=1;
		foreach ($row as $item) {
			$itemhtml = $itemtemplate;
			require JModuleHelper::getLayoutPath($module->module, 'default_parse');
			if ($itemColumnCount == 1) { $colclass = 'singlecol'; } 	// Col class
			elseif ($c == 1) { $colclass = 'firstcol'; }
			elseif ($c == $itemColumnCount) { $colclass = 'lastcol'; }
			else { $colclass = 'centercol'; }
			$colclass .= ($c%2) ? ' oddcol' : ' evencol';

			$mainareahtml .= '<div class="'.$spanClass.' '.$colclass.' col-'.$c.'" >'.$itemhtml.'</div>';
			$c++;
			$index++;
		}
		$mainareahtml .='</div>'; // wallrow
		$r++;
	}
	$mainareahtml .= '</div>'; // wallpage
	$mainareahtml .= '</div>'; // wallslidercell
	if ($transmode == 'vslide' && $items) { $mainareahtml .= '</div><div class="wallsliderrow">'; }
	$p++;
}
$mainareahtml .= '</div></div></div>'; // wallsliderrow wallslider wallview

// preps
$leftbuttonhtml = '';
$rightbuttonhtml = '';
if ($itemPagesCount) {
	if (preg_match('#{leftbutton}(.*?){/leftbutton}#is', $moduletemplate, $hits)) { // custom left button
	  $tag = $hits[0];
	  $html = '<span id="prev'.$jxtc.'" class="prevbutton">'.trim($hits[1]).'</span>';
	  $moduletemplate = str_replace($tag, $html, $moduletemplate);
	} else { // standard button
		$leftbuttonhtml = ($button == -1) ? '' : '<img id="prev'.$jxtc.'" class="prevbutton" src="'.$live_site.'modules/'.$moduleDir.'/buttons/'.$button.'/prev.png" alt="" />';
	}
	
	if (preg_match('#{rightbutton}(.*?){/rightbutton}#is', $moduletemplate, $hits)) { // custom left button
	  $tag = $hits[0];
	  $html = '<span id="next'.$jxtc.'" class="nextbutton">'.trim($hits[1]).'</span>';
	  $moduletemplate = str_replace($tag, $html, $moduletemplate);
	} else { // standard button
		$rightbuttonhtml = ($button == -1) ? '' : '<img id="next'.$jxtc.'" class="nextbutton" src="'.$live_site.'modules/'.$moduleDir.'/buttons/'.$button.'/next.png" alt="" />';
	}
	// FX
	$jxtcsettings = "{opacityin:$nptipoi,opacityout:$nptipoo,verticalin:$nptipvi,verticalout:$nptipvo,horizontalin:$nptiphi,horizontalout:$nptipho,durationin:$nptipdi,durationout:$nptipdo,pause:$nptpause,fxtype:new ".($nptipAnim=='linear' ? "Fx.Transition(Fx.Transitions.linear)" : "Fx.Transition(Fx.Transitions.$nptipAnim.$nptipEase)").",centered:'$nptipCenter'}";
	$css = $params->get('css');
	if ($css) { $doc->addStyleDeclaration($css); }
	$doc->addScript($live_site.'media/JoomlaXTC/wallFX.js');
	$doc->addScriptDeclaration("window.addEvent('load', function(){ // ".$module->id."
	var ".$jxtc."slidebox = new slidebox('$jxtc','$sbFX',$sbObj,$sbTran);
	var ".$jxtc."jxtcpops = new jxtcpops('$jxtc',$jxtcsettings);
	var ".$jxtc."jxtctips = new jxtctips('$jxtc',$jxtcsettings);
	var ".$jxtc."jxtchover = new jxtchover('$jxtc','$hoi','$hoo');
	});");
	$tType=array('','linear','Quad','Cubic','Quart','Quint','Expo','Circ','Sine','Back','Bounce','Elastic');
	$tEase=array('','easeIn','easeOut','easeInOut');
	
	$transtype = ($transtype == 1) ? 'new Fx.Transition(Fx.Transitions.linear)' : 'new Fx.Transition(Fx.Transitions.'.$tType[$transtype].'.'.$tEase[$transease].')';
	$transflow = ($transflow == 0) ? 0 : $itemPagesCount -1;
	switch ($transmode) {
		case 'vslide':
			$doc->addScriptDeclaration("window.addEvent('load', function(){var $jxtc = new wallFX('$jxtc',{fxmode:'slideVer',slidestart:'$transflow',fxpause:$transpause,fxspeed:$transspeed,fxlayer:'$translayer',fxtype:$transtype,onclick:$onclick});});");
		break;
		case 'hslide':
			$doc->addScriptDeclaration("window.addEvent('load', function(){var $jxtc = new wallFX('$jxtc',{fxmode:'slideHor',slidestart:'$transflow',fxpause:$transpause,fxspeed:$transspeed,fxlayer:'$translayer',fxtype:$transtype,onclick:$onclick});});");
		break;
		case 'fade':
			$doc->addScriptDeclaration("window.addEvent('load', function(){var $jxtc = new wallFX('$jxtc',{fxmode:'fade',slidestart:'$transflow',fxpause:$transpause,fxspeed:$transspeed,fxlayer:'$translayer',fxtype:$transtype,onclick:$onclick});});");
		break;
		case 'wind':
			$doc->addScriptDeclaration("window.addEvent('load', function(){var $jxtc = new wallfx('$jxtc',$width,$height,0);});");
		break;
		case 'winz':
			$doc->addScriptDeclaration("window.addEvent('load', function(){var $jxtc = new wallfx('$jxtc',$width,$height,1);});");
		break;
	}
}

$doc->addStyleSheet($live_site.'modules/'.$moduleDir.'/css/wall.css','text/css');
$modulehtml = $moduletemplate;
$modulehtml = str_replace( '{leftbutton}', $leftbuttonhtml, $modulehtml );
$modulehtml = str_replace( '{rightbutton}', $rightbuttonhtml, $modulehtml );
$modulehtml = str_replace( '{mainarea}', $mainareahtml, $modulehtml );
$modulehtml = str_replace( '{pages}', $pageshtml, $modulehtml );
