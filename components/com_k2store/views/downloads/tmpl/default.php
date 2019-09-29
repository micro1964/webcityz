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
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once(JPATH_COMPONENT.'/helpers/cart.php');
require_once(JPATH_COMPONENT.'/helpers/modules.php');

?>
<?php echo K2StoreHelperModules::loadposition('k2store-download-top'); ?>

<?php if(count($this->files)):?>
<h2><?php echo JText::_('K2STORE_DOWNLOADS'); ?></h2>
<table class="k2store_table_downloads table table-striped table-bordered">
	<thead>
		<th><?php echo JText::_('K2STORE_ORDER_ID'); ?></th>
		<th><?php echo JText::_('K2STORE_PRODUCT_NAME'); ?></th>
		<th><?php echo JText::_('K2STORE_FILE_NAME'); ?></th>
		<th><?php echo JText::_('K2STORE_FILE_DESCRIPTION'); ?></th>
		<th><?php echo JText::_('K2STORE_DOWNLOAD_LINK'); ?></th>
	</thead>
	<?php
	foreach($this->files as $files):
	foreach($files as $file):
		
		$file_link = $this->model->getLink($file->id, $file->itemID, $file->order_id);
		$product = K2StoreHelperCart::getItemInfo($file->product_id);		
	?>
	<tr>
		<td><?php echo $file->order_id;?></td>
		<td><?php echo $product->product_name;?></td>
		<td><?php echo $file->title;?></td>
		<td><?php echo $file->titleAttribute;?></td>
		<td><a href="<?php echo $file_link; ?>"> <?php echo JText::_('K2STORE_CLICK_TO_DOWNLOAD'); ?></a></td>
	</tr>
	<?php endforeach; ?>
	<?php endforeach; ?>
</table>
<?php else:?>
<div class="k2store_nodownloads">
<?php echo JText::_('K2STORE_NO_DOWNLOADS_AVAILABLE');?>
</div>
<?php endif;?>
<?php echo K2StoreHelperModules::loadposition('k2store-download-bottom1'); ?>
<?php echo K2StoreHelperModules::loadposition('k2store-download-bottom2'); ?>