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
    // $conn_a['agusan']['type'] = 'sqlsrv';
    // $conn_a['agusan']['host'] = '.\RAEVIN';
    // $conn_a['agusan']['name'] = 'SyncHRIS';
    // $conn_a['agusan']['uname'] = 'sa';
    // $conn_a['agusan']['pword'] = 'P@ssw0rd';	



    try {
        // Pointed directly to $conn_a['agusan'] to fix the undefined variable notices
        $pdo = new PDO(
            $conn_a['agusan']['type'] . ":Server=" . $conn_a['agusan']['host'] . ";Database=" . $conn_a['agusan']['name'],
            $conn_a['agusan']['uname'],
            $conn_a['agusan']['pword']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo json_encode([]);
        exit;
    }

    if (isset($_GET['dept'])) {
        $deptDesc = $_GET['dept'];

        try {
            // Query 1: Get matching department IDs
            $deptSql = "SELECT deptid FROM HRDepartment WHERE DeptDesc = ?";
            $deptStmt = $pdo->prepare($deptSql);
            $deptStmt->execute([$deptDesc]);
            $deptIds = $deptStmt->fetchAll(PDO::FETCH_COLUMN, 0);

            if (empty($deptIds)) {
                echo json_encode([]);
                exit;
            }

            // Build placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($deptIds), '?'));

            // Query 2: Fetch distinct employee IDs
            $empSql = "SELECT DISTINCT EmpID FROM viewHREmpMaster WHERE DeptID IN ($placeholders) AND Active=1";
            $empStmt = $pdo->prepare($empSql);
            $empStmt->execute($deptIds);
            
            $empIds = $empStmt->fetchAll(PDO::FETCH_COLUMN, 0);

            echo json_encode($empIds);

        } catch (PDOException $e) {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }

?>