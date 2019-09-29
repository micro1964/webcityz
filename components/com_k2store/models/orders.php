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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'components'.DS.'com_k2store'.DS.'models'.DS.'_base.php' );

class K2StoreModelOrders extends K2StoreModelBase {

	public function getList($refresh = false)
	{
	    if (empty( $this->_list ))
	    {
			 $list = parent::getList($refresh = false);
			if( empty( $list ) ){
                return array();
            }
         $this->_list = $list;
		}

		return $this->_list;
	}


   protected function _buildQueryWhere(&$query)
    {
        $filter     = $this->getState('filter');
       	$filter_orderstate	= $this->getState('filter_orderstate');
       	$filter_userid	= $this->getState('filter_userid');
        $filter_user	= $this->getState('filter_user');
        $filter_ordernumber    = $this->getState('filter_ordernumber');
        $filter_orderstates = $this->getState('filter_orderstates');

       	if ($filter)
       	{
			$key	= $this->_db->Quote('%'.$this->_db->escape( trim( strtolower( $filter ) ) ).'%');

			$where = array();

			$where[] = 'LOWER(tbl.order_id) LIKE '.$key;
			$where[] = 'LOWER(oi.billing_first_name) LIKE '.$key;
			$where[] = 'LOWER(oi.billing_last_name) LIKE '.$key;
			$where[] = 'LOWER(u.email) LIKE '.$key;
			$where[] = 'LOWER(u.username) LIKE '.$key;
			$where[] = 'LOWER(u.name) LIKE '.$key;

			$query->where('('.implode(' OR ', $where).')');
       	}


    	if (strlen($filter_user))
        {
			$key	= $this->_db->Quote('%'.$this->_db->escape( trim( strtolower( $filter_user ) ) ).'%');

			$where = array();
			$where[] = 'LOWER(oi.billing_first_name) LIKE '.$key;
			$where[] = 'LOWER(oi.billing_last_name) LIKE '.$key;
			$where[] = 'LOWER(u.email) LIKE '.$key;
			$where[] = 'LOWER(u.username) LIKE '.$key;
			$where[] = 'LOWER(u.name) LIKE '.$key;
			$where[] = 'LOWER(u.id) LIKE '.$key;
			$query->where('('.implode(' OR ', $where).')');
       	}

        if (strlen($filter_orderstate))
        {
            $query->where('tbl.order_state_id = '.$this->_db->Quote($filter_orderstate));
        }

        if (is_array($filter_orderstates) && !empty($filter_orderstates))
        {
            $query->where('tbl.order_state_id IN('.implode(",", $filter_orderstates).')' );
        }

        if (strlen($filter_userid))
        {
            $query->where('tbl.user_id = '.$this->_db->Quote($filter_userid));
        }

    }

	protected function _buildQueryFields(&$query)
	{
		$field = array();

		$field[] = " tbl.* ";

		$field[] = " u.name AS user_name ";
		$field[] = " u.username AS user_username ";
		$field[] = " u.email ";
		$field[] = " oi.user_email";
		$field[] = " oi.billing_first_name";
		$field[] = " oi.billing_last_name";
		$field[] = " oi.billing_address_1";
		$field[] = " oi.billing_address_2";
		$field[] = " oi.billing_city";
		$field[] = " oi.billing_zip";
		$field[] = " oi.billing_zone_name";
		$field[] = " oi.billing_country_name";
		$field[] = " oi.billing_phone_1";
		$field[] = " oi.billing_phone_2";
		$field[] = " oi.shipping_first_name";
		$field[] = " oi.shipping_last_name";
		$field[] = " oi.shipping_address_1";
		$field[] = " oi.shipping_address_2";
		$field[] = " oi.shipping_city";
		$field[] = " oi.shipping_zip";
		$field[] = " oi.shipping_zone_name";
		$field[] = " oi.shipping_country_name";
		$field[] = " oi.shipping_phone_1";
		$field[] = " oi.shipping_phone_2";
//		$field[] = " ui.address_1 ";
//		$field[] = " ui.address_2 ";
//		$field[] = " ui.city ";
//		$field[] = " ui.zip ";
	//	$field[] = " ui.state ";
	//	$field[] = " ui.country ";
	//	$field[] = " ui.phone_1 ";
	//	$field[] = " ui.phone_2 ";
	//	$field[] = " ui.fax ";
	//	$field[] = " ui.first_name as first_name";
	//	$field[] = " ui.last_name as last_name";
	//	$field[] = " uiz.zone_name as state";
	//	$field[] = " uic.country_name as country";

        $field[] = "
            (
            SELECT
                COUNT(*)
            FROM
                #__k2store_orderitems AS items
            WHERE
                items.order_id = tbl.order_id
            )
            AS items_count
        ";

		$query->select( $field );
	}

