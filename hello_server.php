<?php
if(!extension_loaded("soap")){
  dl("php_soap.dll");
}

ini_set("soap.wsdl_cache_enabled","0");
$server = new SoapServer("hi.wsdl");

function doHello($yourName){
	// Create the socket and connect
	/*
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$connection = socket_connect($socket,'localhost', 8776);
	if ($connection === false) {
		return "socket_connect() failed.\nReason: ($connection) " . socket_strerror(socket_last_error($socket)) . "\n";
	} else {
		//return "OK.\n";
	}
	
	if(!socket_write($socket, "SOME DATA/r/n"))
	{
	 return("<p>Write failed</p>");
	}
	
	while($buffer = socket_read($socket, 1000))
	{
	 if($buffer == "NO DATA")
	 {
	 return ("<p>NO DATA</p>");
	 break;
	 }
	 else
	 {
	  // Do something with the data in the buffer
	  return("<p>Buffer Data: " . $buffer . "</p>");
	 }
	}

	
	return "<p>Done Reading from Socket</p>";
	*/
	return "demo:wait for value code gerneration interface integration: ".$yourName;
}

$server->AddFunction("doHello");
$server->handle();
?>
