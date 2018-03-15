<?php
class Displaystore_Gallery_Model_Observer_Product {

    public function injectTabs(Varien_Event_Observer $observer) {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
            if (($this->_getRequest()->getActionName() == 'edit' || $this->_getRequest()->getParam('type')) && $this->_getRequest()->getParam('id')) {
                $block->addTab('attachment_section', array(
                    'label' => Mage::helper('gallery')->__('Gallery'),
                    'alt' => Mage::helper('gallery')->__('Gallery'),
                    'content' => $block->getLayout()->createBlock('gallery/adminhtml_gallery_grid_gridfile')->toHtml(),
                ));
            }
        }
    }

    protected function _getRequest() {
        return Mage::app()->getRequest();
    }

}