	protected function _buildQueryJoins(&$query)
	{
		$query->join('LEFT', '#__k2store_orderinfo AS oi ON oi.orderpayment_id = tbl.id');
//		$query->join('LEFT', '#__k2store_countries AS uic ON ui.country_id = uic.country_id');
//		$query->join('LEFT', '#__k2store_countries AS uiz ON ui.zone_id = tbl.country_id');
		$query->join('LEFT', '#__users AS u ON u.id = tbl.user_id');
	}

    protected function _buildQueryOrder(&$query)
    {
		$order      = $this->_db->escape( $this->getState('order') );
       	$direction  = $this->_db->escape( strtoupper($this->getState('direction') ) );
		if ($order)
		{
       		$query->order("$order $direction");
       	}
       	else
       	{
            $query->order("tbl.id ASC");
       	}
    }

	public function getItem($emptyState=true)
	{
	    if (empty( $this->_item ))
	    {

            JModelLegacy::addIncludePath( JPATH_SITE.'/components/com_k2store/models' );

            $query = $this->getQuery();

			// TODO Make this respond to the model's state, so other table keys can be used
			// perhaps depend entirely on the _buildQueryWhere() clause?
			$keyname = $this->getTable()->getKeyName();
			$value	= $this->_db->Quote( $this->getId() );
			$query->where( "tbl.$keyname = $value" );
			$this->_db->setQuery( (string) $query );
			$item = $this->_db->loadObject();
            if ($item)
            {

                //retrieve the order's items
                $model = JModelLegacy::getInstance( 'OrderItems', 'K2StoreModel' );
                $model->setState( 'filter_orderid', $item->order_id);
                $model->setState( 'order', 'tbl.orderitem_name' );
                $model->setState( 'direction', 'ASC' );
                $item->orderitems = $model->getList();
                foreach ($item->orderitems as $orderitem)
                {
                    $model = JModelLegacy::getInstance( 'OrderItemAttributes', 'K2StoreModel' );
                    $model->setState( 'filter_orderitemid', $orderitem->orderitem_id);
                    $attributes = $model->getList();
                    $attributes_names = array();
                    $attributes_codes = array();
                    foreach ($attributes as $attribute)
                    {
                        // store a csv of the attrib names
                        $attributes_names[] = $attribute->orderitemattribute_name;
                        if($attribute->orderitemattribute_code)
                            $attributes_codes[] = JText::_( $attribute->orderitemattribute_code );
                    }
                    $orderitem->attributes_names = implode(', ', $attributes_names);
                    $orderitem->attributes_codes = implode(', ', $attributes_codes);

                    // adjust the price
                    $orderitem->orderitem_price = $orderitem->orderitem_price + floatval($orderitem->orderitem_attributes_price);
                }


            }

            $this->_item = $item;
	    }

        return $this->_item;
	}

	function loadFromToken($token, $email) {

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__k2store_orders');
		$query->where('user_email='.$db->quote($email));
		$query->where('token='.$db->quote($token));
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

}
