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
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_SCHEMA' ); ?></legend>
			<p class="small"><?php echo JText::sprintf( 'COM_KOMENTO_SETTINGS_SCHEMA_INFO', '<a href="http://schema.org/" target="_blank">schema.org</a>' ); ?></p>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Enable Schema -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_SCHEMA_ENABLED', 'enable_schema' ); ?>

				</tbody>
			</table>
			</fieldset>
		</td>
		<td valign="top">
		</td>
	</tr>
</table>
