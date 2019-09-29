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

// Support functions for XTC framework

defined( '_JEXEC' ) or die;

if (!file_exists(JPATH_ROOT.'/components/com_jxtc')) {
	echo '<div style="float:left;border:1px solid #000;padding:10px;font-family:sans-serif">
	<div style="background:#f0f0f0;padding:20px;">
	<span style="color:#f00;font-size:20px;font-weight:bold;">Framework Error:</span>
	<br/><br/>
	<span style="color:#444">The XTC Framework helper component was not found.
	<br/><br/>
	Please install the component before using this template.
	<br/><br/>
	For more information and download links visit <a target="_blank" href="http://www.joomlaxtc.com">www.joomlaxtc.com</a>.
	</span></div></div>';
	die;
}

jimport( 'joomla.application.module.helper' );

// Create XTC Framework Object
$live_site = JURI::root();
if (substr($live_site,-5) == '/XTC/') { $live_site = dirname(dirname(dirname($live_site))).'/'; }
$xtc = new stdClass();
// Set defaults
$xtc->live_site = $live_site;
$xtc->templatePath = dirname(dirname(__FILE__));
$xtc->template = basename($xtc->templatePath);
$xtc->templateUrl = $xtc->live_site.'templates/'.$xtc->template.'/';
$xtc->CSSmode = 2;	// CSS mode: 1 = Single file, 2 = Separate files, 3 = Embedded in head
$xtc->CSScompression = false;	// enable/disable CSS compression
$xtc->publicParams = isset($publicParams) ? $publicParams : array();
$xtc->showComponents = isset($showComponents) ? $showComponents : array();
$xtc->customPresets = isset($customPresets) ? $customPresets : array();

// Get Browser info
$devices = array(
    "android"       => "android",
    "blackberry"    => "blackberry",
    "iphone"        => "(iphone|ipod)",
    "ipad"        	=> "ipad",
    "opera"         => "opera mini",
    "palm"          => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
    "windows"       => "windows ce; (iemobile|ppc|smartphone)",
    "generic"       => "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)"
);

//$xtc->agent = get_browser(null);

$xtc->agent = new stdClass();
$xtc->agent->isMobile = false;
$xtc->agent->device = 'generic';
$HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$HTTP_ACCEPT = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
$HTTP_X_WAP_PROFILE = isset($_SERVER['HTTP_X_WAP_PROFILE']) ? $_SERVER['HTTP_X_WAP_PROFILE'] : '';
$HTTP_PROFILE = isset($_SERVER['HTTP_PROFILE']) ? $_SERVER['HTTP_PROFILE'] : '';

if ($HTTP_X_WAP_PROFILE || $HTTP_PROFILE) {
	$xtc->agent->isMobile = true;
}
elseif (strpos($HTTP_ACCEPT,'text/vnd.wap.wml') !== false  || strpos($HTTP_ACCEPT,'application/vnd.wap.xhtml+xml') !== false) {
	$xtc->agent->isMobile = true;
}
foreach ($devices as $device => $regexp) {
	preg_match("/".$regexp."/i", $HTTP_USER_AGENT,$null);
	if (isset($null[0])) {
	  $xtc->agent->isMobile = true;
		$xtc->agent->device = $null[0];
		break;
	}
}

$xtc->agent->browserName  =  strtok($HTTP_USER_AGENT,  "/"); 
$xtc->agent->browserVersion  =  strtok( "  "); 
if (strpos( $HTTP_USER_AGENT, "MSIE") !== false ) { 
	$xtc->agent->browserName  =  "MSIE"; 
  $xtc->agent->browserVersion  =  strtok( "MSIE"); 
  $xtc->agent->browserVersion  =  strtok( "  "); 
  $xtc->agent->browserVersion  =  strtok( ";"); 
} 
if (strpos( $HTTP_USER_AGENT, "Safari") !== false ) { 
	$xtc->agent->browserName  =  "Safari"; 
  $hold = explode("Safari",$HTTP_USER_AGENT);
  $hold = explode(" ",substr($hold[1],1));
  $xtc->agent->browserVersion = $hold[0];
}
if (strpos( $HTTP_USER_AGENT, "Chrome") !== false ) { 
	$xtc->agent->browserName  =  "Chrome"; 
  $hold = explode("Chrome",$HTTP_USER_AGENT);
  $hold = explode(" ",substr($hold[1],1));
  $xtc->agent->browserVersion = $hold[0];
}
if (strpos( $HTTP_USER_AGENT, "Opera") !== false ) { 
	$xtc->agent->browserName  =  "Opera"; 
  $hold = explode("Version",$HTTP_USER_AGENT);
  $hold = explode(" ",substr($hold[1],1));
  $hold = explode(".",$hold[0]);
  $xtc->agent->browserVersion = $hold[0];
}
/*  try  to  figure  out  what  platform,  windows  or  mac  */ 
$xtc->agent->platform  =  "unknown"; 
if (strpos( $HTTP_USER_AGENT, "Windows" ) !== false || strpos( $HTTP_USER_AGENT, "WinNT" ) !== false || strpos( $HTTP_USER_AGENT, "Win95" ) !== false) { 
	$xtc->agent->platform  =  "Windows"; 
}
if (strpos ( $HTTP_USER_AGENT, "Mac" ) !== false) { 
	$xtc->agent->platform  =  "Macintosh"; 
} 
$GLOBALS['xtc']=&$xtc;

