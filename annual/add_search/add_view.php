<style>
    @media (max-width:500px) {
        #addForm, #annualReason {
            margin: 0 !important;
        }
    }
</style>
<script>
    var vmSearch = new Vue({
        el: "#app",
        data: {
            mode: 'init',
            uno: sessionStorage.getItem("uno"),
            reasonList: [],
            annualReason: ''
        },
        created() {
            this.initAddPage();

            // 날짜 min/max 값 넣기
            dateMinMaxAppend();
        },
        methods: {
            // 초기화면
            initAddPage() {
                var data = this;
                var url = "./add_search/add.php";
                var info = {
                    mode: this.mode,
                    uno: this.uno,
                    isData: false
                }
                axios.post(url, info)
                .then(function(response) {
                    if(response["data"]["reasonList"].length > 0) {
                        data.isData = true
                        data.reasonList = response["data"]["reasonList"];
                    } else {
                        data.isData = false;
                    }
                    console.log(response["data"]["reasonList"].length);
                })
                .finally(function() {
                    // 첫번째 옵션 값 선택
                    if(data.isData == true) {
                        $("#annualReason").find("option").eq(0).prop("selected", true);
                    } else {
                        data.annualReason = 'direct';
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            }
        }
    })
</script>
<div id="app">
    <div id="addForm" class="p-4 mx-5">
        <div class="row form-group">
            <label for="useDate" class="col-md-3">연차사용 날짜 : </label>
            <div class="col-md">
                <input type="date" class="form-control" id="useDate">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="row form-group">
            <label for="useTime" class="col-md-3">연차사용 시간 : </label>
            <div class="col-md">
                <select class="form-control" id="useTime">
                    <option value="8">8시간(1개)</option>
                    <option value="7">7시간</option>
                    <option value="6">6시간</option>
                    <option value="5">5시간</option>
                    <option value="4">4시간</option>
                    <option value="3">3시간</option>
                    <option value="2">2시간</option>
                    <option value="1">1시간</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="row form-group">
            <label for="annualReason" class="col-md-3">연차 사유 : </label>
            <div class="col-md form-inline">
                <select class="form-control mr-2" id="annualReason" v-model="annualReason">
                    <option v-for="reason in reasonList" :value="reason.rno">{{ reason.reasonText }}</option>
                    <option value="direct">직접입력</option>
                </select>
                <input type="text" class="form-control" id="directReason" v-show="annualReason == 'direct'" maxlength="50"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="row form-group">
            <label for="annualEtc" class="col-md-3">기타사항 : </label>
            <div class="col-md">
                <textarea maxlength="500" class="form-control" id="annualEtc"></textarea>
            </div>
        </div>
    </div>
</div>