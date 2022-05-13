<?php
header('Content-Type: application/json');
//$q= $_REQUEST['search'];
include "connect.php";
$class= $_REQUEST['class'];

//$ref=   $_REQUEST['ref'] ;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////SPECIAL ENCODING FUNCTION  - RECURSIVE FUNCTION THAT CAN FORCE CONVERT TO UTF-8 ALL THE STRINGS CONTAINED IN AN ARRAY
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			function utf8ize($d) {
				if (is_array($d)) {
					foreach ($d as $k => $v) {
						$d[$k] = utf8ize($v);
					}
				} 
				else if (is_string ($d)) {
					return utf8_encode($d);
				}
				return $d;
			}	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////SPECIAL ENCODING FUNCTION  - RECURSIVE FUNCTION THAT CAN FORCE CONVERT TO UTF-8 ALL THE STRINGS CONTAINED IN AN ARRAY
		////////////////////////////////////////////////	////////////////////////////////////////////////////////////////////////////////////////////////	
	
//////SALESORDER API/////////////	*3*
if($class == "SALESORDER"){
class SALESORDER extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		 //$q=   $_REQUEST['search'] ;
		 $fd=   $_REQUEST['fd'] ;
		 $td=   $_REQUEST['td'] ;
		 //$BPL=   $_REQUEST['BPLName'] ;
		 $ProjectCode=   $_REQUEST['ProjectCode'] ;
		$qry = "SELECT	
			a.Project			AS  ProjCode,
			a.ItemCode			AS	ItemCode,	
			a.Dscription 		AS  Description,
			a.U_ItemCode1   	AS  ItemCode1,
			a.U_ItemName   		AS  SubCode,
			a.Quantity   		AS  Quantity,
			a.U_Quantity   		AS  U_Quantity,
			a.Price   			AS  Price,
			a.LineTotal   		AS  LineTotal,
			SUM(a.LineTotal)	AS  Total_Before_Discount,
			D.BPLFrName           AS    BPLName
			
			from RDR1 a
			inner join ORDR b on a.docentry=b.DocEntry
			join OPRJ C ON C.PrjCode=A.Project
			JOIN OBPL D ON D.BPLId=B.BPLId
			WHERE B.DocDate BETWEEN '$fd ' AND '$td'
			AND C.PrjCode='$ProjectCode'
			group by a.Project,a.ItemCode,a.Dscription,a.U_ItemCode1,a.U_ItemName,a.Quantity,a.U_Quantity,a.Price,a.LineTotal,D.BPLFrName";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));

		}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}			
			//header("Content-type:application/json"); 
//return ($returnMsg);
//return 	 $returnMsg;
return ($returnMsg);

}
}
$obj=new SALESORDER();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}


//////OPENINGBALANCE API/////////////	*4*
if($class == "OPENINGBALANCE"){
class OPENINGBALANCE extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		 //$q=   $_REQUEST['search'] ;
		  $fd=   $_REQUEST['fd'] ;
		   $td=   $_REQUEST['td'] ;
		    //$BPL=   $_REQUEST['BPLName'] ;
			$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$qry = " SELECT A.DocNum,A.DocDate,B.ItemCode,B.ItemName,B.Quantity,B.Project,B.WhsCode,B.Price,C.BinCode,A.BPLName FROM OIQI A
INNER JOIN IQI1 B ON A.DocEntry=B.DocEntry
LEFT JOIN OBIN C ON C.WhsCode=B.WhsCode AND C.SL1Code=B.Project
join OPRJ F ON F.PrjCode=B.Project
			JOIN OBPL D ON D.BPLId=A.BPLId
			WHERE A.DocDate BETWEEN '$fd' AND '$td'
			AND F.PrjCode='$ProjectCode'";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
            }else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}				
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new OPENINGBALANCE();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



