<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die;
JHtml::_('behavior.framework');
$document =JFactory::getDocument();
$app =JFactory::getApplication();
$user =JFactory::getUser();
$params = $templateParameters->group->$layout; // We got $layout from the index.php
// Use the Grid parameters to compute the main columns width
$grid = $params->xtcgrid;
$style = $params->xtcstyle;
$typo = $params->xtctypo;

//Group parameters from grid.xml
$gridParams = $templateParameters->group->$grid;
$styleParams = $templateParameters->group->$style;
$typoParams = $templateParameters->group->$typo;
$tmplWidth = 100;
// Start of HEAD
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="<?php echo $xtc->templateUrl; ?>css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $xtc->templateUrl; ?>css/bootstrap-responsive.min.css" type="text/css" />
<?php
// Include the CSS files using the groups as defined in the layout parameters
echo xtcCSS($params->xtcstyle,$params->xtcgrid,$params->xtctypo);
$document->addStyleSheet( $xtc->templateUrl.'css/css3effects.css', 'text/css');

// Get Xtc Menu library
//$document->addScript($xtc->templateUrl.'js/xtcMenu.js'); 
//$document->addScriptDeclaration("window.addEvent('load', function(){ xtcMenu(null, 'menu', 200, 50, 'h', new Fx.Transition(Fx.Transitions.Cubic.easeInOut), 90, false, false); });");
// Get the Template General Scripts file
//$document->addScript($xtc->templateUrl.'js/scripts.js');
?>
<jdoc:include type="head" />
<?php if ($templateParameters->jquery) { ?>
	<script type="text/javascript"> if (window.jQuery == undefined) document.write( unescape('%3Cscript src="//code.jquery.com/jquery-latest.min.js" type="text/javascript"%3E%3C/script%3E') );</script>
	<script type="text/javascript">jQuery.noConflict();</script>
<?php } ?>
<script src="<?php echo $xtc->templateUrl; ?>js/bootstrap.js" type="text/javascript"></script>
</head>
<?php
// End of HEAD
// Start of BODY
?>
<body class="mobile">
    <div id="xtc-mobilesitewrap">
       
<div id="headerwrap" class="xtc-bodygutter <?php echo $gridParams->stickyheader;?>">
		<div id="header" class="xtc-wrapper clearfix">
			<div id="logo" class="hd2">
				<a class="hideTxt" href="index.php">
					<?php echo $app->getCfg('sitename');?>
				</a>
			</div>
			<?php if ($this->countModules('menuright2') || $this->countModules('menuright1')) : ?>
				<div id="menu2" class="hd2">
					<?php if ($this->countModules('menuright2')) : ?>
						<div id="menuright2">                           
							<jdoc:include type="modules" name="menuright2" style="none"/>
						</div>
					<?php endif; ?> 
					<?php if ($this->countModules('menuright1')) : ?>
						<div id="menuright1">                           
							<jdoc:include type="modules" name="menuright1" style="none"/>
						</div>
					<?php endif; ?> 
				</div>
			<?php endif; ?> 
			<div id="menuwrap"><div id="menu" class="clearfix hd8 <?php echo $gridParams->menustyle;?>">
				<jdoc:include type="modules" name="menubarleft" style="raw" />
			</div></div>
		</div> 
	</div>
<div id="mozaixmobile">
<?php
$messages = JFactory::getApplication()->getMessageQueue();
if ( $messages ) {
?>
	 <div id="message" class="clearfix">
	   <jdoc:include type="message" />
	 </div>
<?php } ?>
<?php if ($this->countModules('mobile1')){ ?>
		<div id="mobile1" class="clearfix mobilepad"><jdoc:include type="modules" name="mobile1" style="xtc"  /></div>
<?php } ?>
<?php if ($this->countModules('mobile2')){ ?>
		<div id="mobile2" class="clearfix mobilepad"><jdoc:include type="modules" name="mobile2" style="xtc"  /></div>
<?php } ?>
<?php if ($this->countModules('mobile3')){ ?>
		<div id="mobile3" class="clearfix mobilepad"><jdoc:include type="modules" name="mobile3" style="xtc"  /></div>
<?php } ?>
<?php if ( xtcCanShowComponent()) { 
	echo '<div id="region3" class="mobilepad"><div id="component" class="mobilecomponent clearfix"><jdoc:include type="component" /></div></div>'; 
} ?>
<?php if ($this->countModules('mobile4')){ ?>
		<div id="mobile4" class="clearfix mobilepad"><jdoc:include type="modules" name="mobile4" style="xtc"  /></div>
<?php } ?>
                </div> 
<?php if ($this->countModules('mobilefooter')){ ?>
		<div id="footerwrap">
		<div id="footerpad" class="clearfix"><jdoc:include type="modules" name="mobilefooter" style="xtc"  /></div>
	    </div>	
<?php } ?>
</div>
         
</body>
</html>
<?php
// End of BODY
?>
