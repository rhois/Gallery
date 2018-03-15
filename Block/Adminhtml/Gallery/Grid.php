<?php

class Displaystore_Gallery_Block_Adminhtml_Gallery_Grid extends Displaystore_Gallery_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('fileuploadGrid');
        $this->setDefaultSort('gallery_id');
        $this->setDefaultDir('ASC');        
        $this->setSaveParametersInSession(true);
    }    

    protected function _prepareCollection() {
        $collection = Mage::getModel('gallery/gallery')->getCollection();        
        $this->setCollection($collection);
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
            'width' => '80px',
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
        /*
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
        */
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
                            'url' => array('base' => '*/*/edit'),
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

    protected function _prepareMassaction() {

        $this->setMassactionIdField('gallery_id');
        $this->getMassactionBlock()->setFormFieldName('gallery');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('gallery')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('gallery')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('gallery/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('file_status', array(
            'label' => Mage::helper('gallery')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'file_status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('gallery')->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}