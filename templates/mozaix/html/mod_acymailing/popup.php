<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die('Restricted access');
?><div class="acymailing_module<?php echo $params->get('moduleclass_sfx')?>" id="acymailing_module_<?php echo $formName; ?>">
<?php
	if(!empty($mootoolsIntro)) echo '<p class="acymailing_mootoolsintro">'.$mootoolsIntro.'</p>'; ?>
	<div class="acymailing_mootoolsbutton">
		<?php
		 	$link = "rel=\"{handler: 'iframe', size: {x: ".$params->get('boxwidth',250).", y: ".$params->get('boxheight',200)."}}\" class=\"modal acymailing_togglemodule\"";
		 	$href=acymailing_completeLink('sub&task=display&formid='.$module->id,true);
		?>
		<p><a <?php echo $link; ?> id="acymailing_togglemodule_<?php echo $formName; ?>" href="<?php echo $href;?>"><?php echo $mootoolsButton ?></a></p>
	</div>
</div>
