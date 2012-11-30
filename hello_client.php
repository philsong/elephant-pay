<?php
try{
	$sClient = new SoapClient('https://payserver/pay/hi.wsdl', array('local_cert'=>'client.pem'));
	//$sClient = new SoapClient('https://payserver/pay/hi.wsdl');
  
  $params = "phil";
  $response = $sClient->doHello($params);
  
  var_dump($response);
  
  
} catch(SoapFault $e){
  var_dump($e);
}
?>