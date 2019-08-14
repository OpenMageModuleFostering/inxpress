<?php
class Cedcoss_Inxpress_Block_Adminhtml_Inxpress extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{	
		
		$this->_controller = 'adminhtml_inxpress';
		$this->_blockGroup = 'inxpress';
		$this->_headerText = Mage::helper('inxpress')->__('Manage Variant');
		/* $this->_addButtonLabel = Mage::helper('inxpress')->__('Add Variant'); */
		
		$this->_addButton('import_csv', array(
        'label' => $this->__('Import Csv'),
        'onclick' => "setLocation('{$this->getUrl('adminhtml/adminhtml_index/import')}')",
    ));
		parent::__construct();
		$this->_removeButton('add');
	}
}