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
$row = $this->address;

?>

<form action="index.php?option=com_k2store&view=address" method="post" name="adminForm" id="adminForm">
<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'K2STORE_ADDRESS_DETAILS' ); ?></legend>

		<table class="admintable">
		
			    <tr>
			        <th style="width: 100px; text-align: right;" class="key">
			             <?php echo JText::_( 'K2STORE_ADDRESS_FIRSTNAME' ); ?>
			        </th>
			        <td>
			            <input name="first_name" id="first_name" 
			            type="text" size="35" maxlength="250"
			            value="<?php echo $row->first_name; ?>" />
			        </td>
			    </tr>
			    <tr>
			        <th style="width: 100px; text-align: right;" class="key">
			             <?php echo JText::_( 'K2STORE_ADDRESS_LASTNAME' ); ?>
			        </th>
			        <td>
			           <input type="text" name="last_name"
			            id="last_name" size="45" maxlength="250"
			            value="<?php echo @$row->last_name; ?>" />
			        </td>
			    </tr>
			    <tr>
			        <th style="width: 100px; text-align: right;" class="key">
			              <?php echo JText::_( 'K2STORE_ADDRESS_LINE1' ); ?>
			        </th>
			        <td>
			            <input type="text" name="address_1"
			            id="address_1" size="48" maxlength="250" 
			            value="<?php echo @$row->address_1; ?>" />
			        </td>
			    </tr>
			    <tr>
			        <th style="width: 100px; text-align: right;" class="key">
			              <?php echo JText::_( 'K2STORE_ADDRESS_LINE2' ); ?>
			        </th>
			        <td>
			            <input type="text" name="address_2"
			            id="address_2" size="48" maxlength="250" 
			            value="<?php echo @$row->address_2; ?>" />
			        </td>
			    </tr>
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_CITY' ); ?>
					</th>
					<td>
						<input type="text" name="city" id="city"
						size="48" maxlength="250" 
						value="<?php echo @$row->city; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_ZIP' ); ?>
					</th>
					<td>
						<input type="text" name="zip" id="zip"
						size="48" maxlength="250" 
						value="<?php echo @$row->zip; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_STATE' ); ?>
					</th>
					<td>
						<input type="text" name="state" id="state"
						size="48" maxlength="250" 
						value="<?php echo @$row->state; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_COUNTRY' ); ?>
					</th>
					<td>
						<input type="text" name="country" id="country"
						size="48" maxlength="250" 
						value="<?php echo @$row->country; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_PHONE' ); ?>
					</th>
					<td>
						<input type="text" name="phone_1" id="phone_1"
						size="25" maxlength="250" 
						value="<?php echo @$row->phone_1; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
			            <?php echo JText::_( 'K2STORE_ADDRESS_MOBILE' ); ?>
					</th>
					<td>
						<input type="text" name="phone_2" id="phone_2"
						size="25" maxlength="250"
						value="<?php echo @$row->phone_2; ?>" />
					</td>
				</tr>
				
				<tr>
					<th style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'K2STORE_ADDRESS_FAX' ); ?>
					</th>
					<td>
						<input type="text" name="fax" id="fax" 
						size="25" maxlength="250" 
						value="<?php echo @$row->fax; ?>" />
					</td>
				</tr>
			
		
		</table>
	</fieldset>
</div>

<div class="clr"></div>

	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="view" value="address" />
	<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
