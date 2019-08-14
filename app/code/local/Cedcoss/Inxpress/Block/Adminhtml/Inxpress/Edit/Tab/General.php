<?php

class Cedcoss_Inxpress_Block_Adminhtml_Inxpress_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	
	public function __construct()
	{
		//parent::__construct();
		$this->setTemplate('inxpress/variant.phtml');
	}
  protected function _prepareForm()
  {
   	
     $form = new Varien_Data_Form(); 
     $this->setForm($form);
	 $inxpress = Mage::getModel('inxpress/variant');
	 $inxpress_row =  $inxpress->load($this->getRequest()->getParam('id'));
     $fieldset = $form->addFieldset('inxpress_general', array('legend'=>Mage::helper('inxpress')->__("Inxpress's Variants Information.")));
	 $fieldset->addField('product_id', 'text', array(
            'label'     => Mage::helper('inxpress')->__('Product Id'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getProduct_id(),
	 		'required'  => true,
	 		'name'      => 'product_id'

        ));
   $fieldset->addField('variant', 'text', array(
            'label'     => Mage::helper('inxpress')->__('variant'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getVariant(),
	 		'required'  => true,
	 		'name'      => 'variant'

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
		
		$fieldset->addField('dim_weight', 'text', array(
            'label'     => Mage::helper('inxpress')->__('dim_weight'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getDim_weight(),
	 		'required'  => true,
	 		'name'      => 'dim_weight'

        ));
		$fieldset->addField('variable', 'text', array(
            'label'     => Mage::helper('inxpress')->__('variable'),
	 		'class'     => 'required-entry',
	 		'value'      =>  $inxpress_row->getVariable(),
	 		'required'  => true,
	 		'name'      => 'variable'

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