// CORE XTC FUNCTIONS

function xtcLoadParams($style=0) {
// Load raw params*.ini file and returns param object (also set on $GLOBALS)
// public parameters on URL or cookie will override template parameters
	$xtc =& $GLOBALS['xtc'];

	if (empty($style)) {
		$app = JFactory::getApplication();
		$template = $app->getTemplate(true);
		if (!isset($template->id)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('id')
				->from('#__template_styles')
				->where('client_id=0 AND home=1');
			$db->setQuery($query);
			$template->id = $db->loadResult();
		}
		$style = $template->id;
	}

	// Get cookie variables array
	$cookie=array();

	if ($xtc->publicParams) { // there are public parameters, cookie them!
		foreach ($xtc->publicParams as $parm) {
			$value = JFactory::getApplication()->getUserStateFromRequest( "com_jxtc.$parm", $parm);
			$value = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
			if ($value) { // We have a URL override
				$cookie[$parm]=$value;
			}
		}
	}

// Build parameter object

	$obj = new stdClass();
	$obj->group = new stdClass();
	$obj->prefix = array();
	$obj->groups = array();

	// Parse parameters file
	$parameterFile = $xtc->templatePath.'/params_'.$style.'.ini';

	if (!is_readable($parameterFile)) {
		$data = xtcMakeParams($style);
	}
	else {
		$data = file($parameterFile);
	}
	
	foreach ($data as $rec) {
		$rec=trim($rec);
		$pos=strpos($rec,'=');
		if ($pos===false) { continue; }
		$parm=substr($rec,0,$pos);
		$value=stripcslashes(substr($rec,$pos+1));

		if (substr($parm,0,1) == '{') { // XTC-formed parm
			$pos=strpos($parm,'}');
			$group=substr($parm,1,$pos-1);
			@list($prefix,$group)=explode('+',$group);
			$parm=substr($parm,$pos+1);
			if (!isset($obj->group->$group)) {
				$obj->group->$group = new stdClass();
				$obj->prefix[$group] = $prefix;
				$obj->groups[]= $group;
			}
		}
		else { // No group
			$group = '';
		}

		if (array_key_exists($parm,$cookie)) {
			$value = htmlspecialchars(strip_tags($cookie[$parm]), ENT_QUOTES, 'UTF-8'); // Override with value on cookie parm, if present
		}

		if ($group) { // It's from a group
			$obj->group->$group->$parm = $value;
		}
		else {
			$obj->$parm = $value;
		}
//echo "parm: [$group][$parm][$value]<br>";
	}

	// Update XTC object with style ID and XTC parameters

	$xtc->style = $style;
	if (isset($obj->CSSmode)) $xtc->CSSmode = $obj->CSSmode;	// CSS mode: 1 = Single file, 2 = Separate files, 3 = Embedded in head
	if (isset($obj->CSScompression)) $xtc->CSScompression = $obj->CSScompression;	// CSS compression 0 = off, 1 = on
	if (isset($obj->publicParams)) { $xtc->publicParams = array_map('trim',explode(',',$obj->publicParams)); } // Public parameters
	if (isset($obj->showComponents)) { $xtc->showComponents = explode('|',$obj->showComponents); } // Frontpage components

	$GLOBALS['templateParameters']=&$obj;
	return $obj;
}

