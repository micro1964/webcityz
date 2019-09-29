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
defined('_JEXEC') or die('Restricted access');
if( $system->my->allow( 'read_comment' ) ) { ?>
<script type="text/javascript">
Komento.require().script('komento.commenttools').done(function($) {
	if($('.commentTools').exists()) {
		Komento.options.element.tools = $('.commentTools').addController('Komento.Controller.CommentTools');
		Komento.options.element.tools.kmt = Komento.options.element;
	}
});

</script>
<div class="commentTools kmt-comment-tools-wrap">
<!-- Comment Title -->
<h3 class="kmt-title">
	<?php echo JText::_( 'COM_KOMENTO_COMMENTS' ); ?>
	<?php if ($commentCount) { ?>
	(<span class="commentCounter"><?php echo $commentCount; ?></span>)
	<?php } ?>
</h3>

<?php if( $system->my->allow( 'read_comment') ) { ?>

<ul class="kmt-toolbar reset-ul float-li clearfix">
	<?php if( $system->config->get( 'show_sort_buttons' ) ) { ?>
	<li class="sortOldest kmt-sort-oldest kmt-sorting">
		<a href="javascript:void(0);"<?php echo JRequest::getCmd( 'kmt-sort', $system->config->get( 'default_sort' ) ) == 'oldest' ? ' class="selected"' : ''; ?>><?php echo JText::_( 'COM_KOMENTO_SORT_OLDEST' );?></a>
	</li>
	<li class="sortLatest kmt-sort-latest kmt-sorting">
		<a href="javascript:void(0);"<?php echo JRequest::getCmd( 'kmt-sort', $system->config->get( 'default_sort' ) ) == 'latest' ? ' class="selected"' : ''; ?>><?php echo JText::_( 'COM_KOMENTO_SORT_LATEST' );?></a>
	</li>
	<?php } ?>
	<?php if( $system->konfig->get( 'enable_admin_mode' ) && ( ( $system->my->id == $componentHelper->getAuthorId() && $system->my->allow( 'author_publish_comment' ) ) || $system->my->allow( 'publish_all_comment' ) ) ) { ?>
	<li class="kmt-admin-mode adminMode">
		<a href="javascript:void(0)"><?php echo JText::_( 'COM_KOMENTO_ADMIN_MODE' ); ?></a>
	</li>
	<?php } ?>

	<?php if( $system->config->get( 'enable_subscription' ) ) {
		$subscribed = null;

		if( $system->my->id )
		{
			$subscribed = Komento::getModel( 'subscription' )->checkSubscriptionExist( $component, $cid, $system->my->id );
		}

		if( is_null( $subscribed ) ) { ?>
		<li class="kmt-subs-email subscribeEmail">
			<a href="javascript:void(0);"><i class="kmt-ico"></i>
				<b><i class="arrow"></i><?php echo JText::_( 'COM_KOMENTO_SUBSCRIBE_EMAIL' ); ?></b>
			</a>
		</li>
		<?php } else { ?>
		<li class="kmt-subs-unemail subscribeEmail">
			<a href="javascript:void(0);"><i class="kmt-ico"></i>
				<b><i class="arrow"></i><?php echo JText::_( 'COM_KOMENTO_FORM_UNSUBSCRIBE' ); ?></b>
			</a>
		</li>
		<?php }
	} ?>

	
</ul>
<?php } ?>
</div>
<?php } ?>
