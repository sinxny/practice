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
        // 개인 연차 목록 가져오기
        $uno = $data["uno"];
        $SQL = "SELECT D.ANO, U.NICK_NAME, TO_CHAR(D.USE_DATE, 'YYYY-MM-DD') AS USE_DATE, D.USE_TIME, R.REASON_TEXT, D.ETC 
                FROM AN_USE_DETAIL D
                INNER JOIN AN_USER U ON D.UNO = U.UNO
                INNER JOIN AN_REASON R ON D.RNO = R.RNO
                WHERE D.UNO = {$uno}
                ORDER BY USE_DATE DESC";
        $db->query($SQL);
        
        $annualList = [];
        while($db->next_record()) {
            $row = $db->Record;
    
            $annualList[] = array(
                "ano" => $row["ano"],
                "nickName" => $row["nick_name"],
                "useDate" => $row["use_date"],
                "useTime" => $row["use_time"],
                "reasonText" => $row["reason_text"],
                "etc" => $row["etc"]
            );
        }
    
        $result = array(
            "annualList" => $annualList
        );
    }
    // 삭제
    else if($mode == "delete") {
        $uno = $data["uno"];
        $ano = $data["ano"];

        $SQL = "DELETE FROM AN_USE_DETAIL
                WHERE UNO = {$uno}
                AND ANO = {$ano}";
        if($db->query($SQL)) {
            $proceed = true;
        } else {
            $proceed = false;
        }
        
        $result = array(
            "proceed" => $proceed
        );
    }
    
    echo json_encode($result);
?>