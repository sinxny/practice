<?php
    session_start();
    @ini_set("display_errors", "On");
    //@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    if (!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "../lib/include.php";
    @error_reporting(E_ALL);
    $db = new DB;

    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    $uno = $data["uno"];

    $SQL = "WITH TR AS(
                            SELECT D.RNO, R.REASON_TEXT, SUM(D.USE_TIME) AS TIME_SUM
                            FROM AN_USE_DETAIL D
                            LEFT OUTER JOIN AN_REASON R ON D.RNO = R.RNO
                            WHERE D.UNO = 1
                            GROUP BY D.RNO, R.REASON_TEXT
                        )
            SELECT TR.*, DENSE_RANK() OVER (ORDER BY TIME_SUM DESC) AS RANK, ROUND(RATIO_TO_REPORT(TIME_SUM) OVER(), 2) * 100 || '%' AS RATIO
            FROM TR";
    $db->query($SQL);
    $rowCnt = $db->nf();
    $reasonList = [];
    $reasonSumList = [];
    $etcSum = 0;
    if($rowCnt > 0) {
        while($db->next_record()) {
            $row = $db->Record;

            $reasonAllData[] = array(
                "reasonText" => $row["reason_text"],
                "timeSum" => $row["time_sum"],
                "rank" => $row["rank"],
                "ratio" => $row["ratio"]
            );

            array_push($reasonList, $row["reason_text"]);
            array_push($reasonSumList, $row["time_sum"]);
        }
    }

    $result = array(
        "reasonAllData" => $reasonAllData,
        "reasonList" => $reasonList,
        "reasonSumList" => $reasonSumList,
        "rowCnt" => $rowCnt
    );

    echo json_encode($result);
?>