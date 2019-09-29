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
$app = JFactory::getApplication();
$menu = $app->getMenu()->getActive();
$pageclass = '';

if (is_object($menu))
$pageclass = $menu->params->get('pageclass_sfx');
$pageview = xtcIsFrontpage() ? 'frontpage' : 'innerpage';
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
echo xtcCSS($params->xtctypo,$params->xtcgrid,$params->xtcstyle);

$document->addStyleSheet( $xtc->templateUrl.'css/css3effects.css', 'text/css');

// Get Xtc Menu library
$document->addScript($xtc->templateUrl.'js/xtcMenu.js'); 
$document->addScriptDeclaration("window.addEvent('load', function(){ xtcMenu(null, 'menu', 200, 50, 'h', new Fx.Transition(Fx.Transitions.Cubic.easeInOut), 90, false, false); });");
// Get the Template General Scripts file
//$document->addScript($xtc->templateUrl.'js/scripts.js');
?>
<?php if ($templateParameters->jquery) { JHtml::_('jquery.framework',true); } ?>
<jdoc:include type="head" />
<script src="<?php echo $xtc->templateUrl; ?>js/easing.js.min.js" type="text/javascript"></script>
<script src="<?php echo $xtc->templateUrl; ?>js/xtcScripts.js" type="text/javascript"></script>
<script src="<?php echo $xtc->templateUrl; ?>js/bootstrap.js" type="text/javascript"></script>
</head>
<?php
// End of HEAD
// Start of BODY
?>
<body id="bttop" class="<?php echo $pageview;?> <?php echo $gridParams->stickyheader;?> <?php echo $pageclass ? htmlspecialchars($pageclass) : 'default'; ?>">

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
							<jdoc:include type="modules" name="menuright2" style="xtc"/>
						</div>
					<?php endif; ?> 
					<?php if ($this->countModules('menuright1')) : ?>
						<div id="menuright1">                           
							<jdoc:include type="modules" name="menuright1" style="xtc"/>
						</div>
					<?php endif; ?> 
				</div>
			<?php endif; ?> 
			<div id="menuwrap"><div id="menu" class="clearfix hd8 <?php echo $gridParams->menustyle;?>">
				<jdoc:include type="modules" name="menubarleft" style="raw" />
			</div></div>
		</div> 
	</div>


<?php
			// Draw the regions in the specified order
			$regioncfg = $gridParams->regioncfg;
			foreach (explode(",",$regioncfg) as $region) {
				if ($region == '') continue;
				require 'layout_includes/region'.$region.'.php';
			}
?>
<?php
	
	$areaWidth = $tmplWidth;
	$gutter = 0;	
	$order = 'footer,legals';
	$columnArray = array();
	$columnArray['footer'] = '<jdoc:include type="modules" name="footer" style="xtc" />';
	$columnArray['legals'] = '<jdoc:include type="modules" name="legals" style="xtc" />';
	$customWidths = '';
	$columnClass = '';
	$columnPadding = '';
	$debug = '';
	$footer_legals = xtcBootstrapGrid($columnArray,$order,'',$columnClass);
        	if ($footer_legals) {
		echo '<div id="footerwrap" class="xtc-bodygutter">';
                echo '<div id="footerwrappad" class="xtc-wrapperpad">';
		echo '<div id="footerpad" class="row-fluid xtc-wrapper">'.$footer_legals.'</div>';
	        echo '</div>';
                echo '</div>';
    
	}
?>
<jdoc:include type="modules" name="debug" />
</body>
</html>
<?php
// End of BODY
?>