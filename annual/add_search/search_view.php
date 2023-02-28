<script>
    var vmSearch = new Vue({
        el: "#app",
        data: {
            uno: sessionStorage.getItem("uno"),
            annualList: [],
            mode: 'init'
        },
        created() {
            this.getAnnualData();
        },
        mounted() {
            // thead 틀 고정
            var thAnnualSearch = $('#tblAnnualSearch').find('thead th');
            $('#tblAnnualSearch').closest('div.tableFixHead-modal').on('scroll', function() {
                thAnnualSearch.css('transform', 'translateY('+ this.scrollTop +'px)');
            });
        },
        methods: {
            getAnnualData() {
                var data = this;
                var url = "./add_search/search.php";
                var info = {
                    uno: this.uno,
                    mode: this.mode
                }
                axios.post(url, info)
                .then(function(response) {
                    data.annualList = response["data"]["annualList"];
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            // 삭제
            btnDeleteClick(ano, $event) {
                var data = this;
                this.mode = 'delete';
                var url = "./add_search/search.php";
                var info = {
                    uno: this.uno,
                    mode: this.mode,
                    ano: ano
                }
                axios.post(url, info)
                .then(function(response) {
                    if(response["data"]["proceed"] == true) {
                        modalPageShow("search");
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
    <div class="tableFixHead-modal">
        <table class="table table-sm" id="tblAnnualSearch">
            <thead>
                <tr class="text-center table-info">
                    <th>날짜</th>
                    <th>사용시간</th>
                    <th>사유</th>
                    <th class="md-none">기타사항</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
                <tr :key="annual.ano" v-for="annual in annualList">
                    <td class="text-center">{{ annual.useDate }}</td>
                    <td class="text-center">{{ annual.useTime }}</td>
                    <td class="text-center">{{ annual.reasonText }}</td>
                    <td class="md-none">{{ annual.etc }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" @click="btnDeleteClick(annual.ano, $event)"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>