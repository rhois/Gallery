<?php
//require_once 'Mage/Adminhtml/Block/Widget/Grid/Column.php';

class Displaystore_Gallery_Block_Adminhtml_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column {

    protected function _getRendererByType() {
        switch (strtolower($this->getType())) {
            case 'file':
                $rendererClass = 'gallery/adminhtml_widget_grid_column_renderer_file';
                break;
            default:
                $rendererClass = parent::_getRendererByType();
                break;
        }
        return $rendererClass;
    }

    protected function _getFilterByType() {
        switch (strtolower($this->getType())) {
            case 'file':
                $filterClass = 'gallery/adminhtml_widget_grid_column_filter_file';
                break;
            default:
                $filterClass = parent::_getFilterByType();
                break;
        }
        return $filterClass;
    }

}