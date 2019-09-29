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


//no direct access
defined('_JEXEC') or die('Restricted access');

 $state = @$this->state;
 $items = @$this->items;
 $row = @$this->row;
 $task ='setproductoptionvalues';
 $action = JRoute::_( 'index.php?option=com_k2store&view=products&task=setproductoptionvalues&tmpl=component&product_option_id='.$row->product_option_id);
 require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/select.php');
 require_once (JPATH_ADMINISTRATOR.'/components/com_k2store/library/options.php');

?>

<div class="k2store">
<h1><?php echo JText::_( 'K2STORE_PAO_SET_OPTIONS_FOR' ); ?>: <?php echo $row->option_name; ?></h1>
<?php if($this->lists['option_values']):?>
<form action="<?php echo $action; ?>" method="post" id="adminForm" name="adminForm" enctype="multipart/form-data">

	<div class="note row-fluid">
	    <h3><?php echo JText::_('K2STORE_PAO_ADD_NEW_OPTION'); ?></h3>
	    <h6><?php echo JText::_( "K2STORE_PAO_COMPLETE_TO_ADD_NEW" ); ?>:</h6>
                <table class="adminlist table table-striped">
                <thead>
                <tr>
                    <th><?php echo JText::_( "K2STORE_PAO_NAME" ); ?></th>
                    <th style="width: 15px;"><?php echo JText::_( "K2STORE_PAO_PREFIX" ); ?></th>
                    <th><?php echo JText::_( "K2STORE_PAO_PRICE" ); ?></th>
                    <th><?php echo JText::_( "K2STORE_PAO_CODE" ); ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php echo $this->lists['option_values']; ?>
                    </td>
                    <td>
                        <?php echo K2StoreSelect::productattributeoptionprefix( "+", 'product_optionvalue_prefix' ); ?>
                    </td>
                    <td>
                        <input id="product_optionvalue_price" name="product_optionvalue_price" value="" size="10" />
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="document.getElementById('task').value='createproductoptionvalue'; document.adminForm.submit();"><?php echo JText::_('K2STORE_PAO_CREATE_OPTION'); ?></button>
                    </td>
                </tr>
                </tbody>
                </table>

	</div>

<div class="note_green row-fluid">
    <h3><?php echo JText::_('K2STORE_PAO_CURRENT_OPTIONS'); ?></h3>
    <div class="pull-right">
        <button class="btn btn-info" onclick="document.getElementById('task').value='saveproductoptionvalues'; document.getElementById('checkall-toggle').checked=true; k2storeCheckAll(document.adminForm); document.adminForm.submit();"><?php echo JText::_('K2STORE_SAVE_CHANGES'); ?></button>
    </div>
    <div class="reset"></div>

	<table class="adminlist table table-striped">
		<thead>
            <tr>
                <th>
                	<input type="checkbox" id="checkall-toggle" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" />
                </th>
                <th>
					<?php echo JText::_('K2STORE_PAO_AO_NAME'); ?>
                </th>
                <th width="10%">
					<?php echo JHTML::_('grid.sort',  'K2STORE_PAO_AO_PREFIX', 'a.product_optionvalue_prefix',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
                </th>
                <th>
					<?php echo JHTML::_('grid.sort',  'K2STORE_PAO_PRICE', 'a.product_optionvalue_price',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
                </th>
                <th width="20px">
                <?php echo JHTML::_('grid.sort',  'K2STORE_ORDERING', 'a.ordering',  $this->lists['order_Dir'], $this->lists['order'],$task ); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $key=>$item) :
      		$checked = JHTML::_('grid.id', $i, $item->product_optionvalue_id);
      //  $checked = JHTML::_('grid.checkedout', $item, $key );
      //get the list of already available options
      $list = K2StoreHelperOptions::getProductOptionValues($item->option_id, $item->optionvalue_id, $item->product_optionvalue_id);

        ?>
            <tr class='row<?php echo $k; ?>'>
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<?php echo $list; ?>
					[<a href="index.php?option=com_k2store&view=products&task=deleteproductoptionvalues&po_id=<?php echo $row->product_option_id; ?>&cid[]=<?php echo $item->product_optionvalue_id; ?>&return=<?php echo base64_encode("index.php?option=com_k2store&view=products&task=setproductoptionvalues&product_option_id={$row->product_option_id}&tmpl=component"); ?>">
						<?php echo JText::_( 'K2STORE_PAO_DELETE_OPTION' ); ?>
					</a>
					]
				</td>
                <td>
                    <?php echo K2StoreSelect::productattributeoptionprefix( $item->product_optionvalue_prefix, "prefix[{$item->product_optionvalue_id}]" ); ?>
                </td>
                <td>
                    <input type="text" name="price[<?php echo $item->product_optionvalue_id; ?>]" value="<?php echo $item->product_optionvalue_price; ?>" size="10" />
                </td>
              <td>
					<input type="text" name="ordering[<?php echo $item->product_optionvalue_id; ?>]" value="<?php echo $item->ordering; ?>" size="10" />
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>

			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="5" align="center">
					<?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="product_option_id" value="<?php echo $row->product_option_id; ?>" />
	<input type="hidden" name="option_id" value="<?php echo $row->option_id; ?>" />
	<input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>" />
	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="task" id="task" value="setproductoptionvalues" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

</form>
<?php else: ?>
<?php echo JText::_('K2STORE_PAO_NO_GLOBAL_OPTIONS_SET'); ?>
<?php endif;?>
</div>