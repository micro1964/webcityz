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

if( $system->config->get( 'layout_avatar_enable' ) ) { ?>
	<div class="kmt-avatar"
		<?php if( $system->config->get( 'easysocial_profile_popbox' ) && !$system->my->guest ) { ?>
		data-popbox="module://easysocial/profile/popbox" data-user-id="<?php echo $system->my->id; ?>"
		<?php } ?>
	>
		<a href="<?php echo $system->my->getProfileLink(); ?>">
			<img src="<?php echo $system->my->getAvatar(); ?>" class="avatar" />
		</a>
	</div>
<?php }
