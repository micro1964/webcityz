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
?>
<?php if( $system->my->toModerate() &&  $system->config->get( 'form_show_moderate_message' ) ) { ?>
<div class="kmt-form-alert"><?php echo JText::_( 'COM_KOMENTO_FORM_USER_COMMENTS_WILL_BE_MODERATED' ); ?></div>
<?php } ?>
<button type="button" class="submitButton kmt-btn-submit<?php if( $system->konfig->get( 'enable_live_form_validation' ) ) echo ' disabled'; ?>"><?php echo JText::_( 'COM_KOMENTO_FORM_SUBMIT' ); ?></button>
