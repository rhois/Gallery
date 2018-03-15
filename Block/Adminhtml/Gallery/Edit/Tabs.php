<?php
class Displaystore_Gallery_Block_Adminhtml_Gallery_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('gallery_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('gallery')->__('Item Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('gallery')->__('File Information'),
            'title' => Mage::helper('gallery')->__('File Information'),
            'content' => $this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tab_form')->toHtml(),
        ));

        $this->addTab('grid_section', array(
            'label' => Mage::helper('gallery')->__('Product Information'),
            'alt' => Mage::helper('gallery')->__('Product Information'),
            'content' => $this->getLayout()->createBlock('gallery/adminhtml_gallery_grid_gridproduct')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}