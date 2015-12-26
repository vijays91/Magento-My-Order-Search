<?php
class Learn_Myordersearch_Block_Order_History extends Mage_Sales_Block_Order_History
{

    public function __construct()
    {
        parent::__construct();
        
        $this->setTemplate('sales/order/history.phtml');
        $postData = $this->getRequest()->getParams();        
        if($postData['myorder_txt'] || $postData['myorder_status']) {        
            $orders = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()));
            $orders->setOrder('main_table.created_at', 'desc');
            if($searchKeyword = strtolower($postData['myorder_txt'])) {
                $orders->getSelect()->joinLeft(array('sub' => $orders->getTable('sales/order_item')), 'sub.order_id=main_table.entity_id', array('sub.sku'));
                $orders->getSelect()->joinLeft(array('order_address' => $orders->getTable('sales/order_address')),  'main_table.entity_id=order_address.parent_id', array('order_address.parent_id'));
                /* $orders->addFieldToFilter('order_address.address_type', 'shipping'); */
                $orders->getSelect()->where("
                    main_table.increment_id         like '%$searchKeyword%' OR 
                    LOWER(main_table.coupon_code)   like '%$searchKeyword%' OR
                    LOWER(sub.name)                 like '%$searchKeyword%' OR 
                    LOWER(sub.sku)                  like '%$searchKeyword%' OR 
                    LOWER(order_address.firstname)  like '%$searchKeyword%' OR 
                    LOWER(order_address.lastname)   like '%$searchKeyword%' OR 
                    LOWER(order_address.company)    like '%$searchKeyword%' OR 
                    LOWER(order_address.street)     like '%$searchKeyword%' OR 
                    LOWER(order_address.region)     like '%$searchKeyword%' OR
                    LOWER(order_address.city)       like '%$searchKeyword%' OR 
                    LOWER(order_address.postcode)   like '%$searchKeyword%' OR
                    LOWER(order_address.telephone)  like '%$searchKeyword%' 
                ");
                $orders->getSelect()->group('sub.order_id');
                /* $orders->getSelect()->group('main_table.entity_id'); */
            }
            if($postData['myorder_status']) {
                $orders->getSelect()->where(" main_table.status='". trim($postData['myorder_status'])."'");
            }
            /* echo $orders->getSelect(); */
        } else {
            $orders = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
                ->setOrder('created_at', 'desc');
        }
        $this->setOrders($orders);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(
            Mage::helper('sales')->__('My Orders')
        );
    }

}
