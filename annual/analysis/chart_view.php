<script>


    new Vue({
        el: "#app",
        data: {
            uno: sessionStorage.getItem("uno")
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
<div id="app" style="display: flex; justify-content: center;">
    <canvas id="myChart" style="width:100%;"></canvas>
</div>