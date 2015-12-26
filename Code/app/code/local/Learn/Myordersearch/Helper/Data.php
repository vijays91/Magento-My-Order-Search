<?php
class Learn_Myordersearch_Helper_Data extends Mage_Core_Helper_Abstract  {

    const XML_PATH_MY_ORDER_SEARCH_ENABLE = 'myordersearch_tab/myordersearch_setting/myordersearch_active';
    
    public function conf($code, $store = null) {
        return Mage::getStoreConfig($code, $store);
    }
    
    /*- My Order Search Enable -*/
	public function myordersearch_enable($store) {
        return $this->conf(self::XML_PATH_MY_ORDER_SEARCH_ENABLE, $store);
    }
    
    /*- Template -*/
	public function myordersearch_template($store) {
        return "myordersearch/search.phtml";
	}
	
    
}