//////GRPO API/////////////	*5*
if($class == "GRPO"){

class GRPO extends connection{
	 function getOBOfInventory(){
		 $conn=$this->getconnection();
		 $ProjectCode=   $_REQUEST['ProjectCode'] ;
		  $fd=   $_REQUEST['fd'] ;
		   $td=   $_REQUEST['td'] ;
		    //$BPL=   $_REQUEST['BPLName'] ;
		$qry = "	 SELECT
			(A.DocNum)      AS 	Document_No,
			(A.DocStatus)   AS		Status,
			(B.Project)     AS		Project_Code,
			(B.ItemCode)    AS		Item_Code,
			(B.Dscription)  AS		Item_Description,
			(B.Quantity)    AS		Quantity,
			(B.Price)       AS		Rate,
			SUM(B.LineTotal)         AS		Total_Before_Discount,
			A.BPLName				 AS		BPLName 
										
			
			FROM OPDN A
			INNER JOIN PDN1 B ON A.DOCENTRY=B.DocEntry 
			join OPRJ C ON C.PrjCode=A.Project
			JOIN OBPL D ON D.BPLId=A.BPLId
			WHERE B.DocDate BETWEEN '$fd' AND '$td'
			AND C.PrjCode='$ProjectCode'
			group by A.DocNum,A.DocStatus,B.Project,B.ItemCode,B.Dscription,B.Quantity,B.Price,B.LineTotal,A.BPLName
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}		
			//header("Content-type:application/json"); 
//return ($returnMsg);
//return 	 $returnMsg;
return ($returnMsg);

}

}
$obj=new GRPO();
//print_r( $obj->getOBOfInventory());
echo  json_encode(utf8ize($obj->getOBOfInventory()), JSON_PRETTY_PRINT);
}


//////WTWSERIESIN API/////////////	*6*

if($class == "WTWSERIESIN"){
class WTWSERIESIN extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$fd=   $_REQUEST['fd'] ;
		$td=   $_REQUEST['td'] ;
		//$BPL=   $_REQUEST['BPLName'] ;
		$qry = " SELECT 
A.CardCode,A.CardName,A.DocNum,A.DocStatus,A.BPLName,A.DocDate 'Posting Date',a.docduedate 'Duedate',a.TaxDate 'Docdate',a.DocType,a.DocTotal,
c.ServCode,b.U_ItemCode1,b.U_ItemName,b.AcctCode,b.U_SReqQty 'Supply Total Qty',b.U_IReqQty 'Ins reqqty',b.LineTotal 'Total LC',b.U_SOPenQty 'Supplyy Pending Qty',b.U_BOQValue,b.Project,b.Price,
b.U_Schdcode,
b.ItemCode,b.Dscription
 FROM OINV A
INNER JOIN INV1 B ON A.DocEntry=B.DocEntry
LEFT OUTER JOIN OSAC C ON B.SacEntry=C.AbsEntry
LEFT OUTER JOIN OBPL D ON A.BPLId=D.BPLId
LEFT OUTER JOIN OPRJ E ON B.Project=E.PrjCode
									
		
			WHERE A.DocDate BETWEEN '$fd' AND '$td'
			AND E.PrjCode='$ProjectCode' 

";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}				
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new WTWSERIESIN();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}

//////WTWSERIESOUT API/////////////	*7*

