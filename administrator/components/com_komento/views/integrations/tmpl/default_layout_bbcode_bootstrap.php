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
<script type="text/javascript">
Komento.require().script('admin.integrations.customsmileys').done(function($) {
	$('.custombbcode').implement('Komento.Controller.CustomSmileys');
});
</script>

<div class="row-fluid">
	<div class="span12">
		<div class="span6">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_BBCODE' ); ?></legend>
			<table class="admintable bbcode" cellspacing="1">
				<tbody>

					<!-- Enable Bold -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_BOLD', 'bbcode_bold' ); ?>

					<!-- Enable Italic -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_ITALIC', 'bbcode_italic' ); ?>

					<!-- Enable Underline -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_UNDERLINE', 'bbcode_underline' ); ?>

					<!-- Enable Link -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_LINK', 'bbcode_link' ); ?>

					<!-- Enable Picture -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_PICTURE', 'bbcode_picture' ); ?>

					<!-- Enable Video -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_VIDEO', 'bbcode_video' ); ?>

					<!-- Enable Bulletlist -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_BULLETLIST', 'bbcode_bulletlist' ); ?>

					<!-- Enable Numericlist -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_NUMERICLIST', 'bbcode_numericlist' ); ?>

					<!-- Enable Bullet -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_BULLET', 'bbcode_bullet' ); ?>

					<!-- Enable Quote -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_QUOTE', 'bbcode_quote' ); ?>

					<!-- Enable Code -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_CODE', 'bbcode_code' ); ?>

					<!-- Enable Clean -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_CLEAN', 'bbcode_clean' ); ?>

					<!-- Enable Smile -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_SMILE', 'bbcode_smile' ); ?>

					<!-- Enable Happy -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_HAPPY', 'bbcode_happy' ); ?>

					<!-- Enable Surprised -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_SURPRISED', 'bbcode_surprised' ); ?>

					<!-- Enable Tongue -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_TONGUE', 'bbcode_tongue' ); ?>

					<!-- Enable Unhappy -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_UNHAPPY', 'bbcode_unhappy' ); ?>

					<!-- Enable Wink -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_BBCODE_WINK', 'bbcode_wink' ); ?>

				</tbody>
			</table>
			</fieldset>
		</div>

		<div class="span6">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_CUSTOM_SMILEYS' ); ?></legend>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_CUSTOM_SMILEYS_INFO' ); ?></p>
			<table class="admintable custombbcode" cellspacing="1">
				<tbody>
					<tr>
						<th width="5"></th>
						<th width="150"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_CUSTOM_SMILEYS_CODE' ); ?></th>
						<th><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_CUSTOM_SMILEYS_PATH' ); ?></th>
					</tr>
					<?php if( is_array( $this->config->get( 'smileycode' ) ) ) { ?>
					<?php $smileypaths = $this->config->get( 'smileypath' ); ?>
					<?php foreach( $this->config->get( 'smileycode' ) as $index => $smileycode ) { ?>
					<tr data-smiley>
						<td data-smiley-delete-button><i class="icon-kmt-delete"></i></td>
						<td>
							<input type="text" class="inputbox" name="smileycode[]" data-smiley-code value="<?php echo $this->escape( $smileycode ); ?>" />
						</td>
						<td valign="top">
							<input type="text" class="inputbox full-width" name="smileypath[]" size="50" data-smiley-path value="<?php echo $this->escape( $smileypaths[$index] ); ?>" />
						</td>
					</tr>
					<?php } ?>
					<?php } ?>
					<tr data-smiley>
						<td data-smiley-delete-button><i class="icon-kmt-delete"></i></td>
						<td>
							<input type="text" class="inputbox" name="smileycode[]" data-smiley-code />
						</td>
						<td valign="top">
							<input type="text" class="inputbox full-width" name="smileypath[]" size="50" data-smiley-path />
						</td>
					</tr>
					<tr data-smiley-add-row>
						<td></td>
						<td></td>
						<td><button type="button" data-smiley-add-button class="btn btn-small btn-success"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_FORM_CUSTOM_SMILEYS_ADD' ); ?></button></td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</div>
	</div>
</div>
