<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if (version_compare(JVERSION, '3.0', 'lt')): ?>
<div class="icon-wrapper">
   <div class="icon">
	    <a rel="{handler: 'iframe', size: {x: 850, y: 500}, onClose: function() {}}"
	 href="index.php?option=com_config&view=component&component=com_k2store&tmpl=component" class="modal">
	     <img alt="<?php echo JText::_('K2STORE_OPTIONS'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/config.png" />
		    <span><?php echo JText::_('K2STORE_OPTIONS'); ?></span>
	    </a>
    </div>
  </div>
 <?php else: ?>
<div class="icon-wrapper">
   <div class="icon">
   		<?php
   		$return = base64_encode('index.php?option=com_k2store&view=cpanel');
   		$url='index.php?option=com_config&view=component&component=com_k2store&path=&return='.$return;
   		?>
	    <a href="<?php echo $url; ?>">
	     <img alt="<?php echo JText::_('K2STORE_OPTIONS'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/config.png" />
		    <span><?php echo JText::_('K2STORE_OPTIONS'); ?></span>
	    </a>
    </div>
  </div>
<?php endif; ?>

<div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&amp;view=orders">
		    <img alt="<?php echo JText::_('K2STORE_ORDERS'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/orders.png" />
		    <span><?php echo JText::_('K2STORE_ORDERS'); ?></span>
	    </a>
    </div>
  </div>

  <div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&amp;view=options">
		    <img alt="<?php echo JText::_('K2STORE_PRODUCT_GLOBAL_OPTIONS'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/product_option.png" />
		    <span><?php echo JText::_('K2STORE_PRODUCT_GLOBAL_OPTIONS'); ?></span>
	    </a>
    </div>
  </div>


<div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&amp;view=taxprofiles">
		    <img alt="<?php echo JText::_('K2STORE_TAX_PROFILES'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/taxprofiles.png" />
		    <span><?php echo JText::_('K2STORE_TAX_PROFILES'); ?></span>
	    </a>
    </div>
  </div>

<div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&view=countries">
		    <img alt="<?php echo JText::_('K2STORE_COUNTRIES'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/countries.png" />
		    <span><?php echo JText::_('K2STORE_COUNTRIES'); ?></span>
	    </a>
    </div>
  </div>

<div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&view=zones">
		    <img alt="<?php echo JText::_('K2STORE_ZONES'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/zones.png" />
		    <span><?php echo JText::_('K2STORE_ZONES'); ?></span>
	    </a>
    </div>
  </div>

<div class="icon-wrapper">
    <div class="icon">
	    <a href="index.php?option=com_k2store&amp;view=shippingmethods">
		    <img alt="<?php echo JText::_('K2STORE_SHIPPING_METHODS'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/shipping_methods.png" />
		    <span><?php echo JText::_('K2STORE_SHIPPING_METHODS'); ?></span>
	    </a>
    </div>
  </div>


<div class="icon-wrapper">
    <div class="icon">
	    <a class="modal" rel="{handler: 'iframe', size: {x: 1040, y: 600}}" href="http://k2store.org" title="<?php echo JText::_('K2STORE_NEWS_ON_K2STORE'); ?>">
		    <img alt="<?php echo JText::_('K2STORE_NEWS_ON_K2STORE'); ?>" src="<?php echo JURI::root();?>media/k2store/images/dashboard/info.png" />
		    <span><?php echo JText::_('K2STORE_INFO'); ?></span>
	    </a>
    </div>
  </div>

    <?php echo LiveUpdate::getIcon(); ?>

