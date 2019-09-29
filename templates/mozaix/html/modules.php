<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
function modChrome_xtc($module, $params, $attribs) {
	$content         = $module->content;
	$suffix          = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
    if ($module->showtitle)  {
			$moduleClass = 'title-on';
		} else { 
	    $moduleClass = 'title-off';
    }


?>
<div class="module <?php echo $moduleClass;?>  <?php echo  $suffix; ?>">
<?php if ($module->showtitle != 0) { ?>
  <?php $modtitle = explode("||", $module->title, 2); ?>
  <h3 class="moduletitle">
    <?php if(!empty($modtitle[0])) { ?><span class="first_word"><?php echo $modtitle[0];?></span><?php } ?>
    <?php if(!empty($modtitle[1])) {?><span class="rest"><?php echo $modtitle[1]; ?></span><?php }?>
</h3>

<?php } ?>
  <div class="modulecontent"> <?php echo $content; ?> </div>
</div>
<?php } ?>
