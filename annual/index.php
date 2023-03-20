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
    <script type="text/javascript" src="js/grp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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

    // 등록버튼
    $("#btnAddAnnual").on('click', function() {
        modalPageShow("add");
    })
    // 조회버튼
    $("#btnSearchAnnual").on('click', function() {
        modalPageShow("search")
    });
    // 저장버튼
    $("#btnSave").on('click', onBtnSaveClick);
});

// 로그아웃
function onLogoutClick() {
    sessionStorage.removeItem("isLogin");
    sessionStorage.removeItem("nickName");
    location.reload();
}

// 모달 페이지
function modalPageShow(menu) {
    var title = "";
    var url = "";
    if(menu == "search") {
        title = "연차 조회";
        url = "add_search/search_view.php";
        $("#btnSave").hide();
        $("#btnSearchAnnual").hide();
        $("#btnAddAnnual").show();
    } else if(menu == "add") {
        title = "연차 등록"
        url = "add_search/add_view.php";
        $("#btnSave").show();
        $("#btnSearchAnnual").show();
        $("#btnAddAnnual").hide();
    } else if(menu == "analysis") {
        title = "연차 분석";
        url = "analysis/chart_view.php";
        $("#btnSave").hide();
        $("#btnSearchAnnual").hide();
        $("#btnAddAnnual").hide();
    } else if(menu == "letter") {
        title = "개발자에게 ..";
        url = "developing.php"
        // url = "letter/to_developer_view.php";
        $("#btnSave").hide();
        $("#btnSearchAnnual").hide();
        $("#btnAddAnnual").hide();
    }

    $("#modalPage .modal-title").text(title);
    $("#modalPage .modal-body").load(url);
    $("#modalPage").modal("show");
}

// 저장 버튼
function onBtnSaveClick() {
    if(chkAnnualDate() & chkAnnualReason()) {
        var url = "./add_search/add.php";
        var useDate = $("#useDate").val();
        var useTime = $("#useTime").val();
        var annualReason = $("#annualReason").val();
        var directReason = $("#directReason").val();
        var annualEtc = $("#annualEtc").val();
        var info = {
            mode: 'add',
            uno: sessionStorage.getItem("uno"),
            useDate: useDate,
            useTime: useTime,
            annualReason: annualReason,
            directReason: directReason,
            annualEtc: annualEtc
        }
        axios.post(url, info)
        .then(function(response) {
            if(response["data"]["proceed"] == true) {
                modalPageShow("search");
            }
        })
        .finally(function() {

        })
        .catch(function(error){
            console.log(error);
        });
    }
}

// 날짜 형식 체크
function chkAnnualDate() {
    var date = $("#useDate").val();
    var result = chkDateFormat(date);
    if(result == '') {
        $("#useDate").addClass("is-invalid");
        $("#useDate").siblings("div").text("날짜를 입력하세요.");
        return false;
    } else if (result == false) {
        $("#useDate").addClass("is-invalid");
        $("#useDate").siblings("div").text("날짜형식이 잘못되었습니다.");
        return false;
    } else {
        $("#useDate").removeClass("is-invalid");
        $("#useDate").siblings("div").text("");
        return true;
    }
}

// 연차 사유 체크
function chkAnnualReason() {
    var annualReason = $("#annualReason").val();
    if(annualReason == "direct") {
        var directReason = $("#directReason").val();

        if($.trim(directReason) == '') {
            $("#directReason").addClass("is-invalid");
            $("#directReason").siblings("div").text("사유를 입력하세요.");
            return false;
        } else {
            $("#directReason").removeClass("is-invalid");
            $("#directReason").siblings("div").text("");
            return true;
        }
    } else {
        $("#directReason").removeClass("is-invalid");
        $("#directReason").siblings("div").text("");
        $("#directReason").val("");
        return true;
    }
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
            <div class="exBtn btn-6 m-4" onclick="modalPageShow('search')">연차 등록 / 조회</div>
            <div class="exBtn btn-6 m-4" onclick="modalPageShow('analysis')">연차사유 분석</div>
            <div class="exBtn btn-6 m-4" onclick="modalPageShow('letter')">To Developer</div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="modalPage">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"></h4>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary" id="btnAddAnnual">등록</button>
                <button type="button" class="btn btn-sm btn-primary" id="btnSearchAnnual">조회</button>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body"></div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="justify-content: space-around!important">
            <button type="button" class="btn btn-primary" id="btnSave">저장</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
        </div>
        
      </div>
    </div>
  </div>
</body>

</html>