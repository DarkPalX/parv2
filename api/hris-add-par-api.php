<?php


// $conn_d['davao']['type'] = 'sqlsrv';
// $conn_d['davao']['host'] = '172.16.20.43';
// $conn_d['davao']['name'] = 'SyncHRIS';
// $conn_d['davao']['uname'] = 'db_synchris';
// $conn_d['davao']['pword'] = 'xushuX9k';

$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = '172.16.20.43';
$conn_a['agusan']['name'] = 'SyncHRIS';
$conn_a['agusan']['uname'] = 'db_synchris';
$conn_a['agusan']['pword'] = 'xushuX9k';

//  $conn_d['davao']['type'] = 'sqlsrv';
//  $conn_d['davao']['host'] = '172.16.10.42\philsaga_db';
//  $conn_d['davao']['name'] = 'PMC-DAVAO';
//  $conn_d['davao']['uname'] = 'sa';
//  $conn_d['davao']['pword'] = '@Temp123!';

// $conn_a['agusan']['type'] = 'sqlsrv';
// $conn_a['agusan']['host'] = '172.16.20.42\agusan_db';
// $conn_a['agusan']['name'] = 'PMC-AGUSAN-NEW';
// $conn_a['agusan']['uname'] = 'sa';
// $conn_a['agusan']['pword'] = '@Temp123!';


// LOCAL RAEVIN
$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = '.\RAEVIN';
$conn_a['agusan']['name'] = 'SyncHRIS';
$conn_a['agusan']['uname'] = 'sa';
$conn_a['agusan']['pword'] = 'P@ssw0rd';	


$result = '';


if(isset($_GET['emp'])){

	$emp = $_GET['emp'];

	// $pdoempdata = new PDO($conn_a['agusan']['type'].":server=".$conn_a['agusan']['host'].";Database=".$conn_a['agusan']['name'], $conn_a['agusan']['uname'], $conn_a['agusan']['pword']);
	// $pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	try {
		$pdoempdata = new PDO(
			$conn_a['agusan']['type'] . ":Server=" . $conn_a['agusan']['host'] . ";Database=" . $conn_a['agusan']['name'],
			$conn_a['agusan']['uname'],
			$conn_a['agusan']['pword']
		);
		$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die("Connection failed: " . $e->getMessage());
	}

	$sql = "
		SELECT EmpID, FullName, LName, Active, DeptDesc
		FROM (
			SELECT 
				e.EmpID,
				e.FullName,
				e.LName,
				e.Active,
				d.DeptDesc,
				ROW_NUMBER() OVER (PARTITION BY e.EmpID ORDER BY e.ModifiedDate DESC, e.SeqID DESC) AS rn
			FROM ViewHREmpMaster e
			LEFT JOIN hrposition p ON p.PositionID = e.PositionID
			LEFT JOIN hrdepartment d ON d.deptid = e.deptid
			WHERE e.Active = 1
			  AND (e.EmpID LIKE ? OR e.LName LIKE ?)
		) t
		WHERE rn = 1
	";

	$empdatasql = $pdoempdata->prepare($sql);

	
	// $empdatasql = $pdoempdata->prepare('SELECT DISTINCT FullName, EmpID, LName, Active FROM ViewHREmpMaster WHERE Active = 1 AND (EmpID LIKE ? OR LName LIKE ?)');
	// $empdatasql = $pdoempdata->prepare('SELECT DISTINCT (e.FullName),e.EmpID, e.LName, e.Active,d.DeptDesc FROM ViewHREmpMaster e LEFT JOIN hrposition p ON p.PositionID = e.PositionID LEFT JOIN hrdepartment d ON d.deptid = e.deptid WHERE e.Active = 1 AND e.EmpID LIKE ? OR e.LName LIKE ?');
	
	$empdatasql->execute(["%$emp%", "%$emp%"]);

	for($i=0; $rowempdata = $empdatasql->fetch(PDO::FETCH_ASSOC); $i++){   
		$result .= "<li class='emp_li'><a href='#'>"
			.$rowempdata['EmpID']." - ".$rowempdata['FullName'].
			"<span style='display:none;'>=".$rowempdata['DeptDesc']."</span></a></li>|";
	}

	echo $result ?: "no employee found";

}
    
?>