if($class == "WTWSERIESOUT"){
class WTWSERIESOUT extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$fd=   $_REQUEST['fd'] ;
		$td=   $_REQUEST['td'] ;
		//$BPL=   $_REQUEST['BPLName'] ;
		$qry = "SELECT c.SeriesName,a.DocNum,b.Project, a.U_FromBin,a.ToBinCode,b.ItemCode,b.Dscription,b.Price,b.Quantity,b.LineTotal,sum(b.LineTotal)'Total_Before_Discount',a.Comments,A.BPLName FROM OWTR A
		INNER JOIN WTR1 B ON A.DocEntry=B.DocEntry
		LEFT JOIN NNM1 C ON C.ObjectCode=A.ObjType
		INNER JOIN OBIN E ON E.WhsCode=B.WhsCode AND E.BinCode=A.U_FromBin
		INNER JOIN B1_OinmWithBinTransfer D ON D.ItemCode=B.ItemCode 
		WHERE A.U_FromBin LIKE '%%GENERAL%%'
		AND A.ToBinCode LIKE '%%GENERAL%%'
		AND C.SeriesName LIKE '%%WTW%%'
		AND D.OutQty>'0'
		AND E.SL1Code<>B.Project
		and b.Project = '$ProjectCode'	
		 AND (B.DocDate BETWEEN '$fd' AND '$td')
		group by  c.SeriesName,a.DocNum,b.Project, a.U_FromBin,a.ToBinCode,b.ItemCode,b.Dscription,b.Price,b.Quantity,b.LineTotal,a.Comments,A.BPLName";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}				
	
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new WTWSERIESOUT();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



//////INVENTORYTRANSFERIVT API/////////////	*8*

if($class == "INVENTORYTRANSFERIVT"){
class INVENTORYTRANSFERIVT extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$fd=   $_REQUEST['fd'] ;
		$td=   $_REQUEST['td'] ;
		$qry = "SELECT c.SeriesName,a.DocNum,b.Project,    a.U_FromBin,a.ToBinCode,b.ItemCode,b.Dscription,b.Price,b.Quantity,b.LineTotal,sum(b.LineTotal)'Total_Before_Discount',a.Comments,A.BPLName FROM OWTR A
				INNER JOIN WTR1 B ON A.DocEntry=B.DocEntry
				LEFT JOIN NNM1 C ON C.ObjectCode=A.ObjType
				left OUTER JOIN (
				SELECT B.ItemCode,A.WhsCode,A.BinCode,B.OutQty,A.SL1Code FROM OBIN A
				LEFT JOIN B1_OinmWithBinTransfer B ON A.WhsCode=B.Warehouse AND A.SL1Code=B.PrjCode
				WHERE B.OutQty>'0'
				) F ON F.ITEMCODE=B.ItemCode AND F.WhsCode=B.WhsCode AND F.BinCode=A.U_FromBin AND F.SL1Code=B.Project
				WHERE A.U_FromBin LIKE '%%GENERAL%%'
				AND A.ToBinCode LIKE '%%CONSUMPTION%%'
				AND C.SeriesName LIKE '%%IVT%%'
				and (A.DocDate BETWEEN '$fd' AND '$td') 
				and b.Project = '$ProjectCode'
               
					group by  c.SeriesName,a.DocNum,b.Project, a.U_FromBin,a.ToBinCode,b.ItemCode,b.Dscription,b.Price,b.Quantity,b.LineTotal,a.Comments,A.BPLName";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));	
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}				
	
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new INVENTORYTRANSFERIVT();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



//////GOODS ISSUE  API/////////////	*9*
if($class == "GOODSISSUE"){
class GOODSISSUE extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		//$t=   $_REQUEST['region'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		$ProjectCode=$_REQUEST['ProjectCode'] ;
		//$BPL=$_REQUEST['BPLName'] ;
		$qry = "SELECT A.DocNum,A.DOCENTRY,b.ItemCode
,A.DocDate 'Posting Date',a.TaxDate 'Document Date',a.BPLName
FROM OIGE A
INNER JOIN IGE1 B ON A.DOCENTRY=B.DOCENTRY
left outer join OBPL c ON C.BPLId=A.BPLId
LEFT OUTER JOIN OPRJ D ON D.PrjCode=B.Project
where D.PrjCode='$ProjectCode'
AND A.DocDate BETWEEN '$fd' AND '$td'";
		$process = odbc_exec($conn,$qry);
		$json_response = array();
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
				$row_array = $fetch;
				$DOC_NO = utf8_encode($fetch['ItemCode']);   
//echo $DOC_NO ;
		$row_array['LineItems']= array();
		
		$qry1 = "SELECT B.DOCENTRY,
b.ItemCode,b.Dscription 'Item Description',b.Quantity,b.Price,(b.Quantity*b.Price)'Total',b.WhsCode,b.Project,b.unitMsr'UOM'
FROM OIGE A
INNER JOIN IGE1 B ON A.DOCENTRY=B.DOCENTRY
left outer join OBPL c ON C.BPLId=A.BPLId
LEFT OUTER JOIN OPRJ D ON D.PrjCode=B.Project
where D.PrjCode='$ProjectCode'
AND A.DocDate BETWEEN '$fd' AND '$td' and B.ItemCode ='$DOC_NO'";

		 $process1 = odbc_exec($conn,$qry1);
			while($fetch1 = odbc_fetch_array($process1)){
				$row_array['LineItems'][] = array(
					'ItemCode' => utf8_encode($fetch1['ItemCode']),
				    'Item Description' => utf8_encode($fetch1['Item Description']),
					'Quantity' => utf8_encode($fetch1['Quantity']),
				    'WhsCode' => utf8_encode($fetch1['WhsCode']),
					'Project' => utf8_encode($fetch1['Project']),
				    'UOM' => utf8_encode($fetch1['UOM'])
					);
				
				$LoadData1[]  = $fetch1;
			}
	array_push($json_response, $row_array); //push the values in the array*/
			}
			
           
			//$arr1 = array_map('Headerdata'=> ($LoadData));
			//$arr2 = array('Detailsdata'=> ($LoadData1));
			//$arraytest = array_map(null,$LoadData,$LoadData1);
			$arraytest = json_encode($json_response, JSON_PRETTY_PRINT);
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','Data'=> ($json_response));	
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new GOODSISSUE();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}







