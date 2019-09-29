<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

//JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
$action = JRoute::_('index.php?option=com_k2store&view=taxrates');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$listOrder == 'a.taxrate_id';

$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_k2store&task=taxrates.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'taxrateList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<div class="k2store">
<h3><?php echo JText::_('K2STORE_TAXRATES');?></h3>
<form action="<?php echo $action;?>" name="adminForm" class="adminForm" id="adminForm" method="post">

	<table class="adminlist table table-striped " >
		<tr>

			<!-- search filter -->
			<td>
			<!-- search filter -->
		  <label for="filter_search" >
		  		<?php echo JText::_('K2STORE_FILTER_SEARCH');?> </label>
				<input type="text" name="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" id="search"/>
				<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_GO' ); ?></button>
				<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_RESET' ); ?></button>
			</td>

			<td>
			    <?php echo $this->lists['geozone_options']; ?>
			<!-- select for state -->
					   <select name="filter_published" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
							<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
						</select>
			 </td>
		</tr>
	</table>

		   <table class="adminlist table table-striped" id="taxrateList">
			<thead>
			<th width="1%" class="nowrap center hidden-phone"><?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>

				<th width="1px">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="id">
					<?php echo JHtml::_('grid.sort',  'K2STORE_TAXRATE_ID', 'a.taxrate_id', $listDirn, $listOrder); ?>
				</th>
				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_TAXRATE_NAME', 'a.taxrate_name', $listDirn, $listOrder); ?>
				</th>

				<th class="country_name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_GEOZONE_NAME', 'g.geozone_name', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>

			</thead>

			<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>

				</td>
			</tr>
		</tfoot>

			<tbody>
				<?php
				 foreach ($this->items as $i => $item)

				  {

				  $ordering	= ($listOrder == 'a.taxrate_id');
				  $canChange	= 1;
				  $canCheckin=1;
				  $canEdit=1;
				 	  ?>
				<tr class="row<?php echo $i%2; ?>" sortable-group-id="1">
				<td class="order nowrap center hidden-phone"><?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
						$disabledLabel    = JText::_('JORDERINGDISABLED');
						$disableClassName = 'inactive tip-top';
						endif; ?> <span
							class="sortable-handler hasTooltip <?php echo $disableClassName?>"
							title="<?php echo $disabledLabel?>"> <i class="icon-menu"></i>
						</span> <input type="text" style="display: none" name="order[]"
							size="5" value="<?php echo $item->ordering;?>"
							class="width-20 text-area-order " /> <?php else : ?> <span
							class="sortable-handler inactive"> <i class="icon-menu"></i>
						</span> <?php endif; ?>
						</td>

					 <td><?php echo JHtml::_('grid.id',$i,$item->taxrate_id); ?> </td>
					 <td> <?php echo $item->taxrate_id; ?></td>
				     <td><a href="index.php?option=com_k2store&view=taxrate&task=taxrate.edit&taxrate_id=<?php echo $item->taxrate_id; ?>">
				     <strong>
				     <?php echo $this->escape($item->taxrate_name);?>&nbsp;(<?php echo floatval($this->escape($item->tax_percent ));?>%)
				     </strong>
				     </a>
				     </td>
				    <td> <?php echo $this->escape($item->geozone_name);?> </td>
					<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'taxrates.', $canChange, 'cb'); ?>
				</td>

				 <?php
				  } ?>

			</tbody>
		  </table>
		  <input type="hidden" name="option" value="com_k2store" />
		  <input type="hidden" name="view" value="taxrates" />
		 <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

		</form>
</div>
