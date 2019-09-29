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


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );
?>
<div class="k2store">

		<div class="alert alert-info">
			<?php echo JText::_('K2STORE_MIGRATE_ATTRIBUTES_INFO')?>
		</div>

		<div class="alert alert-danger">
			<strong>
			<?php echo JText::_('K2STORE_MIGRATE_ATTRIBUTES_WARNING')?>
			</strong>
		</div>

		<!-- Are there attributes with = sign? -->
		<?php if(count($this->items)):?>

			<div class="alert alert-danger">
				<strong>
					<?php echo JText::_('K2STORE_MIGRATE_ATTRIBUTES_INCOMPATIBLE_PREFIX_INFO')?>
				</strong>
			</div>
			<table class="adminlist table table-striped">
			<thead>
				<th><?php echo JText::_('K2STORE_PRODUCT_ID')?></th>
				<th><?php echo JText::_('K2STORE_PRODUCT_NAME')?></th>
				<th><?php echo JText::_('K2STORE_PA_NAME')?></th>
				<th><?php echo JText::_('K2STORE_PAO_ATTRIBUTE_OPTIONS')?></th>
			</thead>
			<tbody>
			<?php foreach($this->items as $item) : ?>
			<tr>
				<td><?php echo $item['product_id']; ?> </td>
				<td><?php echo $item['product_name']; ?> </td>
				<td><?php echo $item['attribute_name']; ?> </td>
				<td>
					<?php echo JText::_('K2STORE_PAO_AO_NAME')?>:<?php echo $item['pao']->productattributeoption_name; ?>
					<br />
					<?php echo JText::_('K2STORE_PAO_PRICE')?>:<?php echo $item['pao']->productattributeoption_price; ?>
					<br />
					<?php echo JText::_('K2STORE_PAO_PREFIX')?>:<?php echo $item['pao']->productattributeoption_prefix; ?>
				 </td>

			</tr>
			<?php endforeach; ?>

			</tbody>
			</table>
		<?php endif; ?>
		<?php if(K2STORE_ATTRIBUTES_MIGRATED==false):?>
		<form action='index.php' method="post" id="migrateForm" name="migrateForm">
			<input type="button" id="btnSubmit" class="btn btn-primary btn-large" onclick="javascript:this.disabled=true; document.migrateForm.submit();" value="<?php echo JText::_('K2STORE_START_MIGRATION')?>" />
			<input type="hidden" name="option" value="com_k2store" />
			<input type="hidden" name="view" value="migrate" />
			<input type="hidden" name="task" value="migrate" />

		</form>
		<?php endif; ?>
</div>