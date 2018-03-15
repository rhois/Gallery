<?php

class Displaystore_Gallery_Adminhtml_GalleryController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('catalog/items')
                ->_addBreadcrumb(Mage::helper('gallery')->__('Items Manager'), Mage::helper('gallery')->__('Item Manager'));
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('gallery/gallery')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
        }
        Mage::register('gallery_data', $model);
        return $this;
    }

    public function indexAction() {
        Mage::helper('unicommon')->c($this->getRequest()->getModuleName());
        $this->_initAction()
                ->renderLayout();
    }

    public function productgridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tab_products')->toHtml()
        );
    }

    public function filegridAction() {
        $this->_initAction();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tab_productfile')->toHtml()
        );
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('gallery/gallery')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('gallery_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('catalog/items');

            $this->_addBreadcrumb(Mage::helper('gallery')->__('Item Manager'), Mage::helper('gallery')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('gallery')->__('Item gallery'), Mage::helper('gallery')->__('Item gallery'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('gallery/adminhtml_gallery_edit'))
                    ->_addLeft($this->getLayout()->createBlock('gallery/adminhtml_gallery_edit_tabs'));
            
            if ((Mage::helper('gallery')->getVersionLow()) && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
                $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            }
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        $filedata = array();
        $uploadedFile = 'uploaded_file';
        $_helper = Mage::helper('gallery');
        $post = $this->getRequest()->getPost();
        //echo '<pre>';
        //print_r($imageInfo);
        //die();
        if (!empty($_FILES[$uploadedFile]['name'])) {
            try {
                
                $fileName = $_FILES["uploaded_file"]["name"]; // The file name
                $fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"]; // File in the PHP tmp folder
                $fileType = $_FILES["uploaded_file"]["type"]; // The type of file it is
                $fileSize = $_FILES["uploaded_file"]["size"]; // File size in bytes
                $fileErrorMsg = $_FILES["uploaded_file"]["error"]; // 0 for false... and 1 for true
                $kaboom = explode(".", $fileName); // Split file name into an array using the dot
                $fileExt = end($kaboom);
                
                if (!$fileTmpLoc) { // if file not chosen
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('ERROR: Please browse for a file before clicking the upload button.'));
                }else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('ERROR: Your file was larger than 5 Megabytes in size.'));
                    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                } else if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
                     // This condition is only if you wish to allow uploading of specific file types
                     Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('ERROR: Your image was not .gif, .jpg, or .png.'));
                     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                } else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('ERROR: An error occured while processing the file. Try again.'));
                }
                
                $path = Mage::getBaseDir('media') . DS . 'custom' . DS . 'upload' . DS. 'gallery' . DS;
                $moveResult = move_uploaded_file($fileTmpLoc, $path."/$fileName");
                // Check to make sure the move result is true before continuing
                if ($moveResult != true) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('ERROR: File not uploaded. Try again.'));
                    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                }
                unlink($fileTmpLoc);

                
                $target_file = $path."/$fileName";
                $resized_file = $path."/gallery_" . strtolower(str_replace(" ","_",$fileName));
                $wmax = 600;
                $hmax = 400;
                
                $this->getImgResize($target_file, $resized_file, $wmax, $hmax, $fileExt);
                //$this->ImageResize($fileName,$fileTmpLoc,$fileType,$wmax,$hmax);
                //$_helper->getImgResize($target_file, $resized_file, $wmax, $hmax, $fileExt);

                /*$ext = $_helper->getFileExtension($_FILES[$uploadedFile]['name']);
                $fname = 'Gallery-' . time() . $ext;
                $path = Mage::getBaseDir('media') . DS . 'custom' . DS . 'upload' . DS. 'gallery' . DS;
                
                $uploader = new Varien_File_Uploader($uploadedFile);
                #$uploader->setAllowedExtensions(array("txt", "csv", "htm", "html", "xml", "css", "doc", "docx", "xls", "xlsx", "rtf", "ppt", "pdf", "swf", "flv", "avi", "wmv", "mov", "wav", "mp3", "jpg", "jpeg", "gif", "png","zip"));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $fname);
                */
                
                // Varien Image
                /*$path = Mage::getBaseDir('media') . DS . 'custom' . DS . 'upload' . DS. 'gallery' . DS;
                $imageTmpName = $_FILES[$uploadedFile]['tmp_name'];
    			$imageInfo = getimagesize($imageTmpName);
    			list ($type, $subtype) = explode('/', $imageInfo['mime']);
    			if($type == 'image'){
    				$image = new Varien_Image($imageTmpName);
    				$image->constrainOnly(false);
    				$image->keepFrame(false);
    				$image->keepAspectRatio(true);
    				$image->keepTransparency(true);
                    $image->resize(600,null);
    				$image->save($path.$imageTmpName);
    			}
                
                $imageUrl = Mage::getBaseDir('media').DS."custom".DS."upload".DS."gallery".DS.$fname;
                $imageResized = Mage::getBaseDir('media').DS."custom".DS."upload".DS."gallery".DS."resize".DS.$fname;
                if (!file_exists($imageResized)&&file_exists($_imageUrl)) :
                	$imageObj = new Varien_Image($_imageUrl);
                	$imageObj->constrainOnly(TRUE);
                	$imageObj->keepAspectRatio(TRUE);
                	$imageObj->keepFrame(FALSE);
                	$imageObj->resize(135, 135);
                	$imageObj->save($imageResized);
                endif;*/
                unlink($target_file);
                $filedata[$uploadedFile] = 'custom/upload/gallery/gallery_' . strtolower(str_replace(" ","_",$fileName));
                $data['file_content'] = $filedata[$uploadedFile];
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        if ($data = $this->getRequest()->getPost()) {
            $products = array();
            //print_r($data);
            //die();
            $availProductIds = Mage::getModel('gallery/gallery')->getAllAvailProductIds();
            parse_str($data['gallery_products'], $products);
            foreach ($products as $k => $v) {
                if (preg_match('/[^0-9]+/', $k) || preg_match('/[^0-9]+/', $v)) {
                    unset($products[$k]);
                }
            }
            //print_r($data['uploaded_file']);
            //die();
            $productIds = array_intersect($availProductIds, $products);
            $data['product_ids'] = implode(',', $productIds);
            $data['file_content'] = $data['uploaded_file']['value'];
            if (!empty($filedata[$uploadedFile])) {
                $data[$uploadedFile] = $filedata[$uploadedFile];
            } else {
                if (isset($data[$uploadedFile]['delete']) && $data[$uploadedFile]['delete'] == 1) {
                    if ($data[$uploadedFile]['value'] != '')
                        $this->removeFile($data[$uploadedFile]['value']);
                    $data[$uploadedFile] = '';
                }else {
                    unset($data[$uploadedFile]);
                }
            }
            
            //resize
            if(!empty($data['resizevalue'])){
                $fileName = explode('/',$data['file_content']);
                
                $path = Mage::getBaseDir('media') . DS . 'custom' . DS . 'upload' . DS. 'gallery' . DS;
                
                $baseFromJavascript = $data['resizevalue'];
                
                $fileDec = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $baseFromJavascript));

                $filepath = $path."/thumb/" .$fileName[3]; // or image.jpg
                
                // Save the image in a defined path
                file_put_contents($filepath,$fileDec);
                
            }
            
            $model = Mage::getModel('gallery/gallery');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gallery')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }


        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $id = $this->getRequest()->getParam('id');
                $fileupload = Mage::getModel('gallery/gallery')->load($id);
                if ($fileupload['uploaded_file'] != '')
                    $this->removeFile($fileupload['uploaded_file']);
                $fileupload->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gallery')->__('File was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $fileuploadIds = $this->getRequest()->getParam('gallery');
        if (!is_array($fileuploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('Please select item(s)'));
        } else {
            try {
                foreach ($fileuploadIds as $fileuploadId) {
                    $fileupload = Mage::getModel('gallery/gallery')->load($fileuploadId);
                    if ($fileupload['uploaded_file'] != '')
                        $this->removeFile($fileupload['uploaded_file']);
                    $fileupload->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('gallery')->__(
                                'Total of %d record(s) were successfully deleted', count($fileuploadIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $fileuploadIds = $this->getRequest()->getParam('gallery');
        if (!is_array($fileuploadIds)) {
            Mage::getModel('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($fileuploadIds as $fileuploadId) {
                    $fileupload = Mage::getSingleton('gallery/gallery')
                                    ->load($fileuploadId)
                                    ->setFileStatus($this->getRequest()->getParam('file_status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($fileuploadIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function removeFile($file) {
        $_helper = Mage::helper('gallery');
        $file = $_helper->updateDirSepereator($file);
        $directory = Mage::getBaseDir('media') . DS . $file;
        $io = new Varien_Io_File();
        $result = $io->rmdir($directory, true);
    }
    
    public function getImgResize($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        
        // Skala 1
        /*$scale_ratio = $w_orig / $h_orig; // 749:998=0,750501002004008
        if(($w / $h) > $scale_ratio) { // 600:400 = 1.5
            $w = $h * $scale_ratio; // width = 400x0,750501002004008 = 300
        }else{
            $h = $w / $scale_ratio;  // height = 600:0,750501002004008 = 799
        }*/
        
        /*// Skala 2
        // width = 600 , height = 400
        $ratio = max($w/$w_orig, $h/$h_orig); // 600:749 = 0.8 , 400:998 = 0.4
        
        if(($w / $h) > $ratio) { // 600:400 = 1.5
            $w = $h * $ratio; // width = 400x0,8 = 300
        }else{
            $h = $w / $ratio;  // height = 600:0,8 = 799
        }*/
        $scale_ratio = $w_orig / $h_orig; // 600:749 = 0.8 , 400:998 = 0.4
        $hTemp = (($h_orig * $w) / $w_orig);
        $wTemp = (($w_orig * $h) / $h_orig);
        
        if($hTemp >= 600){
            $h = 600;
            $w = $h*$scale_ratio;
        }else{
            $w = 600;
            $h = $w/$scale_ratio;
        }
        
        //$h = (($h_orig * $w) / $w_orig);
        
        
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
          $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
          $img = imagecreatefrompng($target);
        } else { 
          $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
        //imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 80);
    }

}