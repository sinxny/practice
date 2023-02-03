<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>연차분석</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/button.css" />
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="jquery/jquery-ui-1.13.0/jquery-ui.min.css" />
    <script type="text/javascript" src="jquery/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <script type="text/javascript" src="jquery/jquery-ui-1.13.0/i18n/datepicker-ko.js"></script>
    <script type="text/javascript" src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.13/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Font online-->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> -->
    <!--        Animate.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!-- Google JQuery CDN -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
</head>

<body>
<script>
$(document).ready(function() {
    if(!sessionStorage.getItem("isLogin")) {
        $("#mainPage").empty().load("account/login_join_view.php");
    } else {
        $(".dropdown-item-text").text(sessionStorage.getItem("nickName"));
    }
});

function onLogoutClick() {
    sessionStorage.removeItem("isLogin");
    sessionStorage.removeItem("nickName");
    location.reload();
}

</script>
<div id="mainPage">
    <div class="panel shadow1" style="text-align:center;">
        <div class="header">
            <div class="nav-item dropdown text-right">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown" title="로그아웃" style="padding: 0.7rem 1rem !important;">
                    <span class="fa-stack" style="font-size:large">
                        <i class="far fa-circle fa-stack-2x"></i>
                        <i class="fas fa-user fa-stack-1x"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-item-text"></div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" onclick="onLogoutClick()">Logout</a>
                </div>
            </div>
        </div>
        <div>
            <div class="exBtn btn-6 m-4">연차 등록 / 조회</div>
        </div>
    </div>
</div>
</body>

</html>