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
JHTML::_('behavior.tooltip');
 $state = @$this->state;
 $items = @$this->items;
 $row = @$this->row;
 $task='setoptionvalues';
 $action = JRoute::_( 'index.php?option=com_k2store&view=options&task=setoptionvalues&tmpl=component&option_id='.$row->option_id);
 ?>
<div class="k2store">
<h1><?php echo JText::_( "K2STORE_OV_OPTION_VALUES_FOR" ); ?>: <?php echo $row->option_name; ?></h1>

<form action="<?php echo $action; ?>" method="post" name="adminForm" enctype="multipart/form-data">

	<div class="note row-fluid">

    <h3><?php echo JText::_('K2STORE_OV_ADD_NEW_OPTION_VALUES'); ?></h3>
	<table class="adminlist table table-striped">
    	<thead>
    	<tr>
    		<th><?php echo JText::_( "K2STORE_OV_OPTION_VALUE_NAME" ); ?></th>
    	</tr>
    	</thead>
    	<tbody>
    	<tr>
    		<td style="text-align: center;">
    			<input id="optionvalue_name" name="optionvalue_name" value="" />
    		</td>
    		<td><button class="btn btn-primary" onclick="document.getElementById('task').value='createoptionvalue'; document.adminForm.submit();"><?php echo JText::_('K2STORE_OV_CREATE_OPTION_VALUE'); ?></button></td>
    	</tr>
    	</tbody>
	</table>
</div>

<div class="note_green row-fluid">
    <h3><?php echo JText::_('K2STORE_OV_CURRENT_OPTION_VALUES'); ?></h3>
    <div class="pull-right">
        <button class="btn btn-info" onclick="document.getElementById('task').value='saveoptionvalues'; document.getElementById('checkall-toggle').checked=true; k2storeCheckAll(document.adminForm); document.adminForm.submit();"><?php echo JText::_('K2STORE_SAVE_CHANGES'); ?></button>
    </div>
    <div class="reset"></div>
   <table class="adminlist table table-striped" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 20px;">
                	<input type="checkbox" id="checkall-toggle" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'K2STORE_OV_OPTION_VALUE_NAME', 'a.optionvalue_name',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
                </th>
                <th><?php echo JText::_('K2STORE_OPTION_ID'); ?> </th>
                <th style="width: 100px;">
					<?php echo JHTML::_('grid.sort',  'K2STORE_ORDERING', 'a.ordering',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
                </th>
            </tr>
		</thead>
        <tbody>

		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) :
        $checked = JHTML::_('grid.id', $i, $item->optionvalue_id);
	    ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
					<?php
					 //echo JHTML::_('grid.checkedout',   $item, $i );
					echo $checked;
					?>
				</td>
				<td style="text-align: left;">
					<input type="text" name="name[<?php echo $item->optionvalue_id; ?>]" value="<?php echo $item->optionvalue_name; ?>" />
					[<a href="index.php?option=com_k2store&view=options&task=deleteoptionvalue&option_id=<?php echo $row->option_id; ?>&cid[]=<?php echo $item->optionvalue_id; ?>&return=<?php echo base64_encode("index.php?option=com_k2store&view=options&task=setoptionvalues&id={$row->option_id}&tmpl=component"); ?>">
						<?php echo JText::_( "K2STORE_OV_DELETE_OPTION_VALUE" ); ?>
					</a>
					]
				</td>
				<td><?php echo $row->option_id; ?> </td>
				<td style="text-align: center;">
					<input type="text" name="ordering[<?php echo $item->optionvalue_id; ?>]" value="<?php echo $item->ordering; ?>" size="3" />
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>

			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="4" align="center">
					<?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="20">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="<?php echo $row->option_id; ?>" />
	<input type="hidden" name="task" id="task" value="setoptionvalues" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

</div>
</form>
</div>