<?php

class Cedcoss_Inxpress_Block_Adminhtml_Dhl_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	
  protected function _prepareForm()
  {
   	
     $form = new Varien_Data_Form(); 
     $this->setForm($form);
	 $inxpress = Mage::getModel('inxpress/dhl');
	 $inxpress_row =  $inxpress->load($this->getRequest()->getParam('id'));
     $fieldset = $form->addFieldset('inxpress_general', array('legend'=>Mage::helper('inxpress')->__("Inxpress's Dhl variant")));
     
		$fieldset->addField('supplies', 'text', array(
            'label'     => Mage::helper('inxpress')->__('supplies'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getSupplies(),
	 		'required'  => true,
	 		'name'      => 'supplies'

        ));
		$fieldset->addField('length', 'text', array(
            'label'     => Mage::helper('inxpress')->__('length'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getLength(),
	 		'required'  => true,
	 		'name'      => 'length'

        ));
		$fieldset->addField('width', 'text', array(
            'label'     => Mage::helper('inxpress')->__('width'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getWidth(),
	 		'required'  => true,
	 		'name'      => 'width'

        ));
		$fieldset->addField('height', 'text', array(
            'label'     => Mage::helper('inxpress')->__('height'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getHeight(),
	 		'required'  => true,
	 		'name'      => 'height'

        ));
		
	 	
		
		
	 	
	 	 
       if ( Mage::getSingleton('adminhtml/session')->getInxpressData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getInxpressData());
          Mage::getSingleton('adminhtml/session')->setInxpressData(null);
      } elseif ( Mage::registry('Inxpress_data') ) {
          $form->setValues(Mage::registry('inxpress_data')->getData());
      }  
     return parent::_prepareForm();
  }
  
}