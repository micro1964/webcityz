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
jimport('joomla.filesystem.file');
?>
<div class="k2store_quicktips">
<?php if(!JFile::exists(JPATH_ADMINISTRATOR.'/components/com_k2/k2.php')):?>
<div class="alert alert-block alert-danger">
	<strong>
	<?php echo JText::_('K2STORE_K2_NOT_PRESENT_ALERT'); ?>
	</strong>
</div>
<?php endif;?>
<?php if(K2StoreVersion::getPreviousVersion() == '3.0.3'): ?>
<div class="alert alert-block alert-danger">
	<strong>
	<?php echo JText::_('K2STORE_MIGRATE_WARNING'); ?>
	</strong>
	<br />
	<a class="btn btn-info" href="http://k2store.org/support/documentation/item/50-migrate-from-k2store-303-to-35.html" target="_blank"><?php echo JText::_('K2STORE_QUICK_TIPS_READ_MORE'); ?></a>
	<a class="btn btn-success" href="http://k2store.org/support/documentation/item/50-migrate-from-k2store-303-to-35.html" target="_blank"><?php echo JText::_('K2STORE_QUICK_TIPS_WATCH_VIDEO'); ?></a>
</div>
<?php endif; ?>

<?php if(K2StoreVersion::getPreviousVersion() == '3.0.3' && K2STORE_ATTRIBUTES_MIGRATED==false) : ?>
<div class="alert alert-block alert-danger">
	<strong>
	<?php echo JText::_('K2STORE_ATTRIBUTE_MIGRATION_ALERT'); ?>
	</strong>
</div>
		<div class="alert alert-info">
			<strong>
			<?php echo JText::_('K2STORE_MIGRATE_ATTRIBUTES_CPANEL_INFO')?>
			</strong>
		</div>
<?php endif; ?>
<h3><?php echo JText::_('K2STORE_QUICK_TIPS'); ?>
</h3>
<ol>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_STORE'); ?></li>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_CURRENCY'); ?></li>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_ADMIN_EMAIL'); ?></li>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_PRICE_DISPLAY'); ?></li>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_PRICE_TIPS'); ?></li>
	<li><?php echo JText::_('K2STORE_QUICK_TIPS_SET_UP_DONE'); ?></li>

</ol>
<span class="learn_more"><a href="http://k2store.org" target="_blank"><?php echo JText::_('K2STORE_QUICK_TIPS_READ_MORE'); ?></a> </span>

</div>
