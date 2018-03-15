<?php

class Displaystore_Gallery_Model_Gallery extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('gallery/gallery');
    }

    public function getAllAvailProductIds(){
        $collection = Mage::getResourceModel('catalog/product_collection')
                        ->getAllIds();
        return $collection;
    }

    public function getFilesByProductId($productId) {
        $data = array();
        $collection = Mage::getResourceModel('gallery/gallery_collection');
        $collection->addFieldToFilter('product_ids', array('finset' => $productId))        
                ->addFieldToFilter('file_status', 1);
        $collection->getSelect()->order('sort_order');
        return $collection->toArray();
    }

}