function xtcCSS() {
	$xtc =& $GLOBALS['xtc'];
	$templateParameters =& $GLOBALS['templateParameters'];
	$groups=func_get_args();

	switch ($xtc->CSSmode) {
		case 1:	// Single file
			$doc = JFactory::getDocument();
			$doc->addStyleSheet( $xtc->templateUrl.'XTC/css.php?id='.$xtc->style.'&amp;groups='.implode(',',$groups), 'text/css');
		break;
		case 2: // Separate files
			$doc = JFactory::getDocument();
			$doc->addStyleSheet( $xtc->templateUrl.'XTC/css.php?id='.$xtc->style.'&amp;file=default', 'text/css');
			foreach ($groups as $group) {
				if ($group) { $doc->addStyleSheet( $xtc->templateUrl.'XTC/css.php?id='.$xtc->style.'&amp;group='.$group, 'text/css'); }
			}
			$doc->addStyleSheet( $xtc->live_site.'templates/system/css/system.css', 'text/css');
			$doc->addStyleSheet( $xtc->live_site.'templates/system/css/general.css', 'text/css');
			$doc->addStyleSheet( $xtc->templateUrl.'XTC/css.php?id='.$xtc->style.'&amp;file=template', 'text/css');
		break;
		case 3: // Embedded
			echo "<style type=\"text/css\">\n";
			$params = $templateParameters;
			$imgpath = $xtc->templateUrl.'images';
			require $xtc->templatePath.'/css/default.css';
			foreach ($groups as $group) {
				$prefix = $templateParameters->prefix[$group];
				$params = $templateParameters->group->$group;	// Parameters for the selected group
				if (is_readable($xtc->templatePath.'/css/'.$group.'.css')) {
					require $xtc->templatePath.'/css/'.$group.'.css';
				}
				elseif (is_readable($xtc->templatePath.'/css/'.$prefix.'.css')) {
					require $xtc->templatePath.'/css/'.$prefix.'.css';
				}
			}
			require JPATH_ROOT.'/templates/system/css/system.css';
			require JPATH_ROOT.'/templates/system/css/general.css';
			if (file_exists($xtc->templatePath.'/css/template.css')) {
				require $xtc->templatePath.'/css/template.css';
			}
			echo "\n</style>\n";
		break;
	}
}

function xtcIsFrontpage() {
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$active = $menu->getActive();
	return ( isset($active->home) && $active->home == '1' );
}

function xtcCanShowComponent() {
	$xtc =& $GLOBALS['xtc'];
	$option = JRequest::getCmd('option');
	
	if ( xtcIsFrontpage() ) {
		if ( in_array('all',$xtc->showComponents) ) { return true; }
		elseif ( in_array('none',$xtc->showComponents) ) { return false; }
		else { return in_array($option, $xtc->showComponents); }
	}
	else { return true; }
}

function xtcGrid($areaWidth,$gutter,$order,$includeCall,$customWidths=array(),$columnClass='',$columnPad=0,$debug=false) {
	$xtc =& $GLOBALS['xtc'];

	$order = empty($order) ? array_keys($includeCall) : explode(",",$order);
	if (!is_array($customWidths)) settype($customWidths,"array");
	if (isset($xtc->debug) && $xtc->debug) $debug = true;

	$doc = JFactory::getDocument();

 	// Get list of columns to show based on joomla content count per column
 	$columnsToShow = array(); // holds the actual columns to be shown
 	$columnsToShowCount = array(); // holds the total spaces per column
	foreach ($order as $column) {
		if ( array_key_exists($column,$includeCall) && ($total = _xtcJdocCount($doc,$includeCall[$column])) > 0 ) {
			$columnsToShow[] = $column;
			$columnsToShowCount[$column] = $total;
		}
	}

	if (empty($columnsToShow)) return; // Not columns to show

	 // Get custom column widths and adjust area width accordingly
	$originalAreaWidth = $areaWidth; // To compute % widths
	$widthsToShow = array();	// Holds columns-to-show widths
 	foreach ($columnsToShow as $column) {
		if (array_key_exists($column,$customWidths)) {  // column has custom width
			$customWidth = trim(strtolower($customWidths[$column]));
			$customWidth = (substr($customWidth,-1) == '%') ? floor($originalAreaWidth * (substr($customWidth,0,-1)/100)) : (int) $customWidth;
			if ($customWidth != 0) {
				$areaWidth -= $customWidth;
				$widthsToShow[$column]=$customWidth;
			}
		}
	}
	$freeColumns = count($columnsToShow) - count($widthsToShow);

	$gutters = count($columnsToShow) -1; // get number of gutters
	$areaWidth -= ($gutter * $gutters); // compensate for spacing between columns
	$freeColumnWidth = ($freeColumns) ? $areaWidth/$freeColumns : 0; // default module width

	$grid = ''; $debughtml='';
	if ($debug) $debughtml = "<span style=\"color:#000;background-color:#ddd;\"><b>Area Width:</b> {$originalAreaWidth}px, <b>Gutters:</b> $gutters x {$gutter}px, <b>Free column area:</b> {$areaWidth}px, <b>Free column width:</b> {$freeColumnWidth}px</span><div style=\"clear:both\"></div>";
	while ($columnsToShow) {
		$column=array_shift($columnsToShow);
		$width = (array_key_exists($column,$widthsToShow)) ? $widthsToShow[$column] : floor($freeColumnWidth);
		$width -= (int) $columnPad;
		if (!$grid && !$columnsToShow) { $class = 'singlecolumn'; }
		elseif (!$grid && $columnsToShow) { $class = 'firstcolumn'; }
		elseif (! $columnsToShow) { $class = 'lastcolumn'; }
		else { $class = 'centercolumn'; }
		if ($columnClass) $class .= ' '.$columnClass;

		$style='float:left;';
		if ($width) $style .= ' width:'.$width.'px;';
		if (!empty($columnsToShow) && $gutter) $style .= ' margin-right:'.$gutter.'px;';

		$grid .= '<div id="'.$column.'" class="xtcGrid '.$class.'" style="'.$style.'">';
		$grid .= _xtcJdocRender($doc,$includeCall[$column],$columnsToShowCount[$column]);
		$grid .= '</div>';

		if ($debug) {
			switch ($debug) {
				case 1: // Show column area only
					$debughtml .= '<div id="'.$column.'" class="'.$class.'" style="'.$style.';background-color:'.sprintf('#%02X%02X%02X;', rand(128,250),rand(128,250),rand(128,250)).'">';
					$debughtml .= "<span style=\"color:#000\"><b>Column:</b> $column<br/><b>Class:</b> $class<br/><b>Style:</b> ".htmlentities($style)."<br><b>Areas to show:</b> ".$columnsToShowCount[$column]."<br/><b>Raw code:</b><br>".htmlentities($includeCall[$column])."</span>";
					$debughtml .= '</div>';
				break;
				case 2: // Show boxes instead of jdoc output
					$hold = $includeCall[$column];
					foreach ($matches[0] as $jdocCall) {
						$hold = str_replace($jdocCall,htmlentities($jdocCall),$hold);
					}
					$debughtml .= '<div id="'.$column.'" class="'.$class.'" style="'.$style.';background-color:'.sprintf('#%02X%02X%02X;', rand(128,250),rand(128,250),rand(128,250)).'">';
					$debughtml .= "<span style=\"color:#000\"><b>Column:</b> $column<br/><b>Class:</b> $class<br/><b>Style:</b> ".htmlentities($style)."<br><b>jdoc calls:</b> $jdocs<br>".htmlentities($hold)."</span>";
					$debughtml .= '</div>';
				break;
			}
		}
	}
	return ( $debug ? $debughtml : $grid);
}

