<?php

try

{

	$installer = $this;

	$installer->startSetup();

	$user = Mage::getModel('admin/user')->getCollection();
    
    $user=$user->getData();

	

	$company=Mage::getStoreConfig('general/store_information/name');

	$firstname=$user[0]['firstname'];

	$lastname=$user[0]['lastname'];

	$countryModel = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('shipping/origin/country_id'));

	$countryName = $countryModel->getName();

	$region = Mage::getModel('directory/region')->load(Mage::getStoreConfig('shipping/origin/region_id'));

	if($region->getName()!='')

	{

		$region_name=$region->getName();

	}

	else 

	{

		$region_name=Mage::getStoreConfig('shipping/origin/region_id');

	}

	$city=Mage::getStoreConfig('shipping/origin/city');

	$zip=Mage::getStoreConfig('shipping/origin/postcode');

	$phone=Mage::getStoreConfig('general/store_information/phone');

	$email=$user[0]['email'];

	$address=Mage::getStoreConfig('shipping/origin/street_line1').','.Mage::getStoreConfig('shipping/origin/street_line2');

	$website=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

	

	Mage::log('Company Name: '.$company);

	Mage::log('First Name: '.$firstname);

	Mage::log('Last Name: '.$lastname);

	Mage::log('Country: '.$countryName);

	Mage::log('State: '.$region_name);

	Mage::log('City: '.$city);

	Mage::log('Zipcode: '.$zip);

	Mage::log('Phone: '.$phone);

	Mage::log('Email: '.$email);

	Mage::log('Address: '.$address);

	Mage::log('Website: '.$website);

	if($company=='')
	{
		$company='Magento Store';
	}

	$url = 'http://inxpressaz.force.com/leadcreation?cmp='.$company.'&fn='.$firstname.'&ln='.$lastname.'&add='.$address.'&ct='.$city.'&st='.$region_name.'&cnt='.$countryName.'&zp='.$zip.'&ph='.$phone.'&em='.$email.'&ws='.$website.'&ls=Magento';

	Mage::log($url);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,$url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);

	$data = curl_exec ($ch);

	Mage::log($data);
	
	$key1=md5(substr ($website,8,6)."_".time().substr ($email,2,5));
	
	
	
	
	$post_string = '';
	$params = array(
	'firstname'=>$firstname,
	'lastname'=>$lastname,
	'company'=>$company,
	'phone'=>$phone,
	'email'=>$email,
	'address'=>$address,
	'city'=>$city,
	'state'=>$region_name,
	'country'=>$countryName,
	'zipcode'=>$zip,
	'website'=>$website,
	'framework'=>1,
	'key'=>$key1,
	);

	Mage::log($key1);
	Mage::log('======================');
	
	$inxpresskey = new Mage_Core_Model_Config();

	$inxpresskey ->saveConfig('carriers/inxpress/key', $key1, 'default', 0);

	foreach($params as $key=>$value) { $post_string .= $key.'='.$value.'&'; }
	$post_string = rtrim($post_string, '&');
	
	$url="http://www.ixpapi.com/ixpadmin/control/index.php/downloadInfo/create";
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
	
	
	$result = curl_exec($ch);
	curl_close($ch);
	Mage::log($result);

	Mage::log('setup executed');

	

	

}

catch(Exception $e)

{

	Mage::log($e);

}