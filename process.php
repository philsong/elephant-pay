<?php
/**
* E-xact Transactions Ltd.
*
* Copyright (c) 2004.  All Rights Reserved.
*
* YOUR RIGHTS WITH RESPECT TO THIS SOFTWARE IS GOVERNED BY THE
* TERMS AND CONDITIONS SET FORTH IN THE CORRESPONDING EULA.
*
* This htm page was developed by E-xact's Software Development Division.
* Last Updated: May 8, 2006
*
* A PHP server is required for this sample code and can be downloaded from http://www.php.net 
* PHP 5.1.2 was used for this code.
*
* PHP Extensions required:	
*		php_openssl.dll 		- allows the use of https connections
*								- required files:
*									libeay32.dll	- included in the PHP 5.1.2 package
*									ssleay32.dll	- inlcuded in the PHP 5.1.2 package
*		php_openssl.dll should be inside the 'ext' directory under the PHP installation directory and the required 
*		dependancies are in the root of the PHP installation directory.
* 		The dependencies should be placed in a directory which is part of the windows path or placed in the 'system32' of 
*		the Windows installation directory.
*		php_openssl.dll extension should be enabled in the PHP.ini file used to setup the PHP server.											
*
*		php_soap.dll			- allows the soap communication with the transaction server.
*
*		php_soap.dll should be inside the 'ext' directory under the PHP installation directory.
*		php_soap.dll extension should be enabled in the PHP.ini file used to setup the PHP server.															
*
* For setup of PHP server and activation of PHP extensions please refer to the installation manual included in the 
* PHP 5.1.2 package download.
**/

$trxnProperties = array(
  "User_Name"=>"",
  "Secure_AuthResult"=>"",
  "Ecommerce_Flag"=>"",
  "XID"=>"",
  "ExactID"=>$_POST["ddlPOS_ExactID"],				    //Payment Gateway I.E. CAD="A00049-01" USD="A00427-01"
  "CAVV"=>"",
  "Password"=>"test1",					                //Gateway Password I.E. CAD="test1" USD="testus"
  "CAVV_Algorithm"=>"",
  "Transaction_Type"=>$_POST["ddlPOS_Transaction_Type"],//Transaction Code I.E. Purchase="00" Pre-Authorization="01" etc.
  "Reference_No"=>$_POST["tbPOS_Reference_No"],
  "Customer_Ref"=>$_POST["tbPOS_Customer_Ref"],
  "Reference_3"=>$_POST["tbPOS_Reference_3"],
  "Client_IP"=>"",					                    //This value is only used for fraud investigation.
  "Client_Email"=>$_POST["tb_Client_Email"],			//This value is only used for fraud investigation.
  "Language"=>$_POST["ddlPOS_Language"],				//English="en" French="fr"
  "Card_Number"=>$_POST["tbPOS_Card_Number"],		    //For Testing, Use Test#s VISA="4111111111111111" MasterCard="5500000000000004" etc.
  "Expiry_Date"=>$_POST["ddlPOS_Expiry_Date_Month"] . $_POST["ddlPOS_Expiry_Date_Year"],//This value should be in the format MM/YY.
  "CardHoldersName"=>$_POST["tbPOS_CardHoldersName"],
  "Track1"=>"",
  "Track2"=>"",
  "Authorization_Num"=>$_POST["tbPOS_Authorization_Num"],
  "Transaction_Tag"=>$_POST["tbPOS_Transaction_Tag"],
  "DollarAmount"=>$_POST["tbPOS_DollarAmount"],
  "VerificationStr1"=>$_POST["tbPOS_VerificationStr1"],
  "VerificationStr2"=>"",
  "CVD_Presence_Ind"=>"",
  "Secure_AuthRequired"=>"",
  
  // Level 2 fields 
  "ZipCode"=>$_POST["tbPOS_ZipCode"],
  "Tax1Amount"=>$_POST["tbPOS_Tax1Amount"],
  "Tax1Number"=>$_POST["tbPOS_Tax1Number"],
  "Tax2Amount"=>$_POST["tbPOS_Tax2Amount"],
  "Tax2Number"=>$_POST["tbPOS_Tax2Number"],
  
  "SurchargeAmount"=>"",
  "PAN"=>"",
  //"SurchargeAmount"=>$_POST["tbPOS_SurchargeAmount"],	//Used for debit transactions only
  //"PAN"=>$_POST["tbPOS_PAN"]							//Used for debit transactions only
  );

$trxn = array("Transaction"=>$trxnProperties);

// If you are using our DEMO site at rpm-demo.e-xact.com with a Gateway ID of "AD...", you will need to use the host: api-demo.e-xact.com

try {
	$client = new SoapClient("https://api.e-xact.com/transaction/v8/wsdl");
	$trxnResult = $client->__soapCall('SendAndCommit', $trxn);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}


