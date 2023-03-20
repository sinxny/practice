<script>
    new Vue({
        el: "#app",
        data: {
            uno: sessionStorage.getItem("uno"),
            isData: true
        },
        created() {
            this.getReasonRank();
        },
        methods: {
            // 랭크 가져오기
            getReasonRank() {
                var data = this;
                var url = "./analysis/chart.php";
                var info = {
                    uno: this.uno
                }
                axios.post(url, info)
                .then(function(response) {
                    if(response["data"]["reasonList"].length > 0) {
                        data.isData = true;
                    } else {
                        data.isData = false;
                    }
                    var xValues = response["data"]["reasonList"];
                    var yValues = response["data"]["reasonSumList"];

                    // 랜덤 색 지정
                    var rowCnt = response["data"]["rowCnt"];
                    var colorArray = [];
                    for(var i=1; i <= rowCnt; i++) {
                        var colorCode = "#" + Math.round(Math.random() * 0xffffff).toString(16);
                        colorArray.push(colorCode);
                    }

                    new Chart("myChart", {
                        type: "pie",
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: colorArray,
                                data: yValues
                            }]
                        },
                        options: {
                            // title: {
                            //   display: true,
                            //   text: "World Wide Wine Production 2018"
                            // }
                        }
                    });

                    var reasonAllData = response["data"]["reasonAllData"];
                    console.log(reasonAllData);
                    var chartCnt = 5;
                    if(reasonAllData.length < 5) {
                        chartCnt = reasonAllData.length;
                    }
                    var span = '';
                    for (var i=1; i <= chartCnt; i++) {
                        span += '<span class="col">';
                        span += '<span><b>' + reasonAllData[i-1]["rank"] + '위' + '</b></span><br/>';
                        span += reasonAllData[i-1]["reasonText"] + '<br/>';
                        span += '(' + reasonAllData[i-1]["ratio"] + ', ' + reasonAllData[i-1]["timeSum"] + '시간)';
                        span += '</span>';
                    }
                    $("#textChart").html(span);

                })
                .finally(function() {

                })
                .catch(function(error){
                    console.log(error);
                });
            }
        }
    })
</script>
<div id="app">
    <div class="d-flex" v-if="isData">
        <canvas id="myChart"></canvas>
    </div>
    <div class="row border m-2" id="textChart" v-show="isData"></div>
    <div class="alert alert-info text-center" v-show="!isData">
        <strong>데이터가 없습니다.</strong>
    </div>
</div>