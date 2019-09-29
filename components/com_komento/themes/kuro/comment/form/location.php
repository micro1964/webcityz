<?php
/**
 * @package		Komento
 * @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * Komento is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

if( $system->config->get( 'enable_location' ) && $system->config->get( 'show_location' ) ) { ?>
	<div class="locationForm kmt-form-location">
		<input id="register-location" class="locationInput input text kmt-location" type="text" tabindex="45" value="<?php echo JText::_( 'COM_KOMENTO_COMMENT_WHERE_ARE_YOU' ); ?>" name="address" />
		<button type="button" class="autoDetectButton input button"><?php echo JText::_( 'COM_KOMENTO_FORM_LOCATION_AUTODETECT' ); ?></button>
		<input type="hidden" name="latitude" class="locationLatitude" />
		<input type="hidden" name="longitude" class="locationLongitude" />
	</div>
<?php }
