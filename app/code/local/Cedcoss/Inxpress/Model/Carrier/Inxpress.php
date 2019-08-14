<?php
class Cedcoss_Inxpress_Model_Carrier_Inxpress extends Mage_Shipping_Model_Carrier_Abstract
{
      
      protected $_code = 'inxpress';
     
      public function collectRates(Mage_Shipping_Model_Rate_Request $request)
      {
      
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $shippingPrice=0;
        if ($request->getAllItems()) 
        {
            foreach ($request->getAllItems() as $item) {
            	
 				
                if ($item->getProduct()->isVirtual()) {
                    continue;
                }
                else 
                {
	 				$resource = Mage::getSingleton('core/resource');
	    			$readConnection = $resource->getConnection('core_read');
	    			if($item->getProduct()->getWeight()>=1)	{
	    			
    					$code='P';
	    			}
	    			else 
	    			{
	    				$code='X';
	    			}
                	$price=$this->calcRate(Mage::getStoreConfig('carriers/inxpress/account'),$code,$request->getDestCountryId(),$item->getProduct()->getWeight());
	            	
					if($price)
					{
						
	 					$shippingPrice=($shippingPrice+$price['price'])*$item->getQty();
					}
					else 
					{
						
						return false;
					}
                }
            }
        }
        $result = Mage::getModel('shipping/rate_result');
        if ($shippingPrice != 0) {
        	
            $method = Mage::getModel('shipping/rate_result_method');
            
            
            

            $method->setCarrier('inxpress');
            $method->setCarrierTitle(Mage::getStoreConfig('carriers/inxpress/title'));

            $method->setMethod('inxpress');
            $method->setMethodTitle(Mage::getStoreConfig('carriers/inxpress/title').' ( Transit Days: '.$price['days'].' )');

            


            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);

            $result->append($method);
            
            
        }
 
        return $result;
    }
     
    
    public function calcRate($account,$code,$country,$weight)
    {
    	$url = Mage::getStoreConfig('carriers/inxpress/gateway_url').'?acc='.$account.'&dst='.$country.'&prd='.$code.'&wgt='.$weight;
		
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		$data = curl_exec ($ch);
		curl_close ($ch); 
		$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $data);
		$xml = simplexml_load_string($xml);
		$json = json_encode($xml);
		$responseArray = json_decode($json,true);
		if(isset($responseArray['totalCharge']))
		{
			$response=array();
			$response['price']=$responseArray['totalCharge'];
			$response['days']=$responseArray['info']['baseCountryTransitDays'];
			return $response;
		}
		else 
		{
			return false;
		}
		
    }
}