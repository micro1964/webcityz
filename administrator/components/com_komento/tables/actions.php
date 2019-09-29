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

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'parent.php' );

class KomentoTableActions extends KomentoParentTable
{
	/**
	 * The id of the category
	 * @var int
	 */
	public $id 					= null;

	/**
	 * The id of the category
	 * @var string
	 */
	public $type 					= null;

	/**
	 * Comment ID
	 * @var int
	 */
	public $comment_id				= null;

	/**
	 * user ID
	 * @var int
	 */
	public $action_by				= null;

	/**
	 * action datetime
	 * @var datetime
	 */
	public $actioned				= null;

	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__komento_actions' , 'id' , $db );
	}
}
