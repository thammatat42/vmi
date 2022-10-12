<?php

  /////////////////////
 //  MSSQL function //
/////////////////////
function MSSQL_GETDATA_SQL_MEMORY($conn,$sql)
{
	return 0;
}

function MSSQL_GETDATA_SQL($conn,$sql)
{
	$result	= sqlsrv_query($conn,$sql,array(SQLSRV_PHPTYPE_STRING('UTF-8'))) or die(print_r( sqlsrv_errors(), true));
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$rows[] = $row;
	}
	return $rows;
}



function MSSQL_GETDATA_SQL_CHARSET($conn,$sql)
{
	$result	= sqlsrv_query($conn,$sql,array(SQLSRV_PHPTYPE_STRING('UTF-8'))) or die(print_r( sqlsrv_errors(), true));
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$rows[] = $row;
	}
	return $rows;
}

function MSSQL_NONQUERY($conn,$sql)
{
	$result	= sqlsrv_query($conn,$sql,array(SQLSRV_PHPTYPE_STRING('UTF-8'))) or die($sql.print_r( sqlsrv_errors(), true));
	return $result;
}
function MSSQL_NONQUERY_INSERT($conn,$sql)
{
	$result	= sqlsrv_query($conn,$sql,array(SQLSRV_PHPTYPE_STRING('UTF-8'))) or die($sql.print_r( sqlsrv_errors(), true));
	
	sqlsrv_next_result($result); 
	sqlsrv_fetch($result); 
	return sqlsrv_get_field($result, 0); 
}

function GET_LAST_ID($reslut) {
    sqlsrv_next_result($reslut);
    sqlsrv_fetch($reslut);
    return sqlsrv_get_field($reslut, 0);
}
function MSSQL_GETDATA_SQL2($conn,$sql)
{
	$result	= sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_NUMERIC))
	{
		$rows[] = $row;
	}
	return $rows;
}
function MSSQL_GETDATA_SQL_ADDHEAD($conn,$sql,$head)
{
	$result	= sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));
	$rows[] = $head;
	while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_NUMERIC))
	{
		$rows[] = $row;
	}
	return $rows;
}

function MSSQL_EXEC_SP($conn,$sp_name,$params)
{

    //$params = array("D154417", "D154416");
    //$sp_name = "[dbo].[SP_GET_JOC]";
    $sql = "EXEC";
    for($i=0;$i<count($params);$i++)$arai[]="?";
	if($arai)
    $str_arai = implode(",",$arai);

    $sql = $sql.' '.$sp_name.' '.$str_arai;
    $stmt = sqlsrv_query( $conn, $sql, $params);
    if( $stmt === false ) {
         die( print_r( sqlsrv_errors(), true));
    }
	
	$rows = array();
	while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
	{
		$rows[] = $row;
	}
	$tbs[] = $rows;
	
	
	while(sqlsrv_next_result($stmt))
	{
		$rows = array();
		while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC))
		{
			$rows[] = $row;
		}
		$tbs[] = $rows;
	}
	if(count($tbs)==1)
		return $tbs[0];
	else
		return $tbs;
}

function MSSQL_EXEC_SP2($conn,$sp_name,$params)
{

    //$params = array("D154417", "D154416");
    //$sp_name = "[dbo].[SP_GET_JOC]";
    $sql = "EXEC";
    for($i=0;$i<count($params);$i++)$arai[]="?";
	if($arai)
    $str_arai = implode(",",$arai);

    $sql = $sql.' '.$sp_name.' '.$str_arai;
    $stmt = sqlsrv_query( $conn, $sql, $params);
    if( $stmt === false ) {
         die( print_r( sqlsrv_errors(), true));
    }
    print_r($stmt);
    
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $rows[] = $row;
    }
	//$rows[0]{field];
	//$rows[1]{field];
	//foreach($rows as $row)
	//$row[field];
	//json_encode($rows;
    return $rows;
}


function MSSQL_INSERT_DATA_FRM_DT($conn,$table,$fields,$dt)
{
	//print_r($fields);
	$columns = $fields;
	$columsName = implode(", ", $columns);
	$paras = implode(", ",array_fill(0, count($columns), "?"));
	$sql = "INSERT INTO $table ( $columsName ) VALUES ( $paras )";
	
	
	
	//echo $sql;
	$ref= array();
	$i=0;
	foreach($dt[2] as $col)
	{
	   $data_row[] = &$ref[$i++];
	}
	//print_r($ref);
	
	$db_cols = count($columns);
	if($i != $db_cols)
	{
		echo "Error: Column Not Match : Excel(".$i.")/Database(".$db_cols.") ";
		die();
	}
	$stmt = sqlsrv_prepare( $conn, $sql, $data_row);
	if( !$stmt ) {
		die( print_r( sqlsrv_errors(), true));
	}

	// Execute the statement for each order.
	$r=0;
	foreach( $dt as $row) 
	{
		$r++;
		if($r<2)continue;
		$i=0;
		$valeString="";
		foreach($row as $key => $value)
	    {
			if($value==""||$value=="RRT")$ref[$i++]=null;
			else if (is_numeric($value))
				$ref[$i++]=(int)$value;
			else if(strtotime($value))
			    $ref[$i++]=date("Y-m-d",strtotime($value));
			else
				$ref[$i++]=$value;
			
			$valeString .= ','.$value;
		}
		//print_r($ref);
		if( sqlsrv_execute( $stmt ) === false ) {
			  echo "Error : ".sqlsrv_errors()[0][message].$valeString;
			  die();
		}
	}
	echo "Row Effect : $r";
}


function MSSQL_INSERT_DATA_PARAM($conn,$table,$data)
{
	//print_r($fields);
	$columns = array_keys($data);
	$columsName = implode(", ", $columns);
	$paras = implode(", ",array_fill(0, count($columns), "?"));
	$sql = "INSERT INTO $table ( $columsName ) VALUES ( $paras )";
	$data_row = array_values($data);
	$stmt = sqlsrv_query( $conn, $sql, $data_row);
	if($stmt === false ) {
			  echo "Error : ".sqlsrv_errors()[0][message];
			  die();
	}
	return $stmt;

}
?>