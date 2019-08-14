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
	    			$dimweight = Mage::getModel('inxpress/variant')->getCollection()->addFieldToFilter('product_id',$item->getProduct()->getId())->getData();
	    			if(!empty($dimweight))
	    			{
	    				$weight=($dimweight[0]['dim_weight'] > $item->getProduct()->getWeight() ? $dimweight[0]['dim_weight'] : $item->getProduct()->getWeight());						$variable=$dimweight[0]['variable'];
	    			}
	    			else 
	    			{
	    				$weight=$item->getProduct()->getWeight();
	    			}
	    			$code='';
	    			if($weight>0.5)	{
	    			
    					$code='P';
	    			}
	    			else if($weight!=0&&$weight<=0.5)
	    			{
	    				$code='X';
	    			}
	    			if(!empty($dimweight))
	    			{
                	$price=$this->calcRate(Mage::getStoreConfig('carriers/inxpress/account'),$code,$request->getDestCountryId(),$weight,$dimweight[0]['length'],$dimweight[0]['width'],$dimweight[0]['height'],$request->getDestPostcode(),$item->getProduct()->getWeight());
	    			}
	    			else 
	    			{
	    			$price=$this->calcRate(Mage::getStoreConfig('carriers/inxpress/account'),$code,$request->getDestCountryId(),$weight,0,0,0,$request->getDestPostcode(),$item->getProduct()->getWeight());
	    			}
					if($price)
					{
						if((isset($variable))&&($variable!=''))						
						{							
							if($variable>=$item->getQty())							
							{								
								$shippingPrice=($shippingPrice+$price['price']);							
							}							
							else if($variable<$item->getQty())							
							{								
								$qty=ceil(($item->getQty())/$variable);								
								$shippingPrice=($shippingPrice+$price['price'])*$qty;						
							}						
						}						
						else						
						{
	 						$shippingPrice=($shippingPrice+$price['price'])*$item->getQty();						
						}
					}
					else 
					{
						
						return false;
					}
                }
            }
        }
        $result = Mage::getModel('shipping/rate_result');
        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);
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
     
    
    public function calcRate($account,$code,$country,$weight,$length,$width,$height,$zip,$pro_weight)
    {
    	if(($pro_weight>$weight)||($length==0)||($width==0)||($height==0))
    	{
    		$url = Mage::getStoreConfig('carriers/inxpress/gateway_url').'?acc='.$account.'&dst='.$country.'&prd='.$code.'&wgt='.$weight.'&pst='.$zip;
    	}
    	else 
    	{
    		$url = Mage::getStoreConfig('carriers/inxpress/gateway_url').'?acc='.$account.'&dst='.$country.'&prd='.$code.'&wgt='.$weight.'&pst='.$zip.'&pcs='.$length.'|'.$width.'|'.$height.'|'.$pro_weight;
    	}
    	
		
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