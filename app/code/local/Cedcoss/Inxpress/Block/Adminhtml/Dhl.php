<?php
class Cedcoss_Inxpress_Block_Adminhtml_Dhl extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{	
		$this->_controller = 'adminhtml_dhl';
		$this->_blockGroup = 'inxpress';
		$this->_headerText = Mage::helper('inxpress')->__('Manage Dhl Box\'s');
		$this->_addButtonLabel = Mage::helper('inxpress')->__('Add Box Size');
		parent::__construct();
	}
}