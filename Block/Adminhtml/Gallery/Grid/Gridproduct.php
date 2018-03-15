<?php

class Displaystore_Gallery_Block_Adminhtml_Gallery_Grid_Gridproduct extends Mage_Adminhtml_Block_Widget_Container {

    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();        
        $this->setTemplate('gallery/products.phtml');
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    protected function _prepareLayout() {
        $this->setChild('tabs', $this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tab_products', 'gallery.grid.products'));
//        $this->setChild('store_switcher',
//            $this->getLayout()->createBlock('adminhtml/store_switcher')
//                ->setUseConfirm(false)
//                ->setSwitchUrl($this->getUrl('*/*/edit', array('id'=>$this->getRequest()->getParam('id'),'_confirm'=>false,'store'=>null)))
//                ->setTemplate('store/switcher.phtml'));
        return parent::_prepareLayout();
    }

    protected function getGalleryData() {
        return Mage::registry('gallery_data');
    }

    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }

    public function isSingleStoreMode() {
        if (!Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }

    public function getProductsJson() {
        $products = explode(',', $this->getGalleryData()->getProductIds());
        if (!empty($products) && isset($products[0]) && $products[0] != '') {
            $data = array();
            foreach ($products as $element) {
                $data[$element] = $element;
            }
            return Zend_Json::encode($data);
        }
        return '{}';
    }

}
