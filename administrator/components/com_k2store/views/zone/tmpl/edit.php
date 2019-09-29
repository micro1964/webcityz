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

 $action = JRoute::_('index.php?option=com_k2store&view=zone');
 JHtml::_('behavior.formvalidation');
 JHtml::_('behavior.keepalive');
 JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $action; ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

<div class="k2store">
	<fieldset class="fieldset">
		<legend><?php echo JText::_('K2STORE_ZONE'); ?></legend>
				<table>
					<tr>
						<td> <?php echo $this->form->getLabel('zone_name'); ?> </td>
						<td> <?php echo $this->form->getInput('zone_name'); ?> </td>
					</tr>


					<tr>
						<td> <?php echo $this->form->getLabel('zone_code'); ?> </td>
						<td> <?php echo $this->form->getInput('zone_code'); ?> </td>
					</tr>
					<tr>
						<td> <?php echo $this->form->getLabel('country_id'); ?> </td>
						<td> <?php echo $this->form->getInput('country_id'); ?> </td>
					</tr>

					<tr>
						<td> <?php echo $this->form->getLabel('state'); ?> </td>
						<td> <?php echo $this->form->getInput('state'); ?> </td>
					</tr>

				</table>
	   </fieldset>
	</div>
	  	<input type="hidden" name="option"	value="com_k2store">
	  	<input type="hidden" name="zone_id"	value="<?php echo $this->item->zone_id; ?>">
		<input type="hidden" name="task" value="">
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
