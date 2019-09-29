<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');
?>

<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_CONVERSATION_BAR' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Enable Conversation Bar -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CONVERSATION_BAR_ENABLE', 'enable_conversation_bar' ); ?>

					<!-- Max Authors -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CONVERSATION_BAR_MAX_AUTHORS', 'conversation_bar_max_authors', 'input' ); ?>

					<!-- Include Guest -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CONVERSATION_BAR_INCLUDE_GUEST', 'conversation_bar_include_guest' ); ?>

				</tbody>
			</table>
			</fieldset>
		</td>

		<td width="50%" valign="top">
		</td>
	</tr>
</table>
