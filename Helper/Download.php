<?php


class Displaystore_Gallery_Helper_Download extends Mage_Downloadable_Helper_Download {
    protected function _prepareFileForPath($file) {
        return str_replace('/', DS, $file);
    }

    public function getFilePath($path, $file) {
        $file = $this->_prepareFileForPath($file);

        if (substr($file, 0, 1) == DS) {
            return $path . DS . substr($file, 1);
        }

        return $path . DS . $file;
    }
}