function xtcFluidGrid($areaWidth,$gutter,$order,$includeCall,$customWidths=array(),$columnClass='',$columnPad=0,$debug=false) {
	$xtc =& $GLOBALS['xtc'];
        
	$order = empty($order) ? array_keys($includeCall) : explode(",",$order);
	if (!is_array($customWidths)) settype($customWidths,"array");
	if (isset($xtc->debug) && $xtc->debug) $debug = true;

	$doc = JFactory::getDocument();

 	// Get list of columns to show based on joomla content count per column
 	$columnsToShow = array(); // holds the actual columns to be shown
 	$columnsToShowCount = array(); // holds the total spaces per column
	foreach ($order as $column) {
		if ( array_key_exists($column,$includeCall) && ($total = _xtcJdocCount($doc,$includeCall[$column])) > 0 ) {
			$columnsToShow[] = $column;
			$columnsToShowCount[$column] = $total;
		}
	}

	if (empty($columnsToShow)) return; // Not columns to show

	 // Get custom column widths and adjust area width accordingly
	$originalAreaWidth = $areaWidth; // To compute % widths
	$widthsToShow = array();	// Holds columns-to-show widths
 	foreach ($columnsToShow as $column) {
		if (array_key_exists($column,$customWidths)) {  // column has custom width
			$customWidth = trim(strtolower($customWidths[$column]));
			$customWidth = (substr($customWidth,-1) == '%') ? floor($originalAreaWidth * (substr($customWidth,0,-1)/100)) : (int) $customWidth;
			if ($customWidth != 0) {
				$areaWidth -= $customWidth;
				$widthsToShow[$column]=$customWidth;
			}
		}
	}
	$freeColumns = count($columnsToShow) - count($widthsToShow);

	$gutters = count($columnsToShow) -1; // get number of gutters
	$areaWidth -= ($gutter * $gutters); // compensate for spacing between columns
	$freeColumnWidth = ($freeColumns) ? $areaWidth/$freeColumns : 0; // default module width

	$grid = ''; $debughtml='';
	if ($debug) $debughtml = "<span style=\"color:#000;background-color:#ddd;\"><b>Area Width:</b> {$originalAreaWidth}%, <b>Gutters:</b> $gutters x {$gutter}%, <b>Free column area:</b> {$areaWidth}%, <b>Free column width:</b> {$freeColumnWidth}%</span><div style=\"clear:both\"></div>";
	while ($columnsToShow) {
		$column=array_shift($columnsToShow);
		$width = (array_key_exists($column,$widthsToShow)) ? $widthsToShow[$column] : $freeColumnWidth;
		$width -= $columnPad;
		if (!$grid && !$columnsToShow) { $class = 'singlecolumn'; }
		elseif (!$grid && $columnsToShow) { $class = 'firstcolumn'; }
		elseif (! $columnsToShow) { $class = 'lastcolumn'; }
		else { $class = 'centercolumn'; }
		if ($columnClass) $class .= ' '.$columnClass;

		$style='float:left;';
		if ($width) $style .= ' width:'.$width.'%;';
		if (!empty($columnsToShow) && $gutter) $style .= ' margin-right:'.$gutter.'%;';

		$grid .= '<div id="'.$column.'" class="xtcFluidGrid '.$class.'" style="'.$style.'">';
		$grid .= _xtcJdocRender($doc,$includeCall[$column],$columnsToShowCount[$column]);
		$grid .= '</div>';

		if ($debug) {
			switch ($debug) {
				case 1: // Show column area only
					$debughtml .= '<div id="'.$column.'" class="'.$class.'" style="'.$style.';background-color:'.sprintf('#%02X%02X%02X;', rand(128,250),rand(128,250),rand(128,250)).'">';
					$debughtml .= "<span style=\"color:#000\"><b>Column:</b> $column<br/><b>Class:</b> $class<br/><b>Style:</b> ".htmlentities($style)."<br><b>Areas to show:</b> ".$columnsToShowCount[$column]."<br/><b>Raw code:</b><br>".htmlentities($includeCall[$column])."</span>";
					$debughtml .= '</div>';
				break;
				case 2: // Show boxes instead of jdoc output
					$hold = $includeCall[$column];
					foreach ($matches[0] as $jdocCall) {
						$hold = str_replace($jdocCall,htmlentities($jdocCall),$hold);
					}
					$debughtml .= '<div id="'.$column.'" class="'.$class.'" style="'.$style.';background-color:'.sprintf('#%02X%02X%02X;', rand(128,250),rand(128,250),rand(128,250)).'">';
					$debughtml .= "<span style=\"color:#000\"><b>Column:</b> $column<br/><b>Class:</b> $class<br/><b>Style:</b> ".htmlentities($style)."<br><b>jdoc calls:</b> $jdocs<br>".htmlentities($hold)."</span>";
					$debughtml .= '</div>';
				break;
			}
		}
	}
	return ( $debug ? $debughtml : $grid);
}

