
<?php

class connection{
	public function getconnection(){
		$serverName = "192.168.10.141"; //serverName\instanceName, portNumber (default is 1433)
        $user ="sa";
        $password = "p@ssw0rd" ;
		return $conn =  odbc_connect("Driver={SQL Server};Server=$serverName;Database=JUNE_LIVE_TEST;", $user, $password);
}
}
?>