/*
$client = new SoapClient("https://api.e-xact.com/vplug-in/transaction/rpc-enc/service.asmx?wsdl");
$trxnResult = $client->__soapCall('SendAndCommit', $trxn);

if($client->fault){
    // there was a fault, inform
    print "<B>FAULT:  Code: {$client->faultcode} <BR />";
    print "String: {$client->faultstring} </B>";
    $trxnResult["CTR"] = "There was an error while processing. No TRANSACTION DATA IN CTR!";
}
*/

//Uncomment the following commented code to display the full results.

echo "<H3><U>Transaction Properties BEFORE Processing</U></H3>";
echo "<TABLE border='0'>\n";
echo " <TR><TD><B>Property</B></TD><TD><B>Value</B></TD></TR>\n";
foreach($trxnProperties as $key=>$value){
   // echo " <TR><TD>$key</TD><TD>:$value</TD></TR>\n";
}
echo "</TABLE>\n";

echo "<H3><U>Transaction Properties AFTER Processing</U></H3>";
echo "<TABLE border='0'>\n";
echo " <TR><TD><B>Property</B></TD><TD><B>Value</B></TD></TR>\n";
foreach($trxnResult as $key=>$value){
    $value = nl2br($value);
	if(strcmp($key, "Bank_Resp_Code")==0)
	{
		if(strcmp($value, "028")==0)
		{
			try{
			  $sClient = new SoapClient('http://localhost/pay/hi.wsdl');
			  
			  $params = "tti001";
			  $response = $sClient->doHello($params);
			  
			  var_dump($response);
			  
			  
			} catch(SoapFault $e){
			  var_dump($e);
			}
		}
	}
    echo " <TR><TD valign='top'>$key</TD><TD>:$value</TD></TR>\n";
}
echo "</TABLE>\n";


// kill object
unset($client);
?>

<html>
<head>
<title>VPOS - ColdFusion Sample Code</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
    <table>
	    <tr><td>
	    <table cellSpacing="0" cellPadding="0" width="660" align="left" border="0">
		    <tr>
			    <td width="165"><IMG height="5" src="/images/demo/space.gif" width="100%"></td>
			    <td width="200" bgColor="#003366"><IMG height="5" src="/images/demo/space.gif"></td>
			    <td width="60" bgColor="#003366"><IMG height="5" src="/images/demo/space.gif"></td>
			    <td width="120" bgColor="#003366"><IMG height="5" src="/images/demo/space.gif"></td>
			    <td width="40" bgColor="#003366"><IMG height="5" src="/images/demo/space.gif"></td>
		    </tr>
		    <tr>
			    <td><IMG height="53" src="logo.jpg" width="165"></td>
			    <td bgColor="#003366" colSpan="2"><font face="verdana,arial,helvetica" color="white" size="5"><b>&nbsp;&nbsp;P&nbsp;o&nbsp;i&nbsp;n&nbsp;t&nbsp;&nbsp;&nbsp;O&nbsp;f&nbsp;&nbsp;&nbsp;S&nbsp;a&nbsp;l&nbsp;e</b></font></td>
			    <td vAlign="top" rowSpan="3">
			        <table borderColor="#003366" height="100%" cellSpacing="0" borderColorDark="#003366" cellPadding="0" width="100%" borderColorLight="#003366" border="3">
				        <tr>
					        <td><IMG height="94" src="pos_cc2.gif" width="130"></td>
				        </tr>
			        </table></td>
			    <td bgColor="#003366"><IMG height="60" src="space.gif"></td>
		    </tr>
		    <tr>
			    <td bgColor="#003366"><IMG height="5" src="/images/demo/space.gif" width="3"></td>
			    <td bgColor="#003366"><IMG height="5" src="/images/demo/space.gif" width="3"></td>
			    <td bgColor="#003366"><IMG height="5" src="/images/demo/space.gif" width="3"></td>
			    <td bgColor="#003366"><IMG height="5" src="/images/demo/space.gif"></td>
		    </tr>
		    <tr>
			    <td align="right" colSpan="2"><font face="verdana,arial,helvetica" color="#003366" size="3"><b>V
									P l u g - i n&nbsp;&nbsp;&nbsp;<FONT face="verdana,arial,helvetica" color="#003366" size="3"><B>P
											r o c e s s&nbsp;&nbsp;&nbsp;D e m o </B></FONT>&nbsp;&nbsp; </b></font></td>
			    <td bgColor="#003366"><IMG height="60" src="space.gif"></td>
			    <td vAlign="middle" align="center"><IMG height="37" src="lock.gif" width="35"></td>
		    </tr>
	        </table></td></tr>
	        <tr><td>
	        <table cellSpacing="6" cellPadding="0" width="660" align="left" border="2">
		        <tr>
			        <td align="left" valign="top">
						<?php 
							foreach($trxnResult as $key=>$value){
								if ($key == "CTR") {
								    $value = nl2br($value);
									print $value;
								}
							}
						?></td>
                    <!-- NOTE: chr(10) is the ASCII equivalent of the "Line Feed" character -->
		        </tr>
		        <tr>
			        <td align="center" valign="top"><a href="javascript:history.back();">Perform Another Transaction</a></td>
		        </tr>
	    </table></td></tr>
    </table>
</body>
</html>