function xtcSemanticGrid($columnArray,$order='',$columnClass='') {

	$xtc =& $GLOBALS['xtc'];

	$order = empty($order) ? array_keys($columnArray) : explode(",",$order);

	$doc = JFactory::getDocument();

 	// Get list of columns to show based on joomla content count per column
 	$columnsToShow = array(); // holds the actual columns to be shown
 	$columnsToShowCount = array(); // holds the total spaces per column
	foreach ($order as $column) {
		if ( array_key_exists($column,$columnArray) && ($total = _xtcJdocCount($doc,$columnArray[$column])) > 0 ) {
			$columnsToShow[] = $column;
			$columnsToShowCount[$column] = $total;
		}
	}

	if (empty($columnsToShow)) return; // Not columns to show

	$cols = count($columnsToShow); $col = 1;
	$grid = '<div class="xtcgrid">';
	while ($columnsToShow) {
		$column=array_shift($columnsToShow);
		if (!$grid && !$columnsToShow) { $class = 'singlecolumn'; }
		elseif (!$grid && $columnsToShow) { $class = 'firstcolumn'; }
		elseif (! $columnsToShow) { $class = 'lastcolumn'; }
		else { $class = 'centercolumn'; }
		if ($columnClass) $class = $columnClass.' '.$class;

		$grid .= '<div id="xtcSemanticGrid '.$column.'" class="'.$class.' cols-'.$cols.' column-'.$col++.'">';
		$grid .= _xtcJdocRender($doc,$columnArray[$column],$columnsToShowCount[$column]);
		$grid .= '</div>';
	}
	$grid .= '</div>';
	return $grid;
}

