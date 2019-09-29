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
//this is necessary for sorting
$task='setpaimport';
$action = JRoute::_( 'index.php?option=com_k2store&view=products&task=setpaimport&tmpl=component&id='.$this->row->id);
?>
<h3><?php echo JText::_( "K2STORE_PAI_IMPORT_ATTRIBUTE_FOR" ); ?>: <?php echo $this->row->title; ?></h1>
<?php if(count($this->items)):?>
<form action="<?php echo $action; ?>" method="post" name="adminForm" enctype="multipart/form-data">

<div class="row-fluid">
	<div>
		<button class="btn btn-primary" onclick="document.getElementById('task').value='importattributes'; document.adminForm.submit();"><?php echo JText::_('K2STORE_PAI_IMPORT_ATTRIBUTES'); ?></button>
	</div>
	<table class="adminlist table table-striped">
		<thead>
            <tr>
                <th style="width: 20px;">
                	<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" />
                </th>
                <th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'K2STORE_PRODUCT_ID', 'p.id',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>					
                </th>
                <th style="text-align: left;">
					<?php echo JHTML::_('grid.sort',  'K2STORE_PRODUCT_NAME', 'p.title',  $this->lists['order_Dir'], $this->lists['order'], $task ); ?>
                </th>
                 <th style="text-align: left;">
					<?php echo JText::_( "K2STORE_PRODUCT_ATTRIBUTES" ); ?>
                </th>
                       
            </tr>
		</thead>
        <tbody>
	
		<?php $i=0; $k=0; ?>
        <?php foreach (@$this->items as $item) : 
        $checked = JHTML::_('grid.id', $i, $item->id);
	    ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
					<?php
					 //echo JHTML::_('grid.checkedout',   $item, $i );
					echo $checked;
					?>
				</td>
				<td style="text-align: left;">
					<?php echo $item->id?>
				</td>
				
				<td style="text-align: left;">
					 <?php echo $item->title; ?>
				</td>
				
				<td style="text-align: left;">
					 <?php 
					 //get the attributes for this product
					 $attributes = $this->model->getAttributes($item->id);
					 if(count($attributes)) {
					 		echo '<ol>';
					 	foreach($attributes as $attribute) {
					 		echo '<li>'.$attribute->productattribute_name.'</li>';
					 		
					 		//now get the options for this attribute
					 		$a_options = $this->model->getAttributeOptions($attribute->productattribute_id);
					 			if(count($a_options)) {
					 				echo '<strong>'.JText::_('K2STORE_PAI_OPTIONS_FOR_THIS_ATTRIBUTE').'</strong>';
					 				echo '<ol>';
					 				foreach ($a_options as $a_option) {
					 				?>	
					 				<li>
					 				<span><?php echo $a_option->productattributeoption_name; ?></span>
					 				<span><?php echo $a_option->productattributeoption_prefix; ?>&nbsp;<?php echo $a_option->productattributeoption_price; ?></span>
					 				</li>	
					 				<?php 
					 				}
					 				echo '</ol>';
					 			}
					 		}
					 		echo '</ol>';
					 }
					 ?>					 
				</td>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php echo @$this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="task" id="task" value="setpaimport" />
	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="view" value="products" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</div>
</form>
<?php else: ?>
	<div>
		<?php echo JText::_('K2STORE_NO_ITEMS_FOUND'); ?>
	</div>
<?php endif; ?>