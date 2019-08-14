<?php
class Cedcoss_Inxpress_Block_Adminhtml_Dhl_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
  {
      parent::__construct();
      $this->setId('dhl');
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
  }

  protected function _prepareMassaction()
  {
  	$this->setMassactionIdField('id');
  	$this->getMassactionBlock()->setFormFieldName('id');
  
  	$this->getMassactionBlock()->addItem('delete', array(
  			'label'    => Mage::helper('inxpress')->__('Delete'),
  			'url'      => $this->getUrl('*/*/massDelete'),
  			'confirm'  => Mage::helper('inxpress')->__('Are you sure?')
  	));
  	return $this;
  }
  
  
  protected function _prepareCollection()
  {
    $collection = Mage::getModel('inxpress/dhl')->getCollection();
      $this->setCollection($collection);
  	
  	return parent::_prepareCollection();
  }
  protected function _prepareColumns()
  {
  	$this->addColumn('id', array(
  			'header'    => Mage::helper('inxpress')->__('ID'),
  			'align'     =>'right',
  			'width'     => '50px',
  			'index'     => 'id',
  	));
  
	$this->addColumn('supplies', array(
  			'header'    => Mage::helper('inxpress')->__('Supplies'),
  			'align'     => 'left',
   			'index'     => 'supplies',
  			'filter_index'  => 'supplies',

  	));
	$this->addColumn('length', array(
  			'header'    => Mage::helper('inxpress')->__('length'),
  			'align'     => 'left',
   			'index'     => 'length',
  			'filter_index'  => 'length',

  	));
	$this->addColumn('width', array(
  			'header'    => Mage::helper('inxpress')->__('width'),
  			'align'     => 'left',
   			'index'     => 'width',
  			'filter_index'  => 'width',

  	));
	
	$this->addColumn('height', array(
  			'header'    => Mage::helper('inxpress')->__('height'),
  			'align'     => 'left',
   			'index'     => 'height',
  			'filter_index'  => 'height',

  	));
  	$this->addColumn('created_at', array(
  			'header'        => Mage::helper('inxpress')->__('Created On'),
  			'align'         => 'left',
  			'type'          => 'datetime',
  			'width'         => '100px',
  			'filter_index'  => 'modifieddate',
  			'index'         => 'modifieddate',
  	));
  
  
  	return parent::_prepareColumns();
  }

  public function getRowUrl($row)
  {
  	return $this->getUrl('*/adminhtml_dhl/new', array('id' => $row->getId()));
  }
}