function xtcCssGrid($columnArray,$order='',$columnClass='') {

	$xtc =& $GLOBALS['xtc'];

	$order = empty($order) ? array_keys($columnArray) : explode(",",$order);

	$doc = JFactory::getDocument();

 	// Get list of columns to show based on joomla content count per column
 	$columnsToShow = array(); // holds the actual columns to be shown
 	$columnsToShowCount = array(); // holds the total spaces per column
	foreach ($order as $column) {
		if ( array_key_exists($column,$columnArray) && ($total = _xtcJdocCount($doc,$columnArray[$column])) > 0 ) {
			$columnsToShow[] = $column;
			$columnsToShowCount[$column] = $total;
		}
	}

	if (empty($columnsToShow)) return; // Not columns to show

	$grid = '<div style="display:table;width:100%" class="gridtable" ><div style="display:table-row" class="gridrow">';
	$cols = count($columnsToShow); $col = 1;
	while ($columnsToShow) {
		$column=array_shift($columnsToShow);
		if (!$grid && !$columnsToShow) { $class = 'singlecolumn'; }
		elseif (!$grid && $columnsToShow) { $class = 'firstcolumn'; }
		elseif (! $columnsToShow) { $class = 'lastcolumn'; }
		else { $class = 'centercolumn'; }
		if ($columnClass) $class = $columnClass.' '.$class;

		$grid .= '<div id="'.$column.'" style="display:table-cell" class="xtcCssGrid '.$class.' cols-'.$cols.' column-'.$col++.'">';
		$grid .= _xtcJdocRender($doc,$columnArray[$column],$columnsToShowCount[$column]);
		$grid .= '</div>';
//		if ($columnsToShow) {
//			$grid .= '<div class="gridgutter"></div>';
//		}
	}
	$grid .= '</div></div>';
	return $grid;
}

function xtcBootstrapGrid($columnArray,$order='',$customSpans='',$columnClass='') {

	$xtc =& $GLOBALS['xtc'];

	$order = empty($order) ? array_keys($columnArray) : explode(",",$order);

	$doc = JFactory::getDocument();

 	// Get list of columns to show based on joomla content count per column
 	$columnsToShow = array(); // holds the actual columns to be shown
 	$columnsToShowCount = array(); // holds the total spaces per column
	foreach ($order as $column) {
		if ( array_key_exists($column,$columnArray) && ($total = _xtcJdocCount($doc,$columnArray[$column])) > 0 ) {
			$columnsToShow[] = $column;
			$columnsToShowCount[$column] = $total;
		}
	}

	if (empty($columnsToShow)) return; // Not columns to show

	$cols = count($columnsToShow); $col = 1;
	
	//Resolve custom span classes
	$spaces = 12; $cs = 0;
	if (is_array($customSpans)) {
		$cs = count($customSpans);
		foreach ($customSpans as $c => $s) { $spaces -= intval(str_replace('span','',$s)); }
	}
	else {
		$customSpans = array();
	}

	$colsLeft = $cols - $cs;
	if ($spaces && $colsLeft) {
		$spanClass = floor($spaces / ($cols - $cs));
		if ($spanClass == 0) $spanClass = 1;
	}
	else { $spanClass == 1; }

	$grid = '';
	while ($columnsToShow) {
		$column=array_shift($columnsToShow);
		if (!$grid && !$columnsToShow) { $class = 'singlecolumn'; }
		elseif (!$grid && $columnsToShow) { $class = 'firstcolumn'; }
		elseif (! $columnsToShow) { $class = 'lastcolumn'; }
		else { $class = 'centercolumn'; }
		if ($columnClass) $class = $columnClass.' '.$class;

		$span = array_key_exists($column,$customSpans) ? $customSpans[$column] : 'span'.$spanClass;
		$grid .= '<div id="'.$column.'" class="xtcBootstrapGrid '.$span.' '.$class.' cols-'.$cols.' column-'.$col++.'">';
		$grid .= _xtcJdocRender($doc,$columnArray[$column],$columnsToShowCount[$column]);
		$grid .= '</div>';
	}

	return '<div class="row-fluid">'.$grid.'</div>';
}

// Get number of Joomla positions with content from a string/array of jdoc calls
function _xtcJdocCount(&$doc,$includeCalls) {
	$matches = array();
	if (!is_array($includeCalls)) $includeCalls = array($includeCalls);

	$total = 0;
	foreach ($includeCalls as $includeCall) {

		$jdocs = preg_match_all('/<jdoc:include\ type="([^"]+)" (.*)\/>/iU', $includeCall, $matches);
		$count = count($matches[1]);
	
		for ($i = 0; $i < $count; $i++) {
			$attribs = JUtility::parseAttributes( $matches[2][$i] );
			$type  = $matches[1][$i];
			$name  = isset($attribs['name']) ? $attribs['name'] : null;
	
			switch ($type) {
				case 'component';
					$total += xtcCanShowComponent() ? 1 : 0;
				break;
				case 'message';
					$total += strlen($doc->getBuffer($type,$name,array())) > 0 ? 1 : 0;
				break;
				default: // modules
					$total += ($doc->getBuffer($type,$name,array()) === false) ? 0 : count(JModuleHelper::getModules($name));
				break;
			}
		}
	}
	return $total;
}

