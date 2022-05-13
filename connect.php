<?php

class connection
{
    public function getconnection()
    {
        $serverName = "13.0.160.5";
        $user = "sa";
        $password = "p@ssw0rd";
        return $conn =  odbc_connect("Driver={SQL Server Native Client 11.0};Server=$serverName;Database=DemoAPI;", $user, $password);
    
    
    } 
    
    
   
}

//  <?php
// $from = '0.00';
// $to = '0.00';
// function cal($frmT, $toT)
// {
// return '1.0';
// }
// echo "";
// echo "<input type='time' id='timert' class='timerr' value=''>";
// echo cal('16:09', '16:09');
