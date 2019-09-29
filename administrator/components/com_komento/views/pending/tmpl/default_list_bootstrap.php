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

if( $this->comments ) {
	// alternating row colour
	$this->k = 0;

	$this->config	= JFactory::getConfig();

	$commentCount = count( $this->comments );

	// for ajax insert purposes
	$this->i = isset( $this->startCount ) ? $this->startCount : 0;

	foreach( $this->comments as $this->row )
	{
		echo $this->loadTemplate( 'row_bootstrap' );

		$this->k = 1 - $this->k;
		$this->i++;
	}
} else { ?>
	<tr>
		<td align="center" colspan="<?php echo $this->columnCount; ?>">
			<?php echo JText::_( 'COM_KOMENTO_COMMENTS_NO_COMMENT' );?>
		</td>
	</tr>
<?php }
