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
 $document = JFactory::getDocument();
 $document->addScriptDeclaration("
 Joomla.submitbutton = function(pressbutton){
 		if (pressbutton == 'cancel') {
 		submitform( pressbutton );
 		return;
 		}
 		if (K2Store.trim(K2Store('#option_unique_name').val()) == '') {
 			alert( '".JText::_('K2STORE_OPTION_UNIQUE_NAME_MUST_HAVE_A_TITLE', true)."' );
 		} else if(K2Store.trim(K2Store('#option_name').val()) == '') {
 			alert( '".JText::_('K2STORE_OPTION_NAME_MUST_HAVE_A_TITLE', true)."' );
 		}else {
 		submitform( pressbutton );

 }
 }
 ");

?>
<div class="k2store">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<fieldset>
	<legend><?php echo JText::_('K2STORE_OPTION_DETAILS'); ?> </legend>

	<table class="admintable">

	<tr>
			<td width="100" align="right" class="key">
				<label for="option_unique_name">
					<?php echo JText::_( 'K2STORE_OPTION_UNIQUE_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="option_unique_name" id="option_unique_name" class="required" value="<?php echo $this->data->option_unique_name;?>" />
			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="option_name">
					<?php echo JText::_( 'K2STORE_OPTION_DISPLAY_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="option_name" id="option_name" class="required" value="<?php echo $this->data->option_name;?>" />
			</td>
		</tr>

		<tr>
			<td width="100" align="right" class="key">
				<label for="type">
					<?php echo JText::_( 'K2STORE_OPTION_TYPE' ); ?>:
				</label>
			</td>
			<td>
				<select name="type">
                <optgroup label="<?php echo JText::_( 'K2STORE_OPTION_OPTGROUP_LABEL_CHOOSE' ); ?>">
                                <option <?php echo ($this->data->type=='select')? 'selected="selected"':''?> value="select"><?php echo JText::_( 'K2STORE_SELECT' ); ?></option>
                               <option  <?php echo ($this->data->type=='radio')? 'selected="selected"':''?> value="radio"><?php echo JText::_( 'K2STORE_RADIO' ); ?></option>

                   </optgroup>
              </select>
			</td>
		</tr>

		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'K2STORE_OPTION_STATE' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
	</table>

</fieldset>
	<input type="hidden" name="option" value="com_k2store" />
	<input type="hidden" name="view" value="options" />
	<input type="hidden" name="option_id" value="<?php echo $this->data->option_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>