//////AR INVOICE API///////////// *10*

if($class == "ARINVOICE"){
	
class ARINVOICE extends connection{
	
	 function getGRPOlist(){
		 
		 $conn=$this->getconnection();
		//$BPLName=   $_REQUEST['BPLName'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		$ProjectCode=$_REQUEST['ProjectCode'] ;
		$qry = " SELECT 
A.CardCode,A.CardName,A.DocNum,A.DocStatus,A.BPLName,A.DocDate 'Posting Date',a.docduedate 'Duedate',a.TaxDate 'Docdate',a.DocType,a.DocTotal,
c.ServCode,b.U_ItemCode1,b.U_ItemName,b.AcctCode,b.U_SReqQty 'Supply Total Qty',b.U_IReqQty 'Ins reqqty',b.LineTotal 'Total LC',b.U_SOPenQty 'Supplyy Pending Qty',b.U_BOQValue,b.Project,b.Price,
b.U_Schdcode,
b.ItemCode,b.Dscription,
D.BPLFrName
 FROM OINV A
INNER JOIN INV1 B ON A.DocEntry=B.DocEntry
LEFT OUTER JOIN OSAC C ON B.SacEntry=C.AbsEntry
LEFT OUTER JOIN OBPL D ON A.BPLId=D.BPLId
LEFT OUTER JOIN OPRJ E ON B.Project=E.PrjCode
									
		
			WHERE A.DocDate BETWEEN '$fd' AND '$td'
			AND E.PrjCode='$ProjectCode'  
			


";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				//echo "hi";die;
				$LoadData1[]  = $fetch;
				$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData1));
			}
		}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
					
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new ARINVOICE();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



//////GENERALBINS API/////////////	*11*

if($class == "GENERALBINS"){
class GENERALBINS extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$fd=   $_REQUEST['fd'] ;
		$td=   $_REQUEST['td'] ;
					
		$qry = "SELECT B.ItemCode,B.Dscription,A.BinCode,B.INQTY,B.OUTQTY,(B.InQty-B.OutQty)AVAILABLEQTY,B.Price,(B.Price*(B.InQty-B.OutQty))Total_Before_Discount,B.DocDate FROM OBIN A
LEFT JOIN B1_OinmWithBinTransfer B ON A.WhsCode=B.Warehouse AND B.PrjCode=A.SL1Code
where b.ItemCode='ENDT00020'
 AND (A.CreateDate BETWEEN '$fd ' AND '$td')

and a.SL2Code='GENERAL'
GROUP BY B.ItemCode,B.Dscription,A.BinCode,B.InQty,B.OutQty,B.Price,B.DocDate
ORDER BY DocDate
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new GENERALBINS();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}




