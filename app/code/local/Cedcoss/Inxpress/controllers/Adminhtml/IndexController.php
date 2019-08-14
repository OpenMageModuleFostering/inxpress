<?php 

class Cedcoss_Inxpress_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		
	 $this->loadLayout();
	 $this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_inxpress'));
	$this->renderLayout();	

	}
	public function importAction()
	{
		 $this->loadLayout();
		 $this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_csvimport'));
		$this->renderLayout();	
	}
	public function importcsvAction()
	{
	
		if(isset($_FILES["file"]["name"]))
		{	
			$handle = fopen($_FILES["file"]["tmp_name"], "r");
			$data = fgetcsv($handle, 4000, ",");
			$indexes=array();
			foreach($data as $key=>$val)
			{
				$indexes[$val]=$key;
			}
			if(!isset($indexes['length'])||!isset($indexes['width'])||!isset($indexes['height'])||!isset($indexes['sku']))
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('inxpress')->__('Some required attributes are missing.'));
				$this->_redirect('inxpress/adminhtml_index/index');
				return;
			}
			$count=0;			$lbh_check=0;			$success=0;
			while(($data = fgetcsv($handle, 4000, ",")) !== FALSE)
			{	

				$data1=array();
				$product=Mage::getModel('catalog/product')->loadByAttribute('sku',$data[$indexes['sku']]);
				if($product)
				{
					$variant=Mage::getModel('inxpress/variant')->getCollection()->addFieldToFilter('product_id',$product->getId())->getFirstItem();
					$variant->setId($variant->getId());
					foreach($indexes as $key=>$index )
					{
						$data1[$key]=$data[$index];
					}					if(trim($data1['length'])==''||trim($data1['width'])==''||trim($data1['height'])=='')					{						$lbh_check++;						continue;					}					
					$data1['product_id']=$product->getId();
					$data1['variant']=$product->getName();
					$data1['modifieddate']=now();
					$data1['dim_weight']=ceil((((float)$data1['height'])*((float)$data1['width'])*((float)$data1['length']))/139);					$success++;
					$variant->addData($data1)->save();
					
				}
				else
				{
					$count++;
				}
			}			if($count!=0)			{				Mage::getSingleton('adminhtml/session')->addError($count.' Sku\'s of csv are not matching to magento products..');			}			if($lbh_check!=0)			{				Mage::getSingleton('adminhtml/session')->addError($lbh_check.' Row\'s of csv have not valid LBH value..');			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('Csv Imported Successfully.'));
			
		}
		$this->_redirect('inxpress/adminhtml_index/index');
	}
	public function editAction()

	{
		$this->_forward('new');
	}
	public function saveAction()
	{	

		if($id=$this->getRequest()->getParam('id'))
		{ 		
		
				if($post = $this->getRequest()->getPost())		
				{	
					$data=$post;
					
					//$data['modifieddate']=now();
					$inxpress = Mage::getModel('inxpress/variant')->load($id); 
					
					
						$inxpress->addData($data)->setId($id)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('The variant data has been saved.'));
					
				}
		}
		else
		{	//die('ss');
				
				if($post = $this->getRequest()->getPost())
				{
					$data=$post;
					$data['modifieddate']=now();
					
					
						$inxpress = Mage::getModel('inxpress/variant'); 
						$inxpress->setData($data)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('The variant data has been saved.'));
						$this->_redirect('inxpress/adminhtml_index/new',array('id'=>$inxpress->getId()));
					
					
				}
		}
		if($this->getRequest()->getParam('back'))
		{
			$this->_redirect('inxpress/adminhtml_index/new',array('id'=>$inxpress->getId()));
		}
		else
		{
			$this->_redirect('inxpress/adminhtml_index/index');
		}
		
	}
	public function deleteAction()
	{
		
		$id=$this->getRequest()->getParam('id');
		$obj=Mage::getModel('inxpress/variant');
		$ob=$obj->load($id);
		$ob->delete();
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('Product Variant Deleted Successfully...'));
		$this->_redirect('inxpress/adminhtml_index/index');
	
	}
		public function massStatusAction()
	{
		
		$status=$this->getRequest()->getParam('status');
		//	$count=count($this->getRequest()->getParam('id'));
		if( $this->getRequest()->getParam('id') > 0 ) {
			
			try{
				foreach($this->getRequest()->getParam('id') as $id)
				{	
					$model = Mage::getModel('inxpress/variant')->load($id);
					$groupId=$model->getGroupid();
					$inxpress=$model->getInxpress()/100;
					$website=$model->getWebsite();
					if(!$website){
						$fetchwebsite = 1;
					}else{
						$fetchwebsite = $website;
					}
				//	$store=$model->getStore();
					 $resource = Mage::getSingleton('core/resource');
					 $writeConnection = $resource->getConnection('core_write');
					 $table = $resource->getTableName('catalog_product_index_price');
					 $query="SELECT entity_id,price FROM {$table} WHERE customer_group_id='{$groupId}' AND website_id='{$fetchwebsite}'";
					 $results = $writeConnection->fetchAll($query);
					/*  print_r($results);die; */
					 $table = $resource->getTableName('catalog_product_entity_group_price');
					 $query="DELETE FROM `{$table}` WHERE `customer_group_id` = '{$groupId}' AND `website_id` = '{$website}';" ;
					 $writeConnection->query($query);
					 if($groupId==0)
					{
						
						$allgroups=1;
					}else{
						$allgroups=0;
					}
					
					if($status=='apply')
					{
						
						foreach($results as $result)
						{
							 $price=$result['price']-($result['price']*$inxpress);
							
							 $query = "INSERT INTO `{$table}` SET `entity_id` = '{$result['entity_id']}',`all_groups` = '{$allgroups}',`customer_group_id` = '{$groupId}',`value` = '{$price}',`website_id` = '{$website}';";
					
/* 							 echo $query ;die('sdvf'); */
							 $writeConnection->query($query);
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Applied To All Products..'));
					}
					else
					{
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Unapplied From All Products..'));
					}
					$process = Mage::getModel('index/process')->load(2);
					$process->reindexAll();
					$model->setId($id)
					->setStatus($status)->save();
				}
			}
			catch(Exception $e)	
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('%s',$e->getMessage()));
			}
		}
			
			
		$this->_redirect('*/*/index');
	}

	public function massDeleteAction() {

		if( $this->getRequest()->getParam('id') > 0 ) {
			try {

				$model = Mage::getModel('inxpress/variant');
				foreach($this->getRequest()->getParam('id') as $id)
				{	
					$model = Mage::getModel('inxpress/variant')->load($id);

					 $model->delete();
						
					
					/* $row=Mage::getModel('inxpress/inxpress')->load($id);
					 */
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Product Variant Deleted Successfully...'));
				$this->_redirect('*/*/index');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/index');
	}
	public function newAction(){
			
            $this->loadLayout();
	//		$this->_setActiveMenu('system/author');
			$this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_inxpress_edit'))
				->_addLeft($this->getLayout()->createBlock('inxpress/adminhtml_inxpress_edit_tabs'));
			$this->renderLayout();
	}
		
	
	
/*	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('system/author')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Authors'), Mage::helper('adminhtml')->__('Authors'));
	
		return $this;
	}
*/	
}


?>