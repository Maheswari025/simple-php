<?php
// header('Content-Type: application/json');
include "connect.php";
// $class = $_REQUEST['class'];
function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

// if ($class == "EMPLOYEEDETAILS") {
    class EMPLOYEEDETAILS extends connection
    {
        function getEMPlist()
        {
            $conn = $this->getconnection();
            $EmpID =   $_REQUEST['ID'];
            $Name =   $_REQUEST['Name'];
            $qry = "SELECT * FROM emp WHERE Name='$Name' and EmpID='$EmpID'";
            $process = odbc_exec($conn, $qry);
            if (odbc_num_rows($process) > 0) {
                while ($fetch = odbc_fetch_array($process)) {
                    $LoadData[]  = $fetch;
                }
                $returnMsg = array('status_code' => '200', 'message' => 'success', 'data' => ($LoadData));
            } else {
                $returnMsg = array('status_code' => '200', 'message' => 'success', 'data' => 'No Data');
            }
            return ($returnMsg);
        }
    }
    $obj = new EMPLOYEEDETAILS();
    print_r( $obj->getEMPlist());
    echo  json_encode(utf8ize($obj->getEMPlist()), JSON_PRETTY_PRINT);
    echo "123";
// }



