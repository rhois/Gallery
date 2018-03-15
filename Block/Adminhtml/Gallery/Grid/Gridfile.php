<?php

class Displaystore_Gallery_Block_Adminhtml_Gallery_Grid_Gridfile extends Mage_Adminhtml_Block_Widget_Container {

    /**
     * Set template
     */
    public function __construct() {
        parent::__construct();        
        $this->setTemplate('gallery/tabs.phtml');
    }

    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }

    protected function _prepareLayout() {
        $this->setChild('tabs', $this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tab_productfile', 'product.grid.gallery'));
        return parent::_prepareLayout();
    }

}
