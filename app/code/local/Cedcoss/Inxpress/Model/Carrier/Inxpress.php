<?php
class Cedcoss_Inxpress_Model_Carrier_Inxpress extends Mage_Shipping_Model_Carrier_Abstract
{
      
      protected $_code = 'inxpress';
     
      public function collectRates(Mage_Shipping_Model_Rate_Request $request)
      {
     
        if (!$this->getConfigFlag('active')) {
            return false;
        }
		$final_lbh = '';
		$weight=0;
        $shippingPrice=0;
        if ($request->getAllItems()) 
        {
            foreach ($request->getAllItems() as $item) {
            	
 				
                if ($item->getProduct()->isVirtual()) {
                    continue;
                }
                else 
                {
	    			$dimweight = Mage::getModel('inxpress/variant')->getCollection()->addFieldToFilter('product_id',$item->getProduct()->getId())->getData();
	    			if(!empty($dimweight))
	    			{
	    				$variable=$dimweight[0]['variable'];	
	    				if(($variable!=''&&$variable!=0))						
						{							
							if($variable>=$item->getQty())							
							{								
								if($dimweight[0]['dim_weight'] > $item->getProduct()->getWeight())
	    						{
	    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$item->getProduct()->getWeight().';';
	    							$weight=$weight+$dimweight[0]['dim_weight'];
	    						}	
	    						else 
	    						{
	    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$item->getProduct()->getWeight().';';
	    							$weight=$weight+$item->getProduct()->getWeight();
	    						}					
							}							
							else if($variable<$item->getQty())							
							{								
								$qty=ceil(($item->getQty())/$variable);	
								$prod_weight=$item->getProduct()->getWeight()*$qty;
								$prod_dim_weight=$dimweight[0]['dim_weight']*$qty;
								if($prod_dim_weight > $prod_weight)
	    						{
	    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$prod_weight.';';
	    							$weight=$weight+$prod_dim_weight;
	    						}
	    						else 
	    						{	
	    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$prod_weight.';';
	    							$weight=$weight+($item->getProduct()->getWeight()*$qty);
	    						}	
															
							}						
						}						
						else						
						{
							
							$prod_weight=$item->getProduct()->getWeight()*$item->getQty();
							$prod_dim_weight=$dimweight[0]['dim_weight']*$item->getQty();
							if($prod_dim_weight > $prod_weight)
    						{
    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$prod_weight.';';
    							$weight=$weight+$prod_dim_weight;
    						}	
    						else 
	    					{	
	    							$final_lbh.=$dimweight[0]['length'].'|'.$dimweight[0]['width'].'|'.$dimweight[0]['height'].'|'.$prod_weight.';';
	    							$weight=$weight+($item->getProduct()->getWeight()*$item->getQty());
	    					}			
						}	
	    			
	    			
	    				
	    			}
	    			else 
	    			{
	    				$weight=$weight+($item->getProduct()->getWeight()*$item->getQty());
	    			}
	    			
	    			
	    			
	    			$code='';
                	if($weight>0.5)	{
	    			
    					$code='P';
	    			}
	    			else if($weight!=0&&$weight<=0.5)
	    			{
	    				$code='X';
	    			}
	    			
                }
            }
        	$price=$this->calcRate(Mage::getStoreConfig('carriers/inxpress/account'),$code,$request->getDestCountryId(),$weight,$final_lbh,$request->getDestPostcode());
			//print_r($price);die('wkkk');
			if($price)
			{
				$shippingPrice=$price['price'];
			}
			else 
			{
				return false;
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
     
    
    public function calcRate($account,$code,$country,$weight,$dimension,$zip)
    {
    	$dimension = rtrim($dimension, ';');
    	$url = Mage::getStoreConfig('carriers/inxpress/gateway_url').'http://www.ixpapi.com/ixpapp/rates.php?acc='.$account.'&dst='.$country.'&prd='.$code.'&wgt='.$weight.'&pst='.$zip.'&pcs='.$dimension;
    	
    	//echo $url;die;
		
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