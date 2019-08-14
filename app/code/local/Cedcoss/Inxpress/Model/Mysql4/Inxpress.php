<?php
class Cedcoss_Inxpress_Model_Mysql4_Inxpress extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $resource = Mage::getSingleton('core/resource');
        
        $this->setType('inxpress_inxpress');
        $this->setConnection(
            $resource->getConnection('inxpress_read'),
            $resource->getConnection('inxpress_write')
        );
    }
} 