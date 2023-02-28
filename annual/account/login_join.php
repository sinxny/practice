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

    // 회원가입
    if($mode == "join") {
        $proceed = true;
        // 아이디 중복체크
        $SQL = "SELECT COUNT(*)
                FROM AN_USER
                WHERE USER_ID = '{$data["userId"]}'";
        $isIdDup = $db->query_one($SQL);

        // 닉네임 중복체크
        $SQL = "SELECT COUNT(*)
                FROM AN_USER
                WHERE NICK_NAME = '{$data["nickName"]}'";
        $isNameDup = $db->query_one($SQL);

        if($isIdDup > 0) {
            $proceed = false;
            $msg = "userId|중복된 ID입니다.";
        } else if($isNameDup > 0) {
            $proceed = false;
            $msg = "nickName|중복된 NICK NAME입니다.";
        } else {
            $SQL = "INSERT INTO AN_USER(UNO, USER_ID, PASSWORD, NICK_NAME)
                    VALUES (SEQ_AN_UNO.NEXTVAL, '{$data["userId"]}', '{$data["password"]}', '{$data["nickName"]}')";
            if($db->query($SQL)) {
                $proceed = true;
                $msg = "회원가입이 완료되었습니다.";
            } else {
                $proceed = false;
            }
        }
        $result = array(
            "proceed" => $proceed,
            "msg" => $msg
        );
        echo json_encode($result);
    }
    // 로그인
    if($mode == "login") {
        $proceed = true;

        $SQL = "SELECT UNO, NICK_NAME
                FROM AN_USER
                WHERE USER_ID = '{$data["loginId"]}'
                AND PASSWORD = '{$data["loginPassword"]}'";
        $db->query($SQL);
        $isUser = $db->nf();

        if($isUser == 1) {
            while($db->next_record()) {
                $row = $db->Record;
                $uno = $row["uno"];
                $nickName = $row["nick_name"];
            }
            $proceed = true;
            $msg = '';
        } else {
            $proceed = false;
            $msg = '로그인에 실패하였습니다.';
            $uno = null;
            $nickName = null;
        }

        $result = array(
            "proceed" => $proceed,
            "msg" => $msg,
            "uno" => $uno,
            "nickName" => $nickName
        );
        echo json_encode($result);
    }
?>