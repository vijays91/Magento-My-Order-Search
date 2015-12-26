<?php
class Learn_Myordersearch_Block_Myordersearch extends Mage_Core_Block_Template {

    public function getAllOrderStatus() {
        $collection = Mage::getModel('sales/order_status')->getCollection();
        $collection->getSelect()->order('label', 'asc');
        return $collection->getData();
    }
    
    public function getPostValue($arg=null) {
        if($arg) {
            return $this->getRequest()->getParam($arg);
        }
        return null;
    }
    
    public function resetUrl($arg=null) {
        return  Mage::helper('core/url')->getCurrentUrl();
    }
    
}