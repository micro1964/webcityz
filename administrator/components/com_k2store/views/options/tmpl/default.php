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
$action = JRoute::_('index.php?option=com_k2store&view=options');
$listOrder	= $this->lists['order'];
$listDirn	= $this->lists['order_Dir'];
$saveOrder	= $listOrder == 'a.ordering';
require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/popup.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/library/options.php');
?>
<div class="k2store">
<h3><?php echo JText::_('K2STORE_PRODUCT_GLOBAL_OPTIONS');?></h3>
		<form action="<?php echo $action;?>" name="adminForm" class="adminForm" id="adminForm" method="post">
		<table class="table">
		<tr>
				<td align="left" width="100%">
				<?php echo JText::_( 'K2STORE_FILTER_SEARCH' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
				<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_GO' ); ?></button>
				<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_RESET' ); ?></button>
				</td>
			</tr>
		   </table>

		  <table id="optionsList" class="adminlist table table-striped">

			<thead>
			<tr>
				<?php if (version_compare(JVERSION, '3.0', 'ge')): ?>
				<th width="1%" class="center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'], null, 'asc', 'K2STORE_ORDER'); ?>
				</th>
				<?php else: ?>
				<th>#</th>
				<?php endif; ?>
				<th width="1px">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_OPTION_UNIQUE_NAME', 'a.option_unique_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_OPTION_DISPLAY_NAME', 'a.option_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_OPTION_TYPE', 'a.option_type', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<?php if (!version_compare(JVERSION, '3.0', 'ge')): ?>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2STORE_ORDER', 'a.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php if($this->ordering) {echo JHTML::_('grid.order',  $this->items);} ?>
				</th>
				<?php endif; ?>

				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="id">
					<?php echo JHtml::_('grid.sort',  'ID', 'a.option_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="id">
					<?php echo JText::_('K2STORE_OPTION_VALUES'); ?>
				</th>
			</tr>
			</thead>

			<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

			<tbody>
				<?php if($this->items) : ?>
				<?php
				 foreach ($this->items as $i => $item)
				  {
				  	$canChange	= 1;
				  	$canEdit=1;
				 	  ?>
				<tr class="row<?php echo $i%2; ?>">
			   <?php if (version_compare(JVERSION, '3.0', 'ge')): ?>
					<td class="order center hidden-phone">
					<?php if($canChange): ?>
					<span class="sortable-handler<?php echo ($this->ordering) ? '' : ' inactive tip-top' ; ?>" title="<?php echo ($this->ordering) ? '' : JText::_('JORDERINGDISABLED'); ?>" rel="tooltip"><i class="icon-menu"></i></span>
					<input type="text" style="display:none"  name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
					<?php else: ?>
					<span class="sortable-handler inactive" ><i class="icon-menu"></i></span>
					<?php endif; ?>
				</td>
				<?php else: ?>
				<td><?php echo $i+1; ?></td>
				<?php endif; ?>
					 <td><?php echo JHtml::_('grid.id',$i,$item->option_id); ?> </td>
					 <td> <?php echo $item->option_unique_name;?> </td>
				     <td> <?php echo $this->escape($item->option_name);?> </td>
				     <td> <?php
				     	echo K2StoreHelperOptions::convertKeysToText($item->type);

				     ?> </td>

				    <?php if (!version_compare(JVERSION, '3.0', 'ge')): ?>
					<td class="order k2Order">
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'orderup', 'K2STORE_MOVE_UP', $this->ordering); ?></span> <span><?php echo $this->pagination->orderDownIcon($i, count($this->items), true, 'orderdown', 'K2_MOVE_DOWN', $this->ordering); ?></span>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" <?php echo ($this->ordering)?  '' : 'disabled="disabled"' ?> class="text_area k2OrderBox" />
					</td>
					<?php endif; ?>
				      <td class="center">

						<?php
						echo JHtml::_('jgrid.published', $item->state, $i, '', $canChange, 'cb'); ?>
					</td>
					 <td> <?php echo $item->option_id; ?></td>
					 <td>
					 <?php
					 $type_array = array('select', 'radio', 'checkbox');
					 ?>
						<?php if(in_array($item->type, $type_array )) : ?>
					 	[<?php echo K2StorePopup::popup( "index.php?option=com_k2store&view=options&task=setoptionvalues&option_id=".$item->option_id."&tmpl=component", JText::_( "K2STORE_OPTION_SET_OPTIONVALUES" ) ); ?>]
					 	<?php else: ?>
					 		<?php echo JText::_('K2STORE_NOT_APPLICABLE'); ?>
					 	<?php endif; ?>
					 </td>

				 <?php
				  } ?>
			<?php else: ?>
				 <td colspan="9"><?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?></td>
			<?php endif; ?>
			</tr>
			</tbody>
		  </table>
		 <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		</form>
</div>

