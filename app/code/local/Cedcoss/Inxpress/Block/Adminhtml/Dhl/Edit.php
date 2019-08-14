<?php

class Cedcoss_Inxpress_Block_Adminhtml_Dhl_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'inxpress';
        $this->_controller = 'adminhtml_dhl';//actual location of block files
        
        $this->_updateButton('save', 'label', Mage::helper('inxpress')->__('Save Dhl Box'));
        $this->_updateButton('delete', 'label', Mage::helper('inxpress')->__('Delete Dhl Box'));
		
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
            return Mage::helper('inxpress')->__("Edit Inxpress '%s'", $this->htmlEscape(Mage::registry('inxpress_data')->getFirstname().' '.Mage::registry('
			tabs_data')->getLastname()));
        } else {
            return Mage::helper('inxpress')->__('Add Dhl Box');
        }
    }
	
}