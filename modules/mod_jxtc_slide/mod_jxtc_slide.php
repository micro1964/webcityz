<?php
/*
	JoomlaXTC slide module

	version 1.2.1
	
	Copyright (C) 2009-2015  Monev Software LLC.	All Rights Reserved.
	
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

jimport( 'joomla.filesystem.folder' );
JHtml::_('behavior.framework', true);

$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_jxtc_slide/css/style.css','text/css');
$doc->addScript("modules/mod_jxtc_slide/js/southslide.js");

/* Module data */ 
$site = JURI::base();
$module_path = 'modules/mod_jxtc_slide';
$jxtc = uniqid('jxtc');

/* Module Params */
$panelW = $params->get('panelWidth','900');
$panelH = $params->get('panelHeight','330');
$panelBg = $params->get('panelBg','000000');
$panelOpacity = $params->get('panelOpacity',90);
$panelDir = $params->get('panelDir','top');
$panelSpeedIn = $params->get('panelSpeedIn',1000);
$panelTransTypeIn = $params->get('panelTransTypeIn','linear');
$panelTransEaseIn = $params->get('panelTransEaseIn','easeIn');
$panelSpeedOut = $params->get('panelSpeedOut',500);
$panelCloseFx = $params->get('panelCloseFx','so');
$closeText = $params->get('closeText','Close');
$panelTransTypeOut = $params->get('panelTransTypeOut','linear');
$panelTransEaseOut = $params->get('panelTransEaseOut','easeOut');
$boxesLayoutOut = $params->get('boxesLayout','lr');
$boxesAnimOrder = $params->get('boxesAnimOrder','l');
/* Box Left */
$boxLeftWidth = $params->get('boxLeftWidth','380');
$boxLeftHeight = $params->get('boxLeftHeight','300');
$boxLeftFx = $params->get('boxLeftFx','so');
$boxLeftDir = $params->get('boxLeftDir','left');
$boxLeftSpeed = $params->get('boxLeftSpeed',1000);
$boxLeftTransType = $params->get('boxLeftTransType','linear');
$boxLeftTransEase = $params->get('boxLeftTransEase','easeIn');
$boxLeft = $params->get('boxLeft','');
/* Box Right */
$boxRightWidth = $params->get('boxRightWidth','380');
$boxRightHeight = $params->get('boxRightHeight','300');
$boxRightFx = $params->get('boxRightFx','so');
$boxRightDir = $params->get('boxRightDir','right');
$boxRightSpeed = $params->get('boxRightSpeed',1000);
$boxRightTransType = $params->get('boxRightTransType','linear');
$boxRightTransEase = $params->get('boxRightTransEase','easeIn');
$boxRight = $params->get('boxRight','');
/* Trigger */
$trigger = $params->get('trigger','Click me!');
$triggerout = $params->get('triggerout','Log Out!');

$template = $params->get('template',-1);
if ($template != -1 && JFolder::exists(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template)) {
	$trigger = JFile::read(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template.'/trigger.html');
	$triggerout = JFile::read(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template.'/triggerout.html');
	$boxleft = JFile::read(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template.'/leftbox.html');
	$boxright = JFile::read(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template.'/rightbox.html');
  if (file_exists(JPATH_ROOT.'/modules/mod_jxtc_slide/templates/'.$template.'/template.css')) {
      $doc->addStyleSheet(JURI::root().'modules/mod_jxtc_slide/templates/'.$template.'/template.css', 'text/css');
  }
}
$plugins = $params->get('plugins',1);

$user = JFactory::getUser();
if ($user->id){
	$trigger = $triggerout;
}

if($panelTransTypeIn == 'linear') $panelTransEaseIn = '';
if($panelTransTypeIn && $panelTransEaseIn) $panelTransEaseIn = ".".$panelTransEaseIn;

if($panelTransTypeOut == 'linear') $panelTransEaseOut = '';
if($panelTransTypeOut && $panelTransEaseOut) $panelTransEaseOut = ".".$panelTransEaseOut;

if($boxLeftTransType == 'linear') $boxLeftTransEase = '';
if($boxLeftTransType && $boxLeftTransEase) $boxLeftTransEase = ".".$boxLeftTransEase;

if($boxRightTransType == 'linear') $boxRightTransEase = '';
if($boxRightTransType && $boxRightTransEase) $boxRightTransEase = ".".$boxRightTransEase;

/* Use class methods */
/* $categories = ModJxtcGalHelper::listCategories($imagesSrc); */

/* Add scripts declarations */
	if($boxesLayoutOut == 'l') $panels = "{s1:'sd1'}";
	if($boxesLayoutOut == 'r') $panels = "{s2:'sd2'}";
	if($boxesLayoutOut == 'lr') $panels = "{s1:'sd1', s2:'sd2'}";
	
  /* Add javscript instances */
	$doc->addScriptDeclaration("window.addEvent('domready', function(){ 
	var southslide$jxtc = new southslide( '$jxtc', $panels , {panelW: $panelW, panelH: $panelH, panelBg: '$panelBg', panelOpacity: $panelOpacity, panelDir: '$panelDir', panelSpeedIn: $panelSpeedIn, panelTranIn: new Fx.Transition(Fx.Transitions.".$panelTransTypeIn.$panelTransEaseIn."), panelOutAnim: '$panelCloseFx', panelSpeedOut: $panelSpeedOut, panelTranOut: new Fx.Transition(Fx.Transitions.".$panelTransTypeOut.$panelTransEaseOut."), closeText: '$closeText', boxesAnimOrder: '$boxesAnimOrder', boxLW: $boxLeftWidth, boxLH: $boxLeftHeight, boxLeftDir: '$boxLeftDir', boxLeftSpeed: $boxLeftSpeed, boxLeftFx:'$boxLeftFx', boxLeftTran: new Fx.Transition(Fx.Transitions.".$boxLeftTransType.$boxLeftTransEase."), boxRW: $boxRightWidth, boxRH: $boxRightHeight, boxRightDir: '$boxRightDir', boxRightSpeed: $boxRightSpeed, boxRightFx:'$boxRightFx', boxRightTran: new Fx.Transition(Fx.Transitions.".$boxRightTransType.$boxRightTransEase.") } ); 
});");


/* Get the login module Markup */

$moduleFinal = JModuleHelper::getModule( 'mod_login' );
$modLoginRender = JModuleHelper::renderModule( $moduleFinal );

$moduleFinalHTML = '<div class="southslide">'.$trigger.'</div>';
$moduleFinalHTML .= '<div id="sd1" style="display:none;"><div>';
$moduleFinalHTML .= str_replace( '{loginform}', $modLoginRender, $boxLeft );
$moduleFinalHTML .= '</div></div>';
$moduleFinalHTML .= '<div id="sd2" style="display:none;"><div>';
$moduleFinalHTML .= str_replace( '{loginform}', $modLoginRender, $boxRight );
$moduleFinalHTML .= '</div></div>';

if ($plugins) {
	JPluginHelper::importPlugin('content');
	$contentconfig = JComponentHelper::getParams('com_content');
	$dispatcher = JDispatcher::getInstance();
	$item = new stdClass();
	$item->text = $moduleFinalHTML;
	$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$contentconfig, 0 ));
	$moduleFinalHTML = $item->text;
}
?>
<div id="<?php echo $jxtc; ?>">
	<div class="login_open_wrap">
	  <div class="slide_wrap">
	    <?php echo $moduleFinalHTML; ?>
	  </div>
	</div>
</div>