//////CONSUMPTIONBINS API/////////////	*12*
if($class == "CONSUMPTIONBINS"){
class CONSUMPTIONBINS extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		 //$t=   "010-AMAZONBOM-PH1-21";
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		$fd=   $_REQUEST['fd'] ;
		$td=   $_REQUEST['td'] ;
		$qry = "SELECT B.ItemCode,B.Dscription,A.BinCode,B.INQTY,B.OUTQTY,(B.InQty-B.OutQty)AVAILABLEQTY,B.Price,(B.Price*(B.InQty-B.OutQty))Total_Before_Discount,B.DocDate FROM OBIN A
LEFT JOIN B1_OinmWithBinTransfer B ON A.WhsCode=B.Warehouse AND B.PrjCode=A.SL1Code
where b.ItemCode='$ProjectCode'

 AND (A.CreateDate BETWEEN '$fd ' AND '$td')
 
and a.SL2Code='GENERAL'
GROUP BY B.ItemCode,B.Dscription,A.BinCode,B.InQty,B.OutQty,B.Price,B.DocDate
ORDER BY DocDate

";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));	
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new CONSUMPTIONBINS();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



/////REVENUE PER REGION///// *13*





//////OVERHEADSPERREGION API/////////////	*14*
if($class == "OVERHEADSPERREGION"){
class OVERHEADSPERREGION extends connection{
	 function getGRPOlist(){
		$conn=$this->getconnection();
		//$BPL=   $_REQUEST['BPLName'];
		$ProjectCode=   $_REQUEST['ProjectCode'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		$qry = "SELECT T2.AcctName,T1.Project,T3.PrjName,T4.BPLId,T4.BPLName
,SUM(Debit-Credit) Value
FROM OJDT T0 WITH(NOLOCK) JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
JOIN (SELECT FatherNum,AcctCode,CASE 
		WHEN FatherNum IN ('50301100','50301200') THEN 'Finance Cost' 
		WHEN FatherNum IN ('50102100') THEN 'Cost of Goods Sold'
		WHEN FatherNum IN ('50401101') THEN 'Depreciation & Amortisation'
		WHEN FatherNum IN ('50103300') THEN 'Direct Expenses'
		WHEN FatherNum IN ('50201100','50201200','50201300') THEN 'Employee Cost' END AcctName
		FROM OACT 
		WHERE FatherNum IN ('50301100','50301200','50102100','50401101','50103300','50201100','50201200','50201300')
		UNION
		SELECT B.FatherNum,A.AcctCode,'Operation Cost' AcctName
		FROM OACT A,OACT B
		WHERE B.AcctCode=A.FatherNum AND B.FatherNum='50501000' 
		) T2 ON T2.AcctCode=T1.Account
JOIN OPRJ T3 ON T3.PrjCode=T1.Project
JOIN OBPL T4 ON T4.BPLId=T1.BPLId
WHERE (T0.RefDate BETWEEN '$fd' AND '$td') 
AND (T1.Project='$ProjectCode' OR '$ProjectCode'='All')
GROUP BY T2.AcctName,T1.Project,T3.PrjName,T4.BPLId,T4.BPLName
ORDER BY AcctName,Project,BPLName
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));	
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new OVERHEADSPERREGION();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}




//////SALARY PER REGION API/////////////	*15*
if($class == "SALARYPERREGION"){
class SALARYPERREGION extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		//$t=   $_REQUEST['region'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		//$ProjectCode=$_REQUEST['ProjectCode'] ;
		//$BPL=$_REQUEST['BPLName'] ;
		$qry = "select Account,AcctName,BPLName,BPLId,Sum(OB)Ob,Sum(Debit)Debit,Sum(credit)credit,(Sum(Debit)-sum(credit))+sum(OB) Balance,Project,RefDate from 

(Select Account,t2.AcctName,Sum(t1.Debit-Credit)OB, 0 Debit,0 credit,t1.BPLId, t1. BPLName,t1.Project,T0.RefDate FROM OJDT T0 WITH(NOLOCK)
 JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
left join(SELECT FatherNum,AcctCode,CASE 
		WHEN FatherNum IN ('50201100') THEN 'Personal Cost' 
		WHEN FatherNum IN ('50201200') THEN 'Other Personal Cost'
		WHEN FatherNum IN ('50201300') THEN 'Directors Remuneration' 
		WHEN FatherNum IN ('50200000') then 'Employee Benefit Expenses' end 'AcctName'
		FROM OACT 
		WHERE FatherNum IN ('50201100','50201200','50201300','50200000')
		UNION
		SELECT B.FatherNum,A.AcctCode,'Employee Benefit Expenses' AcctName
		FROM OACT A,OACT B
		WHERE B.AcctCode=A.FatherNum AND B.FatherNum='500000000000000' )t2 on t1.Account=t2.AcctCode
WHERE iSnull(T2.AcctName,'')<>''
group by  Account,t2.AcctName,t1.BPLId,T1.BPLName,t1.Project,T0.RefDate

Union All

Select Account,t2.AcctName,0 OB ,Sum(t1.Debit)Debit,sum(Credit) credit,t1.BPLId,t1.BPLName,t1.Project,T0.RefDate FROM OJDT T0 WITH(NOLOCK)
 JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
 LEft Join(
SELECT FatherNum,AcctCode,CASE 
		WHEN FatherNum IN ('50201100') THEN 'Personal Cost' 
		WHEN FatherNum IN ('50201200') THEN 'Other Personal Cost'
		WHEN FatherNum IN ('50201300') THEN 'Directors Remuneration' 
		WHEN FatherNum IN ('50200000') then 'Employee Benefit Expenses' end 'AcctName'
		FROM OACT 
		WHERE FatherNum IN ('50201100','50201200','50201300','50200000')
		UNION
		SELECT B.FatherNum,A.AcctCode,'Employee Benefit Expenses' AcctName
		FROM OACT A,OACT B
		WHERE B.AcctCode=A.FatherNum AND B.FatherNum='500000000000000' )t2 on t1.Account=t2.AcctCode

		where iSnull(T2.AcctName,'')<>''
		group by  t1.Account,t2.AcctName,t1.BPLId,t1.BPLName,t1.Project,T0.RefDate
		)y
		where Isnull(AcctName,'')<>''
		aNd (RefDate BETWEEN '$fd' AND '$td')
		Group by Account,BPLId,BPLName,Project,AcctName,RefDate

		
Order by BPLId asc 

";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new SALARYPERREGION();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}


//////PROFITABILITYPERREGION API/////////////	*16*
if($class == "PROFITABILITYPERREGION"){
class PROFITABILITYPERREGION extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		//$t=   $_REQUEST['region'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		$ProjectCode=$_REQUEST['ProjectCode'] ;
		//$BPL=$_REQUEST['BPLName'] ;
		$qry = "SELECT T2.AcctName,T1.Project,T3.PrjName,T4.BPLId,T4.BPLName
FROM OJDT T0 WITH(NOLOCK) JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
JOIN (SELECT FatherNum,AcctCode,CASE 
		WHEN FatherNum IN ('50301100','50301200') THEN 'Finance Cost' 
		WHEN FatherNum IN ('50102100') THEN 'Cost of Goods Sold'
		WHEN FatherNum IN ('50401101') THEN 'Depreciation & Amortisation'
		WHEN FatherNum IN ('50103300') THEN 'Direct Expenses'
		WHEN FatherNum IN ('50201100','50201200','50201300') THEN 'Employee Cost' END AcctName
		FROM OACT 
		WHERE FatherNum IN ('50301100','50301200','50102100','50401101','50103300','50201100','50201200','50201300')
		UNION
		SELECT B.FatherNum,A.AcctCode,'Operation Cost' AcctName
		FROM OACT A,OACT B
		WHERE B.AcctCode=A.FatherNum AND B.FatherNum='50501000' 
		) T2 ON T2.AcctCode=T1.Account
JOIN OPRJ T3 ON T3.PrjCode=T1.Project
JOIN OBPL T4 ON T4.BPLId=T1.BPLId
WHERE (T0.RefDate BETWEEN '$fd' AND '$td') 
AND (T1.Project='$ProjectCode' OR '$ProjectCode'='All')
GROUP BY T2.AcctName,T1.Project,T3.PrjName,T4.BPLId,T4.BPLName
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));	
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new PROFITABILITYPERREGION();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}


//////PAYMENT RECEIVED API/////////////  *17*
if($class == "PAYMENTRECEIVED"){
class PAYMENTRECEIVED extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		//$t=   $_REQUEST['region'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		$ProjectCode=$_REQUEST['ProjectCode'] ;
		//$BPLName=$_REQUEST['BPLName'] ;
		$qry = "Select X.DocType,X.Docnum,docdate,BPLName,DocDueDate,CardCode,CardName,Address,
CounterRef,PrjCode,TaxDate,TransId,WtCode,BPLId,PayToCode,comments,JrnlMemo,
InvNo,ObjType,Invdate, acccode , accname, loccode, sumapplied, AppliedSys, BfDcntSumS,TrsfrSum
,DocTotalSy,BALANCEDUE from (
select A0.DocType,A0.docnum,A0.docdate,A0.BPLName,A0.docduedate,A0.CardCode
,A0.CardName,A0.Address,A0.CounterRef,A0.PrjCode,A0.TaxDate,A0.TransId,A0.WtCode,A0.BPLId,A0.PayToCode,A0.comments,A0.JrnlMemo,
B.docnum 'InvNo',B.ObjType,B.DocDate 'Invdate',NULL acccode ,NULL accname,NULL loccode,NULL sumapplied,NULL AppliedSys,NULL BfDcntSumS,A0.TrsfrSum,A0.DocTotalSy,    isnull(B.Doctotal,0)-Isnull(B.PaidToDate,0) BALANCEDUE
from ORCT A0
inner join RCT2 A1 on A0.DocEntry=A1.DocNum
Left Join OINV B On A1.InvType=B.ObjType And A1.DocEntry=B.DocEntry
Where  A0.DocType='c'
 UNION ALL
select A3.DocType,A3.docnum,A3.docdate,A3.BPLName,A3.docduedate,NULL CardCode,NULL ,NULL,NULL,A3.PrjCode,A3.TaxDate,A3.TransId,A3.WtCode,A3.BPLId,A3.PayToCode,A3.comments,A3.JrnlMemo,NULL,NULL,NULL
,A4.AcctCode,A4.AcctName,A4.LocCode ,SumApplied,NULL AppliedSys,NULL BfDcntSumS,NULL trsfrsum,NULL doctotal,NULL balancedue
from  ORCT A3 inner join RCT4 A4 on a3.DocEntry=a4.DocNum
WHERE  A3.DOCTYPE='A'
UNION ALL
select A5.DocType,A5.docnum,A5.docdate,A5.BPLName,A5.docduedate,A5.CardCode
,A5.CardName,A5.Address,A5.CounterRef,A5.PrjCode,A5.TaxDate,A5.TransId,A5.WtCode,A5.BPLId,A5.PayToCode,A5.comments,
A5.JrnlMemo,NULL,NULL,NULL,NULL,NULL,NULL,A6.SumApplied,A6.AppliedSys,BfDcntSumS,NULL trsfrsum ,NULL doctotal,NULL balancedue
FROM ORCT A5 
inner join RCT2 A6 on A5.DocEntry=A6.DocNum
Left Join OINV c On A6.InvType=c.ObjType And A5.DocEntry=c.DocEntry
where A5.DocType='s') x
WHERE (x.DocDate BETWEEN '$fd' AND '$td') 
aND 
(x.PrjCode='$ProjectCode' OR '$ProjectCode'='All')
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new PAYMENTRECEIVED();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}




//////LABER COST TILL DATE API/////////////	 *18*

if($class == "LABERCOSTTILLDATE"){
class LABERCOSTTILLDATE extends connection{
	 function getGRPOlist(){
		 $conn=$this->getconnection();
		//$t=   $_REQUEST['region'];
		//$q=   $_REQUEST['search'] ;
		//$date=$_REQUEST['date'] ;
		$fd=$_REQUEST['fd'] ;
		$td=$_REQUEST['td'] ;
		//$ProjectCode=$_REQUEST['ProjectCode'] ;
		//$BPLName=$_REQUEST['BPLName'] ;
		$qry = "select Account,BPLName,BPLId,Sum(OB)Ob,Sum(Debit)Debit,Sum(credit)credit,(Sum(Debit)-sum(credit))+sum(OB) Balance,Project,RefDate from 

(Select Account,t2.AcctName,Sum(t1.Debit-Credit)OB, 0 Debit,0 credit,t1.BPLId, t1. BPLName,t1.Project,t0.RefDate FROM OJDT T0 WITH(NOLOCK)
 JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
LEft Join OACT t2 on t2.AcctCode=t1.Account where t1.Account=50103301
group by  Account,t2.AcctName,t1.BPLId,T1.BPLName,t1.Project,t0.RefDate

Union All

Select Account,t2.AcctName,0 OB ,Sum(t1.Debit)Debit,sum(Credit) credit,t1.BPLId,t1.BPLName,t1.Project,t0.RefDate FROM OJDT T0 WITH(NOLOCK)
 JOIN JDT1 T1 WITH(NOLOCK) ON T0.TransId=T1.TransId
LEft Join OACT t2 on t2.AcctCode=t1.Account where t1.Account=50103301 
group by  Account,t2.AcctName,
t1.BPLId,t1.BPLName,t1.Project,t0.RefDate) y
where (RefDate BETWEEN '$fd' AND '$td')
Group by Account,BPLId,BPLName,Project,RefDate
Order by BPLId asc
";
		$process = odbc_exec($conn,$qry);
		if( odbc_num_rows( $process )>0 ) {
			while($fetch = odbc_fetch_array($process)){
				$LoadData[]  = $fetch;
			}
			$returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=> ($LoadData));
			}else {
              $returnMsg =array('status_code'=>'200' , 'message'=>'success','data'=>'No Data');
}
			//header("Content-type:application/json"); 
return ($returnMsg);

}
}
$obj=new LABERCOSTTILLDATE();
//print_r( $obj->getGRPOlist());
echo  json_encode(utf8ize($obj->getGRPOlist()), JSON_PRETTY_PRINT);
}



?>



            