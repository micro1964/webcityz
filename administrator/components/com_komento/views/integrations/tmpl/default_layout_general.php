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
Komento.ready(function($) {
	window.resetDefaultClassNames = function()
	{
		$('#layout_css_admin').val("<?php echo $this->config->default->layout_css_admin; ?>");
		$('#layout_css_author').val("<?php echo $this->config->default->layout_css_author; ?>");
		$('#layout_css_registered').val("<?php echo $this->config->default->layout_css_registered; ?>");
		$('#layout_css_public').val("<?php echo $this->config->default->layout_css_public; ?>");
	}
});
</script>

<table class="noshow">
	<tr>
		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_GENERAL' ); ?></legend>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_UPGRADE_TO_GET_THEMES' ); ?> <a href="http://stackideas.com/komento/plans.html" target="_blank"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_UPGRADE_NOW' ); ?></a></p>
			<table class="admintable" cellspacing="1">
				<tbody>
					<!-- Themes Selection -->
					<?php
						$result		= JFolder::folders( KOMENTO_THEMES , '.', false , true , $exclude = array('.svn', 'CVS' , '.' , '.DS_Store', '__MACOSX' ) );
						$options	= array();

						foreach( $result as $item )
						{
							$options[] = array( basename($item), ucfirst(basename($item)) );
						}

						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_THEME', 'layout_theme', 'dropdown', $options );
					?>

					<!-- Enable Tabbed Comments -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_TABBED_COMMENTS', 'tabbed_comments' ); ?>

					<!-- Max Threaded Level -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_COMMENTS_MAX_THREADED_LEVEL', 'max_threaded_level', 'input' ); ?>

					<!-- Enable threaded view -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_THREADED_VIEW_ENABLE', 'enable_threaded' ); ?>

					<!-- Warning message for sorting -->
					<?php echo $this->renderText( JText::_( 'COM_KOMENTO_SETTINGS_DEFAULT_SORT_WARNING' ) ); ?>

					<!-- Default sorting (for non threaded view) -->
					<?php $options = array();
						$options[] = array( 'oldest', 'COM_KOMENTO_SETTINGS_SORT_OLDEST' );
						$options[] = array( 'latest', 'COM_KOMENTO_SETTINGS_SORT_LATEST' );
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_DEFAULT_SORT', 'default_sort', 'dropdown', $options );
					?>

					<!-- Switch between Load More or Load Previous -->
					<?php $options = array();
						$options[] = array( '0', 'COM_KOMENTO_SETTINGS_LOAD_BAR_BOTTOM' );
						$options[] = array( '1', 'COM_KOMENTO_SETTINGS_LOAD_BAR_TOP' );
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LOAD_BAR_POSITION', 'load_previous', 'dropdown', $options );
					?>

					<!-- Max Comment Per Page -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_COMMENTS_MAX_PER_PAGE', 'max_comments_per_page', 'input' ); ?>

				</tbody>
			</table>
			</fieldset>

			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE' ); ?></legend>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_DESC' );?></p>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Show Comment Link in Frontpage -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_COMMENTS', 'layout_frontpage_comment' ); ?>

					<!-- Show ReadMore Link in Frontpage -->
					<?php if( $this->component == 'com_content' ) {
						$options = array();
						$options[] = array( 0, 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_READMORE_ALWAYS_HIDE' );
						$options[] = array( 1, 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_READMORE_OBEY_READMORE_BREAK' );
						$options[] = array( 2, 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_READMORE_ALWAYS_SHOW' );
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_READMORE', 'layout_frontpage_readmore', 'dropdown', $options );

						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_READMORE_USE_JOOMLA', 'layout_frontpage_readmore_use_joomla' );
					} else {
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_READMORE', 'layout_frontpage_readmore' );
					}?>

					<!-- Show Hits in Frontpage -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_HITS', 'layout_frontpage_hits' ); ?>

					<!-- Set comment bar alignment -->
					<?php $options = array();
						$options[] = array( 'left', 'COM_KOMENTO_ALIGNMENT_LEFT' );
						$options[] = array( 'right', 'COM_KOMENTO_ALIGNMENT_RIGHT' );
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_ALIGNMENT', 'layout_frontpage_alignment', 'dropdown', $options );
					?>
				</tbody>
			</table>
			</fieldset>

			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_CSS_CONTROL' ); ?></legend>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_CSS_CONTROL_DESC' ); ?></p>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Admin CSS Class -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CSS_CLASS_ADMIN', 'layout_css_admin', 'input', '60' ); ?>

					<!-- Registered CSS Class -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CSS_CLASS_REGISTERED', 'layout_css_registered', 'input', '60' ); ?>

					<!-- Author CSS Class -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CSS_CLASS_CONTENT_AUTHOR', 'layout_css_author', 'input', '60' ); ?>

					<!-- Publis CSS Class -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_CSS_CLASS_PUBLIC', 'layout_css_public', 'input', '60' ); ?>

					<!-- Reset to default classname -->
					<tr>
						<td width="300" class="key">
						</td>
						<td valign="top">
							<div class="has-tip">
								<div class="tip"><i></i><?php echo JText::_( 'COM_KOMENTO_SETTINGS_CSS_CLASS_RESET_DESC' ); ?></div>
								<a href="javascript:void(0);" onclick="resetDefaultClassNames()"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_CSS_CLASS_RESET' ); ?></a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			</fieldset>
		</td>

		<td width="50%" valign="top">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_ITEM' ); ?></legend>
			<p class="small"><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_ITEM_DESC' );?></p>
			<table class="admintable" cellspacing="1">
				<tbody>

					<!-- Enable Lapsed Time -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_COMMENTS_USE_LAPSED_TIME', 'enable_lapsed_time' ); ?>

					<!-- Enable Avatar -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_LAYOUT_AVATAR_ENABLE', 'layout_avatar_enable' ); ?>

					<!-- Enable Guest Links -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_GUEST_LINK_ENABLE', 'enable_guest_link' ); ?>

					<!-- Enable Permalink -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_PERMALINK_ENABLE', 'enable_permalink' ); ?>

					<!-- Enable Share -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_SHARE_ENABLE', 'enable_share' ); ?>

					<!-- Enable Comment Likes -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LIKES_ENABLE', 'enable_likes' ); ?>

					<!-- Enable Comment Replies -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_REPLY_ENABLE', 'enable_reply' ); ?>

					<!-- Enable Comment Reporting -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_REPORT_ENABLE', 'enable_report' ); ?>

					<!-- Enable Comment Location -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LOCATION_ENABLE', 'enable_location' ); ?>

					<!-- Enable Comment Info -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_INFO_ENABLE', 'enable_info' ); ?>

					<!-- Enable Syntax Highlighting -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_SYNTAX_HIGHLIGHTING_ENABLE', 'enable_syntax_highlighting' ); ?>

				</tbody>
			</table>
			</fieldset>

			<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KOMENTO_SETTINGS_LAYOUT_APPEARANCE' ); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
					<!-- Username/Real Name/Stored Name -->
					<?php $options = array();
						$options[] = array( 'default', 'COM_KOMENTO_SETTINGS_NAME_TYPE_DEFAULT' );
						$options[] = array( 'username', 'COM_KOMENTO_SETTINGS_NAME_TYPE_USERNAME' );
						$options[] = array( 'name', 'COM_KOMENTO_SETTINGS_NAME_TYPE_NAME' );
						echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_COMMENTS_NAME_TYPE', 'name_type', 'dropdown', $options );
					?>

					<!-- nofollow for links -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_LINKS_NOFOLLOW', 'links_nofollow' ); ?>

					<!-- Date format -->
					<?php echo $this->renderText( '<a href="http://php.net/manual/en/function.date.php" target="_blank">' . JText::_( 'COM_KOMENTO_SETTINGS_DATE_FORMAT_REFERENCE' ) . '</a>' ); ?>
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_DATE_FORMAT', 'date_format', 'input', 10 ); ?>

					<!-- Image Width -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_IMAGE_WIDTH', 'max_image_width', 'input', array( 'posttext' => ' px' ) ); ?>

					<!-- Image Height -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_IMAGE_HEIGHT', 'max_image_height', 'input', array( 'posttext' => ' px' ) ); ?>

					<!-- Allow Video -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_ALLOW_VIDEO', 'allow_video' ); ?>

					<!-- Video Width -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_VIDEO_WIDTH', 'bbcode_video_width', 'input', array( 'posttext' => ' px' ) ); ?>

					<!-- Video Height -->
					<?php echo $this->renderSetting( 'COM_KOMENTO_SETTINGS_LAYOUT_VIDEO_HEIGHT', 'bbcode_video_height', 'input', array( 'posttext' => ' px' ) ); ?>
				</tbody>
			</table>
			</fieldset>
		</td>
	</tr>
</table>
