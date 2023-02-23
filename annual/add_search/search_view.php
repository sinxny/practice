<script>
    var vmSearch = new Vue({
        el: "#app",
        data: {
            uno: sessionStorage.getItem("uno"),
            annualList: []
        },
        created() {
            this.getAnnualData();
        },
        methods: {
            getAnnualData() {
                var data = this;
                var url = "./add_search/search.php";
                var info = {
                    uno: this.uno
                }
                axios.post(url, info)
                .then(function(response) {
                    data.annualList = response["data"]["annualList"];
                })
                .catch(function(error){
                    console.log(error);
                });

                
            }
        }
    })
</script>
<div id="app">
    <div class="tableFixHead-modal">
        <table class="table table-sm">
            <thead>
                <tr class="text-center table-info">
                    <th>날짜</th>
                    <th>사용시간</th>
                    <th>사유</th>
                    <th>기타사항</th>
                </tr>
            </thead>
            <tbody>
                <tr :key="index" v-for="(annual, index) in annualList">
                    <td class="text-center">{{ annual.useDate }}</td>
                    <td class="text-center">{{ annual.useTime }}</td>
                    <td class="text-center">{{ annual.reasonText }}</td>
                    <td class="text-center">{{ annual.etc }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>