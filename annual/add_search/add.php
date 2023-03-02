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
                WHERE UNO = {$uno}
                ORDER BY RNO DESC";
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
    // 추가
    else if($mode == "add") {
        $uno = $data["uno"];
        $useDate = $data["useDate"];
        $useTime = $data["useTime"];
        $annualReason = $data["annualReason"];
        $directReason = $data["directReason"];
        $annualEtc = $data["annualEtc"];

        $proceed = false;

        // 직접 추가인 경우
        if($annualReason == "direct") {
            $SQL = "SELECT * 
                    FROM AN_REASON
                    WHERE TRIM(REASON_TEXT) = TRIM('{$directReason}')
                    AND UNO = {$uno}";
            $db->query($SQL);

            if($db->nf() > 0) {
                $db->next_record();
                $row = $db->Record;
                $rno = $row["rno"];
            } else {
                $SQL = "INSERT INTO AN_REASON(RNO, UNO, REASON_TEXT)
                        VALUES(SEQ_AN_RNO.NEXTVAL, {$uno}, '{$directReason}')";
                if($db->query($SQL)) {
                    $SQL = "SELECT SEQ_AN_RNO.CURRVAL AS RNO
                            FROM DUAL";
                    $db->query($SQL);
                    $db->next_record();
                    $row = $db->Record;
                    $rno = $row["rno"];
                }
            }
        } else {
            $rno = $annualReason;
        }

        $SQL = "INSERT INTO AN_USE_DETAIL(ANO, UNO, USE_DATE, USE_TIME, RNO, ETC)
                VALUES(SEQ_AN_ANO.NEXTVAL, {$uno}, TO_DATE('{$useDate}','YYYY-MM-DD'), {$useTime}, {$rno}, '{$annualEtc}')";
        if($db->query($SQL)) {
            $proceed = true;
        }

        $result = array(
            "proceed" => $proceed
        );
    }
    echo json_encode($result);
?>