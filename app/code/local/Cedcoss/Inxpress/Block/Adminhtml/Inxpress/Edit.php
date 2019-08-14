<?php

class Cedcoss_Inxpress_Block_Adminhtml_Inxpress_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'inxpress';
        $this->_controller = 'adminhtml_inxpress';//actual location of block files
        
        $this->_updateButton('save', 'label', Mage::helper('inxpress')->__('Save Variant'));
        $this->_updateButton('delete', 'label', Mage::helper('inxpress')->__('Delete Variant'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('inxpress_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'inxpress_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'inxpress_content');
                }
            }
			
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/new/');
            }
		
		
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('inxpress_data') && Mage::registry('inxpress_data')->getId() ) {
            return Mage::helper('inxpress')->__("Edit Variant '%s'", $this->htmlEscape(Mage::registry('inxpress_data')->getFirstname().' '.Mage::registry('
			tabs_data')->getLastname()));
        } else {
            return Mage::helper('inxpress')->__('Add Variant');
        }
    }
	public function getStores()
	{	$array=array();
		foreach (Mage::app()->getWebsites() as $website) {
		foreach ($website->getGroups() as $group) {
					$stores = $group->getStores();
					foreach ($stores as $store) {
						$array[$website->getId()][$store->getId()]=$store->getName();
					}
			}
		}
		return json_encode($array,true);
	}
}