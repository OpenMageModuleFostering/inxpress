<?php 
class Cedcoss_Inxpress_ActivateController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
    {
    	try 
    	{
	    	if(isset($_POST['key']))
	    	{
	    		if($_POST['key']==Mage::getStoreConfig('carriers/inxpress/key'))
	    		{
	    			$inxpresskey = new Mage_Core_Model_Config();
	
					$inxpresskey ->saveConfig('carriers/inxpress/active', 1, 'default', 0);
					$inxpresskey ->saveConfig('carriers/inxpress/account', $_POST['account_no'], 'default', 0);
					$inxpresskey ->saveConfig('carriers/inxpress/inexpressid', $_POST['inxpress_account_no'], 'default', 0);
					$inxpresskey ->saveConfig('carriers/inxpress/gateway_url', 'http://www.ixpapi.com/ixpapp/rates.php', 'default', 0);
					echo "Configuration Data Has Been Saved Successfully!!!!";die;
	    		}
	    	}
    	}
	    catch(Exception $e)
	    {
	    	echo $e;die;
	    }
    }
    
	
}

