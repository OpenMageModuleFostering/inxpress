<?php
class Cedcoss_Inxpress_Block_Adminhtml_Inxpress_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
  {
      parent::__construct();
      $this->setId('formGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('DESC');
      //$this->setSaveParametersInSession(true);
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
  
  //	$statuses = Mage::getSingleton('inxpress')->getOptionArray();
   
   	
  	// $this->getMassactionBlock()->addItem('status', array(
  			// 'label'=> Mage::helper('inxpress')->__('Change status'),
  			// 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
  			// 'additional' => array(
  					// 'visibility' => array(
  							// 'name' => 'status',
  							// 'type' => 'select',
  							// 'class' => 'required-entry',
  							// 'label' => Mage::helper('inxpress')->__('Status'),
  							// 'values' => array('apply'=>'Apply','unapply'=>'Unapply'),
  					// )
  			// )
  	// ));
  	return $this;
  }
  
  
  protected function _prepareCollection()
  {
    $collection = Mage::getModel('inxpress/variant')->getCollection();
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
	$this->addColumn('product_id', array(
  			'header'    => Mage::helper('inxpress')->__('Product ID'),
  			'align'     =>'right',
  			'width'     => '50px',
  			'index'     => 'product_id',
  	));
  
	$this->addColumn('variant', array(
  			'header'    => Mage::helper('inxpress')->__('variant'),
  			'align'     => 'left',
   			'index'     => 'variant',
  			'filter_index'  => 'variant',

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
	$this->addColumn('dim_weight', array(
  			'header'    => Mage::helper('inxpress')->__('dim_weight'),
  			'align'     => 'left',
   			'index'     => 'dim_weight',
  			'filter_index'  => 'dim_weight',

  	));
	$this->addColumn('variable', array(
  			'header'    => Mage::helper('inxpress')->__('variable'),
  			'align'     => 'left',
   			'index'     => 'variable',
  			'filter_index'  => 'variable',

  	));
  	$this->addColumn('created_at', array(
  			'header'        => Mage::helper('inxpress')->__('Created On'),
  			'align'         => 'left',
  			'type'          => 'datetime',
  			'width'         => '100px',
  			'filter_index'  => 'modifieddate',
  			'index'         => 'modifieddate',
  	));
  
  
  
  
  
 	// $this->addExportType('*/*/exportCsv', Mage::helper('inxpress')->__('CSV'));
  	//$this->addExportType('*/*/exportXml', Mage::helper('inxpress')->__('XML'));
  	
  	 
  	return parent::_prepareColumns();
  }
  public function storeFilter()
  {	$storesFilter=array();
  	$stores = Mage::app()->getStores();
  	foreach ($stores as $store) {
  		$storesFilter[$store->getId()]=$store->getName();
  	}
  	return $storesFilter;
  }
  public function websiteFilter()
  {	/* $websiteFilter=array();
  	foreach (Mage::app()->getWebsites() as $website) {
  		$websiteFilter[$website->getId()]=$website->getName();
  	}
  	return $websiteFilter; */
	$websiteFilter=array();
	$websiteFilter[]='All Websites';
	if(Mage::getStoreConfig('catalog/price/scope')==1)
	{
		foreach (Mage::app()->getWebsites() as $_websiteId => $_info) {
			$websiteFilter[$_websiteId]=$_info['name'].$_info['currency'];
		}
	}
	
  	return $websiteFilter;
  }
  public function groupFilter()
  {
	 $customer_group = new Mage_Customer_Model_Group();
	$allGroups  = $customer_group->getCollection()->toOptionHash();
	$customerGroup=array();
	foreach($allGroups as $key=>$allGroup){
	   $customerGroup[$key]=$allGroup;
	}
	return $customerGroup;
  }
  public function getRowUrl($row)
  {
  	return $this->getUrl('*/*/new', array('id' => $row->getId()));
  }
}