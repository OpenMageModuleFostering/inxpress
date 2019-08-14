<?php 

class Cedcoss_Inxpress_Adminhtml_DhlController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		
	 $this->loadLayout();
	 $this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_dhl'));
	$this->renderLayout();	

	}
	public function variantAction()
	{
	
		$this->loadLayout();
		/* $this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_variant')); */
		$this->renderLayout();	

	}
	public function editAction()

	{
		
		$this->_forward('new');
		
	}
	public function variantsaveAction()
	{
		/* print_r($this->getRequest()->getParams());
		$inxpress=''; */
		if($id=$this->getRequest()->getParam('id'))
		{ 		
		
				if($post = $this->getRequest()->getPost())		
				{	
					$data=$post;
					
					//$data['modifieddate']=now();
					$inxpress = Mage::getModel('inxpress/variant')->load($id); 
					
					
						$inxpress->addData($data)->setId($id)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('Product Varient has been saved.'));
					
				}
		}
		else
		{	//die('ss');
				
				if($post = $this->getRequest()->getPost())
				{
					/* print_r($post);die; */
					$data=$post;
					$data['modifieddate']=now();
					
					
						$inxpress = Mage::getModel('inxpress/variant'); 
						$inxpress->setData($data)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('The DHL Varient has been saved.'));
						
					
					
				}
		}
		//$this->_redirect('admin/catalog_product/edit',array('id'=>$this->getRequest()->getParam('product')));
	}
	public function saveAction()
	{	
		$inxpress='';
		if($id=$this->getRequest()->getParam('id'))
		{ 		
		
				if($post = $this->getRequest()->getPost())		
				{	
					$data=$post;
					
					//$data['modifieddate']=now();
					$inxpress = Mage::getModel('inxpress/dhl')->load($id); 
					
					
						$inxpress->addData($data)->setId($id)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('The DHL Varient has been saved.'));
					
				}
		}
		else
		{	//die('ss');
				
				if($post = $this->getRequest()->getPost())
				{
					$data=$post;
					$data['modifieddate']=now();
					
					
						$inxpress = Mage::getModel('inxpress/dhl'); 
						$inxpress->setData($data)->save();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('The DHL Varient has been saved.'));
						$this->_redirect('inxpress/adminhtml_index/new',array('id'=>$inxpress->getId()));
					
					
				}
		}
		if($this->getRequest()->getParam('back'))
		{
			$this->_redirect('inxpress/adminhtml_dhl/new',array('id'=>$inxpress->getId()));
		}
		else
		{
			$this->_redirect('inxpress/adminhtml_dhl/index');
		}
		
	}
	public function deleteAction()
	{
		
		$id=$this->getRequest()->getParam('id');
		$obj=Mage::getModel('inxpress/dhl');
		$ob=$obj->load($id);
		$ob->delete();
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('inxpress')->__('DHL Varient Deleted Successfully...'));
		$this->_redirect('inxpress/adminhtml_dhl/index');
	
	}
		public function massStatusAction()
	{
		
		$status=$this->getRequest()->getParam('status');
		//	$count=count($this->getRequest()->getParam('id'));
		if( $this->getRequest()->getParam('id') > 0 ) {
			
			try{
				foreach($this->getRequest()->getParam('id') as $id)
				{	
					$model = Mage::getModel('inxpress/inxpress')->load($id);
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
			
			
		$this->_redirect('*/adminhtml_dhl/index');
	}

	public function massDeleteAction() {

		if( $this->getRequest()->getParam('id') > 0 ) {
			try {

				$model = Mage::getModel('inxpress/dhl');
				foreach($this->getRequest()->getParam('id') as $id)
				{	
					$model = Mage::getModel('inxpress/dhl')->load($id);

					 $model->delete();
						
					
					/* $row=Mage::getModel('inxpress/inxpress')->load($id);
					 */
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('DHL Varient Deleted Successfully...'));
				$this->_redirect('*/*/index');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/index');
	}
	public function newAction(){
			//die('chcek');
            $this->loadLayout();
	//		$this->_setActiveMenu('system/author');
			$this->_addContent($this->getLayout()->createBlock('inxpress/adminhtml_dhl_edit'))
				->_addLeft($this->getLayout()->createBlock('inxpress/adminhtml_dhl_edit_tabs'));
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