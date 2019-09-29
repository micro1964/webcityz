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
$cronlink = rtrim( JURI::root(), '/' ) . '/index.php?option=com_komento&task=clearCaptcha'; ?>
<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_DATABASE_INFO' ); ?></legend>
			<p class="small"><a href="http://stackideas.com/docs/komento/how-tos/setting-up-cronjobs-in-cpanel.html"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_DATABASE_SETUP_CRONJOB' ) ;?></a></p>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_DATABASE_CRON_LINK' ); ?>: <a href="<?php echo $cronlink; ?>" target="_blank"><?php echo $cronlink; ?></a></p>
			</fieldset>

			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_DATABASE_MAINTENANCE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Show Activities Tab -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_DATABASE_CLEAR_CAPTCHA_ON_PAGE_LOAD', 'database_clearcaptchaonpageload' ); ?>

				</tbody>
			</table>
			</fieldset>
		</td>
		<td width="50%" valign="top">
		</td>
	</tr>
</table>
