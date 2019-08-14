<?php

class Cedcoss_Inxpress_Block_Adminhtml_Inxpress_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
	  
      $this->setId('inxpress_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('inxpress')->__('Form Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('general', array(
          'label'     => Mage::helper('inxpress')->__('General Information'),
          'title'     => Mage::helper('inxpress')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('inxpress/adminhtml_inxpress_edit_tab_general')->toHtml(),
      ));
      return parent::_beforeToHtml();
  }
}