function _xtcJdocRender(&$doc,$includeCalls,&$total) { // Resolves jdoc calls in a string/array

	$matches = array();
	if (!is_array($includeCalls)) $includeCalls = array($includeCalls);

	$idx = 0;
	$output = '';

	foreach ($includeCalls as $includeCall) {

		$jdocs = preg_match_all('/<jdoc:include\ type="([^"]+)" (.*)\/>/iU', $includeCall, $matches);
		$count = count($matches[1]);
		$jdocsCount = 0;
	
		for ($i = 0; $i < $count; $i++) {
			$attribs = JUtility::parseAttributes( $matches[2][$i] );
			$type  = $matches[1][$i];
			$name  = isset($attribs['name']) ? $attribs['name'] : null;
			$jdocCall = $matches[0][$i];
	
			switch ($type) {
				case 'component';
					$buffer = xtcCanShowComponent() ? $doc->getBuffer($type,$name,$attribs) : '';
					if ($idx == 0 && $total == 1) { $class = 'singlearea'; }
					elseif ($idx ==0 && $total > 1) {$class = 'firstarea'; }
					elseif ($idx == ($total - 1)) {$class = 'lastarea'; }
					else {$class = 'centerarea'; }
					if ($buffer) { $buffer = '<div class="'.$class.'">'.$buffer.'</div>'; $idx++; $jdocsCount++; }
				break;
				case 'message';
					$buffer = $doc->getBuffer($type,$name,$attribs);
					if ($idx == 0 && $total == 1) { $class = 'singlearea'; }
					elseif ($idx ==0 && $total > 1) {$class = 'firstarea'; }
					elseif ($idx == ($total - 1)) {$class = 'lastarea'; }
					else {$class = 'centerarea'; }
					if ($buffer) { $buffer = '<div class="'.$class.'">'.$buffer.'</div>'; $idx++; $jdocsCount++; }
				break;
				default: // modules
					$buffer = '';

					$modules = JModuleHelper::getModules($name);
					foreach ($modules as $mod)  {
						if ($mod->position != $name) continue;
						$temp = xtcRenderModule($mod, $attribs);
						if ($idx == 0 && $total == 1) { $class = 'singlearea'; }
						elseif ($idx ==0 && $total > 1) {$class = 'firstarea'; }
						elseif ($idx == ($total - 1)) {$class = 'lastarea'; }
						else {$class = 'centerarea'; }
						if ($temp) {$buffer .= '<div class="'.$class.'">'.$temp.'</div>'; $idx++; $jdocsCount++; }
					}
				break;
			}
			$includeCall = str_replace($jdocCall,$buffer,$includeCall);
		}
		if ($jdocsCount) $output .= $includeCall;
	}
	return $output;
}

function xtcMakeParams($style=0) {
	if (!$style) die;

	jimport('joomla.filesystem.folder');

	$xtc =& $GLOBALS['xtc'];

	// Get parameters from DB, if present
	$db = JFactory::getDBO();
	$db->setQuery( "SELECT params FROM #__template_styles WHERE id=$style");
	$params = $db->loadResult();
	$obj = json_decode($params);

	// Get basic params and info from template xml (override with db)
	$hasLayouts = false;
	$groups = array();

	if ($xml = simplexml_load_file($xtc->templatePath.'/templateDetails.xml')) {
		foreach ($xml->config->fields->fieldset as $fieldset) {
			foreach ($fieldset->field as $field) {
				if ($field['type'] == 'layoutgroups') { $hasLayouts = true; }
				elseif ($field['type'] == 'stylegroups') { $groups[] = $field['group']; }
				if ($field['name']) {
					$parameters[] = (isset($obj->$field['name']))
						? $field['name'].'='.$obj->$field['name']
						: $field['name'].'='.$field['default'];
				}
			}
		}
	}

	// Get XTC parameters (override with db)
	if ($xml = simplexml_load_file($xtc->templatePath.'/XTC/XTC_config.xml')) {
		foreach ($xml->fields->fieldset as $fieldset) {
			foreach ($fieldset->field as $field) {
				if ($field['name']) {
					$parameters[] = (isset($obj->$field['name']))
						? $field['name'].'='.$obj->$field['name']
						: $field['name'].'='.$field['default'];
				}
			}
		}
	}

	// Get Layouts, if present
	if ($hasLayouts) {
		$folders = JFolder::folders($xtc->templatePath.'/layouts');
		foreach ($folders as $folder) {
			if ($xml = simplexml_load_file($xtc->templatePath.'/layouts/'.$folder.'/config.xml')) {
				foreach ($xml->fields->fieldset as $fieldset) {
					foreach ($fieldset->field as $field) {
						if ($field['name']) {
							$parameters[] = '{layout+'.$folder.'}'.$field['name'].'='.$field['default'];
						}
					}
				}
			}
		}
	}

	// Get parameter groups, if present
	$files = JFolder::files($xtc->templatePath.'/parameters','.xml');
	foreach ($groups as $group) {
		foreach ($files as $file) {
		$pathinfo = pathinfo($file);
			if (strtolower($pathinfo['extension']) != 'xml' || substr($file,0,strlen($group)) != $group) continue; // Not the right prefix
			if ($xml = simplexml_load_file($xtc->templatePath.'/parameters/'.$file)) {
				foreach ($xml->fields->fieldset as $fieldset) {
					foreach ($fieldset->field as $field) {
						if ($field['name']) {
							$parameters[] = '{'.$group.'+'.$pathinfo['filename'].'}'.$field['name'].'='.$field['default'];
						}
					}
				}
			}
		}
	}

	// Store & return parameters
	$parameterFile = $xtc->templatePath.'/params_'.$style.'.ini';
	file_put_contents($parameterFile,implode("\n",$parameters));
	return $parameters;
}

