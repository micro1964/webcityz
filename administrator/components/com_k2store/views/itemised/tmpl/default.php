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

$user	= JFactory::getUser();
$action = JRoute::_('index.php?option=com_k2store&view=itemised');
$listOrder	= $this->lists['order'];
$listDirn	= $this->lists['order_Dir'];
$saveOrder	= $listOrder == 'oi.orderitem_id';
require_once (JPATH_SITE.'/components/com_k2store/helpers/orders.php');
?>
<div class="k2store">
<h3><?php echo JText::_('K2STORE_REPORTS_ITEMISED');?></h3>
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
				<th>#</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_PRODUCT_ID', 'oi.product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_PRODUCT_NAME', 'oi.orderitem_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_CATEGORY', 'category_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_REPORTS_ITEMISED_QUANTITY', 'sum', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="id">
					<?php echo JHtml::_('grid.sort',  'K2STORE_REPORTS_ITEMISED_PURCHASES', 'count', $this->lists['order_Dir'], $this->lists['order']); ?>
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
				<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i%2; ?>">
			   <td><?php echo $i+1; ?></td>
				<td> <?php echo $item->product_id;?> </td>
				<td>
				<?php

				?>
				<a href="<?php echo 'index.php?option=com_k2store&view=orders&product_id='.$item->product_id.'&attribute='.base64_encode($item->orderitem_attributes); ?>" >
				 <?php echo $item->orderitem_name;?>
				 </a>
				<br />
				<!-- start of orderitem attributes -->
						<!-- backward compatibility -->
						<?php if(!K2StoreOrdersHelper::isJSON($item->orderitem_attribute_names)): ?>

							<?php if (!empty($item->orderitem_attribute_names)) : ?>
								<span><?php echo $item->orderitem_attribute_names; ?></span>
							<?php endif; ?>
						<br />
						<?php else: ?>
						<!-- since 3.1.0. Parse attributes that are saved in JSON format -->
						<?php if (!empty($item->orderitem_attribute_names)) : ?>
                            <?php

                            	//first convert from JSON to array
                            	$registry = new JRegistry;
                            	$registry->loadString($item->orderitem_attribute_names, 'JSON');
                            	$product_options = $registry->toObject();
                            ?>
                            	<?php foreach ($product_options as $option) : ?>
             				   - <small><?php echo $option->name; ?>: <?php echo $option->value; ?></small><br />
            				   <?php endforeach; ?>
                            <br/>
                        <?php endif; ?>
					<?php endif; ?>
					<!-- end of orderitem attributes -->
				</td>

				<td> <?php echo $this->escape($item->category_name);?> </td>

				<td> <?php echo $this->escape($item->sum);?> </td>
				<td> <?php echo $this->escape($item->count);?> </td>

				<?php endforeach; ?>
			<?php else: ?>
				 <td colspan="9"><?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?></td>
			<?php endif; ?>
			</tr>
			</tbody>
		  </table>
		 <input type="hidden" name="task" value="" />
		 <input type="hidden" name="option" value="com_k2store" />
		 <input type="hidden" name="view" value="itemised" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
		</form>
</div>