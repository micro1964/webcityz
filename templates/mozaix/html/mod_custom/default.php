<?php
/*
Departure template for Joomla!
Commercial Software
Copyright 2013 joomlaxtc.com
All Rights Reserved
www.joomlaxtc.com
*/

defined('_JEXEC') or die;
?>


<?php if ($params->get('backgroundimage')): ?> <div style="background-image:url(<?php echo $params->get('backgroundimage');?>)"><?php endif;?> 
	<?php echo $module->content;?>
<?php if ($params->get('backgroundimage')): ?></div><?php endif;?>
