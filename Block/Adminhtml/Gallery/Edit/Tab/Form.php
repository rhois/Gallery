<?php
class Displaystore_Gallery_Block_Adminhtml_Gallery_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('gallery_form', array('legend' => Mage::helper('gallery')->__('Item information')));
        
   
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('gallery')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('uploaded_file', 'fileuploader', array(
            'label' => Mage::helper('gallery')->__('File'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'uploaded_file',
        ));
        
        // Thumbnail
        $fieldset->addField('file_content', 'resizeimage', array(
            'label' => Mage::helper('gallery')->__('Thumbnail'),
            'required' => false,
            'name' => 'file_content',
        ));
        // End Thumbnail
        

        $fieldset->addField('file_status', 'select', array(
            'label' => Mage::helper('gallery')->__('Status'),
            'name' => 'file_status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('gallery')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('gallery')->__('Disabled'),
                ),
            ),
        ));
        /*
        $fieldset->addField('content_disp', 'select', array(
            'label' => Mage::helper('gallery')->__('Content-Disposition'),
            'name' => 'content_disp',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('gallery')->__('Attachment'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('gallery')->__('Inline'),
                ),
            ),
        ));
        
        if (Mage::helper('gallery')->getVersionLow()) {
            $fieldset->addField('file_content', 'editor', array(
                'name' => 'file_content',
                'label' => Mage::helper('gallery')->__('Content'),
                'title' => Mage::helper('gallery')->__('Content'),
                'style' => 'width:600px; height:300px;',
                'config' => Mage::getSingleton('gallery/wysiwyg_config')->getConfig(),
                'wysiwyg' => true,
                'required' => false,
            ));
        } else {
            $fieldset->addField('file_content', 'editor', array(
                'name' => 'file_content',
                'label' => Mage::helper('gallery')->__('Content'),
                'title' => Mage::helper('gallery')->__('Content'),
                'style' => 'width:600px; height:300px;',
                'wysiwyg' => false,
                'required' => false,
            ));
        }*/

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('gallery')->__('Sort Order'),
            'name' => 'sort_order',
        ));

        if (Mage::getSingleton('adminhtml/session')->getGalleryData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getGalleryData());
            Mage::getSingleton('adminhtml/session')->setGalleryData(null);
        } elseif (Mage::registry('gallery_data')) {
            $form->setValues(Mage::registry('gallery_data')->getData());
        }

        return parent::_prepareForm();
    }

}