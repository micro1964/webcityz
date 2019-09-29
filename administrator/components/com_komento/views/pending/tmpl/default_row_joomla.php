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

$this->row = Komento::getHelper( 'comment' )->process( $this->row, 1 ); ?>

<tr id="<?php echo 'kmt-' . $this->row->id; ?>" class="<?php echo 'row' . $this->k; ?> kmt-row" childs="<?php echo $this->row->childs; ?>" depth="<?php echo $this->row->depth; ?>" parentid="<?php echo $this->row->parent_id; ?>">

	<!-- Row Number -->
	<td align="center">
		<?php echo isset( $this->pagination ) ? $this->pagination->getRowOffset( $this->i ) : ''; ?>
	</td>

	<!-- Checkbox -->
	<td align="center">
		<?php echo JHTML::_('grid.id', $this->i, $this->row->id); ?>
	</td>

	<!-- Comment
		todo::truncate comments (overflow css)
		todo::better edit hyperlink
	 -->
	<?php if( $this->columns->get( 'column_comment' ) ) { ?>
	<td align="left" class="comment-cell">
		<table>
			<tr>
				<?php if( $this->row->depth > 0 ) {
					echo str_repeat( '<td width="1%">|—</td>', $this->row->depth );
				} ?>
				<td><?php echo $this->row->comment; ?></td>
			</tr>
		</table>
	</td>
	<?php } ?>

	<!-- Publish/Unpublish -->
	<?php if( $this->columns->get( 'column_published' ) ) { ?>
	<td align="center" class="published-cell">
		<?php if( $this->row->published == 2 ) { ?>
			<a title="<?php echo JText::_('COM_KOMENTO_PUBLISH_ITEM'); ?>" onclick="return listItemTask('cb<?php echo $this->i; ?>', 'publish')" href="javascript:void(0);">
				<img alt="<?php echo JText::_('COM_KOMENTO_MODERATE'); ?>" src="components/com_komento/assets/images/pending-favicon.png" />
			</a>
		<?php } else {
			if( $this->row->published != 1 ) {
				$this->row->published = 0;
			}
			echo JHTML::_('grid.published', $this->row, $this->i );
		} ?>
	</td>
	<?php } ?>

	<!-- Link to childs / parent
	todo::check if child exist
	if rgt-lft > 1
	-->
	<?php if( $this->columns->get( 'column_link' ) ) { ?>
	<td align="center" class="linked-cell">
	<?php if( $this->row->parent_id ) { ?>
		<a href="<?php echo JRoute::_( 'index.php?option=com_komento&amp;view=comments&amp;controller=comment&amp;nosearch=1&amp;parentid=' . $this->row->parent_id ); ?>"><?php echo JText::_( 'COM_KOMENTO_VIEW_PARENT' ); ?></a>
		<?php } else {
			echo JText::_( 'COM_KOMENTO_NO_PARENT' );
		}
	?>
	</td>
	<?php } ?>

	<!-- Edit -->
	<?php if( $this->columns->get( 'column_edit' ) ) { ?>
	<td align="center">
		<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comment&amp;task=edit&amp;c=comment&amp;commentid=' . $this->row->id); ?>"><?php echo JText::_('COM_KOMENTO_COMMENT_EDIT'); ?></a>
	</td>
	<?php } ?>

	<!-- Component -->
	<?php if( $this->columns->get( 'column_component' ) ) { ?>
	<td align="center">
		<?php echo $this->row->componenttitle; ?>
	</td>
	<?php } ?>

	<!-- Article Title -->
	<?php if( $this->columns->get( 'column_article' ) ) { ?>
	<td align="center">
		<?php if( $this->row->extension ) { ?>
		<a href="<?php echo $this->row->pagelink; ?>"><?php echo $this->row->contenttitle; ?></a>
		<?php } else { ?>
		<span class="error"><?php echo $this->row->contenttitle; ?></span>
		<?php } ?>
	</td>
	<?php } ?>

	<!-- Article Id -->
	<?php if( $this->columns->get( 'column_cid' ) ) { ?>
	<td align="center">
		<?php if( $this->row->extension ) { ?>
		<a href="<?php echo $this->row->pagelink; ?>"><?php echo $this->row->cid; ?></a>
		<?php } else { ?>
		<span class="error"><?php echo $this->row->cid; ?></span>
		<?php } ?>
	</td>
	<?php } ?>

	<!-- Date -->
	<?php if( $this->columns->get( 'column_date' ) ) { ?>
	<td align="center">
		<?php echo $this->row->created->toMySQL(); ?>
	</td>
	<?php } ?>

	<!-- Author -->
	<?php if( $this->columns->get( 'column_author' ) ) { ?>
	<td align="center">
		<?php echo $this->row->name; ?>
	</td>
	<?php } ?>

	<!-- Email -->
	<?php if( $this->columns->get( 'column_email' ) ) { ?>
	<td align="center">
		<?php echo $this->row->email; ?>
	</td>
	<?php } ?>

	<!-- Homepage -->
	<?php if( $this->columns->get( 'column_homepage' ) ) { ?>
	<td align="center">
		<?php echo $this->row->url; ?>
	</td>
	<?php } ?>

	<!-- IP -->
	<?php if( $this->columns->get( 'column_ip' ) ) { ?>
	<td align="center">
		<?php echo $this->row->ip; ?>
	</td>
	<?php } ?>

	<!-- Latitude -->
	<?php if( $this->columns->get( 'column_latitude' ) ) { ?>
	<td align="center">
		<?php echo $this->row->latitude; ?>
	</td>
	<?php } ?>

	<!-- Longitude -->
	<?php if( $this->columns->get( 'column_longitude' ) ) { ?>
	<td align="center">
		<?php echo $this->row->longitude; ?>
	</td>
	<?php } ?>

	<!-- Address -->
	<?php if( $this->columns->get( 'column_address' ) ) { ?>
	<td align="center">
		<?php echo $this->row->address; ?>
	</td>
	<?php } ?>

	<!-- ID -->
	<?php if( $this->columns->get( 'column_id' ) ) { ?>
	<td align="center">
		<?php if( $this->row->extension ) { ?>
		<a href="<?php echo $this->row->permalink; ?>"><?php echo $this->row->id; ?></a>
		<?php } else { ?>
		<span class="error"><?php echo $this->row->id; ?></span>
		<?php } ?>
	</td>
	<?php } ?>
</tr>
