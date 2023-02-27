<?php
    session_start();
    @ini_set("display_errors", "On");
    //@error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    if (!defined("_INCLUDE_")) require_once $_SERVER["DOCUMENT_ROOT"] . "../lib/include.php";
    @error_reporting(E_ALL);
    $db = new DB;

    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $mode = $data["mode"];

    if($mode == "init") {
        // 연차 사유 가져오기
        $uno = $data["uno"];
        $SQL = "SELECT RNO, REASON_TEXT 
                FROM AN_REASON
                WHERE UNO = {$uno}";
        $db->query($SQL);

        while($db->next_record()) {
            $row = $db->Record;

            $reasonList[] = array(
                "rno" => $row["rno"],
                "reasonText" => $row["reason_text"]
            );
        }

        $result = array(
            "reasonList" => $reasonList
        );
    }
    echo json_encode($result);
?>