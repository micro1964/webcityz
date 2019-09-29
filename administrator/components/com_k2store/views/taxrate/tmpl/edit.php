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

$action = JRoute::_('index.php?option=com_k2store&view=taxrate');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $action; ?>" method="post" name="adminForm"
	id="adminForm" class="form-validate">

	<div class="k2store" id="taxrate_edit">
		<fieldset class="fieldset">
			<legend>
				<?php echo JText::_('K2STORE_TAXRATE'); ?>
			</legend>
			<table>
				<tr>
					<td><?php echo $this->form->getLabel('taxrate_name'); ?>
					</td>
					<td><?php echo $this->form->getInput('taxrate_name'); ?>
					</td>
				</tr>


				<tr>
					<td><?php echo $this->form->getLabel('tax_percent'); ?>
					</td>
					<td><?php echo $this->form->getInput('tax_percent'); ?>
					<small><?php echo JText::_('K2STORE_TAXRATE_PERCENT_HELP_TEXT');?></small>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->getLabel('geozone_id'); ?>
					</td>
					<td><?php echo $this->form->getInput('geozone_id'); ?>
					</td>
				</tr>

				<tr>
					<td><?php echo $this->form->getLabel('state'); ?>
					</td>
					<td><?php echo $this->form->getInput('state'); ?>
					</td>
				</tr>

			</table>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_k2store">
	<input type="hidden" name="taxrate_id" value="<?php echo $this->item->taxrate_id; ?>">
	<input type="hidden" name="task" value="">
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
