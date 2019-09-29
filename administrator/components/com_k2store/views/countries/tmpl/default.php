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

JHtml::_('behavior.tooltip');
$user	= JFactory::getUser();
$action = JRoute::_('index.php?option=com_k2store&view=countries');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

?>
<div class="k2store">
		<form action="<?php echo $action;?>" name="adminForm" class="adminForm" id="adminForm" method="post">
		<table class="table">
		<tr>
		<td>
		<!-- search filter -->
		  <label for="filter_search" >
		  		<?php echo JText::_('K2STORE_FILTER_SEARCH');?> </label>
				<input type="text" name="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" id="search"/>
				<button class="btn btn-success" onclick="this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_GO' ); ?></button>
				<button class="btn btn-inverse" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'K2STORE_FILTER_RESET' ); ?></button>
			</td>
			<td class="filter-select" >
			<!-- select for state -->
					   <select name="filter_published" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
							<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
						</select>
			 </td>
			</tr>
		   </table>

		  <table id="adminTable" class="adminlist table table-striped">

			<thead>
				<th width="1px"><?php echo JText::_('K2STORE_NUM'); ?></th>
				<th width="1px">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th class="name">
					<?php echo JHtml::_('grid.sort',  'K2STORE_COUNTRY_NAME', 'a.country_name', $listDirn, $listOrder); ?>
				</th>
				<th class="code2">
					<?php echo JHtml::_('grid.sort',  'K2STORE_COUNTRY_CODE2', 'a.country_isocode_2', $listDirn, $listOrder); ?>
				</th>
				<th class="code3">
					<?php echo JHtml::_('grid.sort',  'K2STORE_COUNTRY_CODE3', 'a.country_isocode_3', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th class="id">
					<?php echo JHtml::_('grid.sort',  'K2STORE_COUNTRY_ID', 'a.country_id', $listDirn, $listOrder); ?>
				</th>

			</thead>

			<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

			<tbody>
				<?php
				 foreach ($this->items as $i => $item)
				  {
				  	$canChange	= 1;
				  	$canEdit=1;
				 	  ?>
				<tr class="row<?php echo $i%2; ?>">
					<td><?php echo $i+1; ?></td>
					 <td><?php echo JHtml::_('grid.id',$i,$item->country_id); ?> </td>
				     <td> <?php echo $this->escape($item->country_name);?> </td>
				     <td> <?php echo $this->escape($item->country_isocode_2 );?></td>
				     <td> <?php echo $this->escape($item->country_isocode_3 );?></td>
				      <td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'countries.', $canChange, 'cb'); ?>
					</td>
					 <td> <?php echo $item->country_id; ?></td>
				 <?php
				  } ?>
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