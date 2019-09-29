<?php
/*------------------------------------------------------------------------
# com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Ramesh Elamathi - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'library'.DS.'plugins'.DS.'payment.php');

class plgK2StorePayment_offline extends K2StorePaymentPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element    = 'payment_offline';

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgK2StorePayment_offline(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}


    /**
     * Prepares the payment form
     * and returns HTML Form to be displayed to the user
     * generally will have a message saying, 'confirm entries, then click complete order'
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
        // prepare the payment form
        $vars = new JObject();
        $vars->order_id = $data['order_id'];
        $vars->orderpayment_id = $data['orderpayment_id'];
        $vars->orderpayment_amount = $data['orderpayment_amount'];
        $vars->orderpayment_type = $this->_element;
        $vars->offline_payment_method = $data['offline_payment_method'];

        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }

    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _postPayment( $data )
    {
        // Process the payment
        $vars = new JObject();
        $orderpayment_id = JRequest::getVar('orderpayment_id');
        $offline_payment_method = JRequest::getVar('offline_payment_method');
        $formatted = array(
                        'offline_payment_method' => $offline_payment_method
                        );

       // load the orderpayment record and set some values
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2store'.DS.'tables' );
        $orderpayment = JTable::getInstance('Orders', 'Table');

        //remove this after live.
        //$orderpayment_id = substr($orderpayment_id, 2, 3);

        $orderpayment->load( $orderpayment_id );
        $orderpayment->transaction_details = implode("\n", $formatted);
        //$orderpayment->transaction_status = JText::_('Pending_Payment');
        //$orderpayment->order_state = JText::_('Pending');
        $orderpayment->transaction_status = JText::_('K2STORE_PENDING_PAYMENT');
        $orderpayment->order_state =JText::_('K2STORE_PENDING');
        $orderpayment->order_state_id = 4; // PENDING

       // save the orderpayment
        if ($orderpayment->save()) {
			JLoader::register( 'K2StoreHelperCart', JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'helpers'.DS.'cart.php');
			 // remove items from cart
            K2StoreHelperCart::removeOrderItems( $orderpayment->id );
        }
        else
        {
        	$errors[] = $orderpayment->getError();
        }

         // let us inform the user that the order is successful
        require_once (JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'helpers'.DS.'orders.php');
        K2StoreOrdersHelper::sendUserEmail($orderpayment->user_id, $orderpayment->order_id, $orderpayment->transaction_status, $orderpayment->order_state, $orderpayment->order_state_id);

        // display the layout
        $html = $this->_getLayout('postpayment', $vars);

        // append the article with offline payment information
        $html .= $this->_displayArticle();

        return $html;
    }

    /**
     * Prepares variables and
     * Renders the form for collecting payment info
     *
     * @return unknown_type
     */
    function _renderForm( $data )
    {
    	$user = JFactory::getUser();
        $vars = new JObject();
        $vars->payment_method   = $this->_paymentMethods();

        $html = $this->_getLayout('form', $vars);

        return $html;
    }

    /**
     * Verifies that all the required form fields are completed
     * if any fail verification, set
     * $object->error = true
     * $object->message .= '<li>x item failed verification</li>'
     *
     * @param $submitted_values     array   post data
     * @return unknown_type
     */
    function _verifyForm( $submitted_values )
    {
        $object = new JObject();
        $object->error = false;
        $object->message = '';
        $user = JFactory::getUser();

        foreach ($submitted_values as $key=>$value)
        {
            switch ($key)
            {
                case "offlinetype":
                    if (!isset($submitted_values[$key]) || !JString::strlen($submitted_values[$key]))
                    {
                        $object->error = true;
                        $object->message .= "<li>".JText::_( "K2STORE_OFFLINE_PAYMENT_TYPE_INVALID" )."</li>";
                    }
                  break;
                default:
                  break;
            }
        }

        return $object;
    }


    /**
     * Generates a dropdown list of valid payment methods
     * @param $fieldname
     * @param $default
     * @param $options
     * @return unknown_type
     */
    function _paymentMethods( $field='offline_payment_method', $default='', $options='' )
    {
        $types = array();
         if ($this->params->get('enable_cod')) {
            $types[] = JHTML::_('select.option', JText::_( "K2STORE_COD" ), JText::_( "K2STORE_COD" ) );
        }
        if ($this->params->get('enable_check')) {
            $types[] = JHTML::_('select.option', JText::_( "K2STORE_CHECK" ), JText::_( "K2STORE_CHECK" ) );
        }
        if ($this->params->get('enable_moneyorder')) {
            $types[] = JHTML::_('select.option', JText::_( "K2STORE_MONEY_ORDER" ), JText::_( "K2STORE_MONEY_ORDER" ) );
        }
        if ($this->params->get('enable_wire')) {
            $types[] = JHTML::_('select.option', JText::_( "K2STORE_WIRE_TRANSFER" ), JText::_( "K2STORE_WIRE_TRANSFER" ) );
        }
        if ($this->params->get('enable_other')) {
            $types[] = JHTML::_('select.option', JText::_( "K2STORE_OTHER" ), JText::_( "K2STORE_OTHER" ) );
        }
        if(count($types)) {
			$return = JHTML::_('select.genericlist', $types, $field, $options, 'value','text', $default);
		} else {
			$return = '';
		}
        return $return;
    }
}
