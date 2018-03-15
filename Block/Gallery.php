<?php
class Displaystore_Gallery_Block_Gallery extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getProductAttachments($productId=0) {
        $attach = array();
        $_helper = Mage::helper('gallery');
        $data = Mage::getModel('gallery/gallery')->getFilesByProductId($productId);
        $totalFiles = $data['totalRecords'];
        if ($totalFiles > 0) {
            $record = $data['items'];
            $i=0;
            foreach ($record as $rec) {
                $i++;
                $file = $_helper->getFilesHtml($rec['uploaded_file'], $rec['title'],$i,true,$rec['content_disp'],true);
                $files = $rec['uploaded_file'];
                $attach[] = array('title' => $rec['title'], 'file' => $files, 'content' => $rec['file_content']);
            }
        }
        return $attach;
    }

}