function xtcRenderModule($module, $attribs = array()) {
	// based on Joomla function.
	// bypasses module re-rendering done by joomla and goes direct to chrome
	static $chrome;

	if (constant('JDEBUG')) {
		JProfiler::getInstance('Application')->mark('beforeRenderModule '.$module->module.' ('.$module->title.')');
	}

	$option = JRequest::getCmd('option');
	$app	= JFactory::getApplication();

	// Record the scope.
	$scope	= $app->scope;

	// Set scope to component name
	$app->scope = $module->module;

	// Get module parameters
	$params = new JRegistry;
	$params->loadString($module->params);

	// Get module path
	$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
	$path = JPATH_BASE.'/modules/'.$module->module.'/'.$module->module.'.php';

	// Load the module chrome functions
	if (!$chrome) {
		$chrome = array();
	}

	require_once JPATH_THEMES.'/system/html/modules.php';
	$chromePath = JPATH_THEMES.'/'.$app->getTemplate().'/html/modules.php';

	if (!isset($chrome[$chromePath])) {
		if (file_exists($chromePath)) {
			require_once $chromePath;
		}

		$chrome[$chromePath] = true;
	}

	// Make sure a style is set
	if (!isset($attribs['style'])) {
		$attribs['style'] = 'none';
	}

	// Dynamically add outline style
	if (JRequest::getBool('tp') && JComponentHelper::getParams('com_templates')->get('template_positions_display')) {
		$attribs['style'] .= ' outline';
	}

	foreach(explode(' ', $attribs['style']) as $style) {
		$chromeMethod = 'modChrome_'.$style;

		// Apply chrome and render module
		if (function_exists($chromeMethod)) {
			$module->style = $attribs['style'];

			ob_start();
			$chromeMethod($module, $params, $attribs);
			$module->content = ob_get_contents();
			ob_end_clean();
		}
	}


	$frontediting = $app->get('frontediting', 0);
	$user = JFactory::getUser();

	$canEdit = $user->id && $frontediting && !($app->isAdmin() && $frontediting < 2) && $user->authorise('core.edit', 'com_modules');
	$menusEditing = ($frontediting == 2) && $user->authorise('core.edit', 'com_menus');

	if ($app->isSite() && $canEdit && trim($module->content) != '' && $user->authorise('core.edit', 'com_modules.module.' . $module->id))	{

		$editUrl = JUri::base() . 'administrator/index.php?option=com_modules&view=module&layout=edit&id=' . (int) $module->id;

		$count = 0;
		$module->content = preg_replace(
			'/^(\s*<(?:div|span|nav|ul|ol|h\d) [^>]*class="[^"]*)"/',
			'\\1 jmoddiv" data-jmodediturl="' . $editUrl . '" data-jmodtip="'
			.	JHtml::tooltipText(
					JText::_('JLIB_HTML_EDIT_MODULE'),
					htmlspecialchars($module->title) . '<br />' . sprintf(JText::_('JLIB_HTML_EDIT_MODULE_IN_POSITION'), htmlspecialchars($module->position)),
					0
				)
			. '"'
			// And if menu editing is enabled and allowed and it's a menu module, add data attributes for menu editing:
			.	($menusEditing && $module->module == 'mod_menu' ?
					'" data-jmenuedittip="' . JHtml::tooltipText('JLIB_HTML_EDIT_MENU_ITEM', 'JLIB_HTML_EDIT_MENU_ITEM_ID') . '"'
					:
					''
				),
			$module->content,
			1,
			$count
		);
		
		if ($count) {
			// Load once booststrap tooltip and add stylesheet and javascript to head:
			JHtml::_('bootstrap.tooltip');
			JHtml::_('bootstrap.popover');
		
			JHtml::_('stylesheet', 'system/frontediting.css', array(), true);
			JHtml::_('script', 'system/frontediting.js', false, true);
		}
	}

	//revert the scope
	$app->scope = $scope;

	if (constant('JDEBUG')) {
		JProfiler::getInstance('Application')->mark('afterRenderModule '.$module->module.' ('.$module->title.')');
	}

	return $module->content;
}