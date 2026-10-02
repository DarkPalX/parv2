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

    try {
        // Run your exact distinct query ordered alphabetically
        $sql = "SELECT DISTINCT DeptDesc FROM JUNDRIE_EmpReports WHERE Active = 1 ORDER BY DeptDesc";
        $stmt = $pdo->query($sql);
        $departments = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        echo json_encode($departments ?: []);
    } catch (PDOException $e) {
        echo json_encode([]);
    }

?>