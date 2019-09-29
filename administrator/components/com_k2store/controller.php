<?php

// controller 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
/**
 * General Controller of Subscription component
 */
class K2StoreController extends JController
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false) 
        {
        		// set default view if not set
               JRequest::setVar('view', JRequest::getWord('view', 'cpanel'));
                // call parent behavior
                parent::display();
                return $this;
        }
}
