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
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">

Komento.ready(function($){
	$('a.toolbar').attr('href', 'javascript:void(0)');

	$.Joomla('submitbutton', function(action) {
		window.submitbutton(action);
		// $.Joomla('submitform', [action]);
	});

	Komento.saveColumns = function() {
		$.Joomla('submitbutton', ['saveColumns']);
	};

	Komento.showColumnsConfiguration = function() {
		$('.columnsConfiguration').toggle();
	};
});

Komento.require().script('admin.comment.actions').done();



</script>
<style>
.comment-cell p {margin:0;}
</style>
<form action="index.php?option=com_komento&view=comments" method="post" name="adminForm" id="adminForm">
<?php echo $this->loadTemplate( $this->getTheme() ); ?>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="affectchild" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_komento" />
<input type="hidden" name="view" value="comments" />
<input type="hidden" name="parentid" value="<?php echo $this->escape($this->parentid); ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="c" value="comment" />
<input type="hidden" name="filter_order" value="<?php echo $this->escape($this->order); ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->orderDirection); ?>" />
</form>
