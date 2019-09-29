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
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_ATTACHMENT' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Enable Attachment -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_ENABLE', 'upload_enable' ); ?>

					<!-- Custom Path -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_CUSTOM_PATH', 'upload_path', 'input', array( 'size' => 10, 'pretext' => '/media/com_komento/uploads/' ) ); ?>

					<!-- Attachment Layout -->
					<?php $options = array();
						$options[] = array( 'icon', 'COM_KOMENTO_SETTINGS_ATTACHMENT_LAYOUT_ICON' );
						$options[] = array( 'list', 'COM_KOMENTO_SETTINGS_ATTACHMENT_LAYOUT_LIST' );
						// echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_LAYOUT', 'attachment_layout', 'dropdown', $options ); ?>

					<!-- Allowed Extension -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_ALLOWED_EXTENSION', 'upload_allowed_extension', 'input', 50 ); ?>

					<!-- Maximum Files -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_MAX_FILE', 'upload_max_file', 'input', array( 'size' => 2, 'align' => 'center' ) ); ?>

					<!-- Maximum Size -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_ATTACHMENT_MAX_SIZE', 'upload_max_size', 'input', array( 'size' => 2, 'posttext' => 'MegaBytes (MB)', 'align' => 'center' ) ); ?>

					<tr>
						<td width="300" class="key"></td>
						<td valign="top"><span class="small"><?php echo JText::sprintf( 'COM_KOMENTO_SETTINGS_PHP_MAX_FILESIZE', ini_get( 'upload_max_filesize') ); ?></span></td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</td>

		<td width="50%" valign="top">
		</td>
	</tr>
</table>
