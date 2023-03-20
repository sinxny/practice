<style>
label {
    margin: 0 !important
}
</style>
<script>
$(document).ready(function() {
    //--------- change color value of the form text/password inputs -----
    const textInputs = $("input[type='textbox']");
    const passwordsInputs = $("input[type='password']");
    //--------- Login screen swicth -----
    $("button").click(function(event) { //  prevent buttons in form to reload
        event.preventDefault();
    });
    $("a").click(function(event) { //  prevent 'a' links in form to reload
        event.preventDefault();
    });

    $("#sign_up").click(function() { // when click Sign Up button, hide the Log In elements, and display the Sign Up elements
        $("#title-login").toggleClass("hidden", true);
        $("#login-fieldset").toggleClass("hidden", true);
        $("#login-form-submit").toggleClass("hidden", true);
        $("#lost-password-link").toggleClass("hidden", true);
        $("#sign_up").toggleClass("active-button", false);
        $("#log_in").removeAttr("disabled");

        $("#title-signup").toggleClass("hidden", false);
        $("#signup-fieldset").toggleClass("hidden", false);
        $("#signup-form-submit").toggleClass("hidden", false);
        $("#log_in").toggleClass("active-button", true);
        $("#sign_up").prop('disabled', true);

        $("input[type=text]").val('');
        $("input").removeClass("validateInput");
        $("input").next("div").find("label").text("");
        $("input").next("div").hide();
    });

    $("#log_in").click(function() { // when click Log In button, hide the Sign Up elements, and display the Log In elements
        $("#title-login").toggleClass("hidden", false);
        $("#login-fieldset").toggleClass("hidden", false);
        $("#login-form-submit").toggleClass("hidden", false);
        $("#lost-password-link").toggleClass("hidden", false);
        $("#sign_up").toggleClass("active-button", true);
        $("#log_in").prop('disabled', true);

        $("#title-signup").toggleClass("hidden", true);
        $("#signup-fieldset").toggleClass("hidden", true);
        $("#signup-form-submit").toggleClass("hidden", true);
        $("#log_in").toggleClass("active-button", false);
        $("#sign_up").removeAttr("disabled");

        $("input[type=text]").val('');
        $("input").removeClass("validateInput");
        $("input").next("div").find("label").text("");
        $("input").next("div").hide();
    });
});

var vmLS = new Vue({
    el: "#app",
    data: {
        userId: '',
        password: '',
        chkPassword: '',
        nickName: '',
        loginId: '',
        loginPassword: ''
    },
    methods : {
        // sign up 버튼 클릭
        signUpAnnaul() {
            var data = this;
            var validateCnt = 0;
            $("#signup-fieldset input").each(function() {
                if(data.validateCheck(this) == false) {
                    validateCnt++
                }
            });

            if(validateCnt == 0) {
                // 비밀번호 체크
                if(data.password != data.chkPassword) {
                    data.initInput();
                    $("#password").addClass("validateInput");
                    $("#chkPassword").addClass("validateInput");
                    $("#chkPassword").next("div").find("label").text("비밀번호가 일치하지 않습니다.");
                    $("#chkPassword").next("div").show();
                } else {
                    // 초기화
                    data.initInput();

                    // 회원가입 시도
                    var url = "./account/login_join.php";
                    var info = {
                        mode : "join",
                        userId: data.userId,
                        password: data.password,
                        chkPassword: data.chkPassword,
                        nickName: data.nickName
                    }
                    axios.post(url, info)
                    .then(function(response) {
                        var response = response["data"];
                        if(response["proceed"] == true) {
                            alert(response["msg"]);
                            data.resetInput();
                            $("#log_in").trigger("click");
                        } else {
                            // input 초기화
                            data.initInput();
                            const [id, msg] = response["msg"].split("|");
                            $("#" + id).addClass("validateInput");
                            $("#" + id).next("div").find("label").text(msg);
                            $("#" + id).next("div").show();
                        }
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            }
        },
        // 유효성 체크
        validateCheck(obj) {
            var passCnt = 0;
            if($.trim($(obj).val()) == '') {
                $(obj).addClass("validateInput");
                $(obj).val('');
                passCnt++;
            } else {
                $(obj).removeClass("validateInput");
            }

            if(passCnt > 0) {
                return false;
            } else {
                return true;
            }
        },
        // input class 초기화
        initInput() {
            $("#signup-fieldset input").removeClass("validateInput");
            $("#signup-fieldset input").next("div").find("label").text("");
            $("#signup-fieldset input").next("div").hide();
        },
        // input 값 비우기
        resetInput() {
            this.userId = '';
            this.password = '';
            this.chkPassword = '';
            this.nickName = '';
        },
        // 로그인 시도
        loginAnnual() {
            var data = this;
            var validateCnt = 0;
            $("#login-fieldset input").each(function() {
                if(data.validateCheck(this) == false) {
                    validateCnt++
                }
            });

            if(validateCnt == 0) {
                // 회원가입 시도
                var url = "./account/login_join.php";
                var info = {
                    mode: "login",
                    loginId: data.loginId,
                    loginPassword: data.loginPassword
                }
                axios.post(url, info)
                .then(function(response) {
                    var response = response["data"];
                    if(response["proceed"] == true) {
                        sessionStorage.setItem("isLogin", true);
                        sessionStorage.setItem("uno", response["uno"]);
                        sessionStorage.setItem("nickName", response["nickName"]);
                        location.reload();
                    } else {
                        alert(response["msg"]);
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            }
        }
    }
})
</script>
<div id="app">
    <div class="panel shadow1">
        <form class="login-form" style="text-align:center">
            <div class="panel-switch animated fadeIn">
                <button type="button" id="sign_up" class="active-button">Sign Up</button>
                <button type="button" id="log_in" class="" disabled>Log in</button>
            </div>
            <h1 class="animated fadeInUp animate1" id="title-login">Sinxny's Annual Analysis</h1>
            <!-- <h1 class="animated fadeInUp animate1 hidden" id="title-signup">Sinxny's Annual Analysis</h1> -->
            <fieldset id="login-fieldset" class="fieldValidate">
                <input class="login animated fadeInUp animate2" name="username" type="textbox" required placeholder="ID" v-model="loginId" />
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
                <input class="login animated fadeInUp animate3" name="password" type="password" required placeholder="Password" v-model="loginPassword" @keyup.enter="loginAnnual"/>
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
            </fieldset>
            <fieldset id="signup-fieldset" class="hidden fieldValidate">
                <input class="login animated fadeInUp animate1" id="userId" type="text" placeholder="ID" v-model="userId" />
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
                <input class="login animated fadeInUp animate1" id="password" type="password" placeholder="Password" v-model="password" />
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
                <input class="login animated fadeInUp animate1" id="chkPassword" type="password" placeholder="Password Check" v-model="chkPassword" />
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
                <input class="login animated fadeInUp animate1" id="nickName" type="text" placeholder="NickName" v-model="nickName" />
                <div style="display:none">
                    <label style="font-size:small;color:red;"></label>
                </div>
            </fieldset>
            <input type="button" id="login-form-submit" class="login_form button animated fadeInUp animate4" value="Log in" @click="loginAnnual"/>
            <input type="button" id="signup-form-submit" class="login_form button animated fadeInUp animate2 hidden" value="Sign up" @click="signUpAnnaul" />
            <!-- <p><a id="lost-password-link" href="" class="animated fadeIn animate5">I forgot my login or password (!)</a></p> -->
        </form>
    </div>
</div>