<?php

class Displaystore_Gallery_Block_Adminhtml_Gallery_Edit_Tab_Productfile extends Displaystore_Gallery_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('fileproductGrid');
        $this->setDefaultSort('gallery_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $productId = $this->getRequest()->getParam('id');
        if ($productId) {
            $collection = Mage::getModel('gallery/gallery')->getCollection();
            $collection->addFieldToFilter('product_ids', array('finset' => $productId));
            $this->setCollection($collection);
        }
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('gallery_id', array(
            'header' => Mage::helper('gallery')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'gallery_id',
        ));
        $this->addColumn('title', array(
            'header' => Mage::helper('gallery')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('product_ids', array(
            'header' => Mage::helper('gallery')->__('Products'),
            'align' => 'left',
            'index' => 'product_ids',
        ));

        $this->addColumn('uploaded_file', array(
            'header' => Mage::helper('gallery')->__('File'),
            'align' => 'left',
            'type' => 'file',
            'escape' => true,
            'sortable' => false,
            'index' => 'uploaded_file',
        ));

        $this->addColumn('content_disp', array(
            'header' => Mage::helper('gallery')->__('Content-Disposiotion'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'content_disp',
            'type' => 'options',
            'options' => array(
                0 => 'Attachment',
                1 => 'Inline',
            ),
        ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('gallery')->__('Sort Order'),
            'width' => '80px',
            'index' => 'sort_order',
            'align' => 'center',
        ));

        $this->addColumn('file_status', array(
            'header' => Mage::helper('gallery')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'file_status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));
        $this->addColumn('action',
                array(
                    'header' => Mage::helper('gallery')->__('Action'),
                    'width' => '80',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('gallery')->__('Edit'),
                            'url' => array('base' => 'gallery/adminhtml_gallery/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
        ));
        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('gallery/adminhtml_gallery/filegrid', array('_current' => true));
    }
    public function getRowUrl() {
        return '#';
    }

}
?>