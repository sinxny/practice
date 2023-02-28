// 날짜 형식이 맞는지
function chkDateFormat(date) {
    if($.trim(date) == '') {
        return ''
    } else {
        var regex = RegExp(/^\d{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/);
        if ( !regex.test(date) ) {
            return false
        } else {
            return true
        }
    }
}

//날짜 mim/max 값 넣기
function dateMinMaxAppend() {
    $("input[type=date]").each(function() {
        //min값
        $(this).attr("min", "2015-01-01");
        //max값
        var today = new Date();
        var toYear = today.getFullYear() + 1;
        var maxDt = toYear + '-12-31';

        $(this).attr("max", maxDt);
    });
}