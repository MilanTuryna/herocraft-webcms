function writeActualTime(callback) {
    function getTime() {
        let x = new Date();
        let hour= x.getHours();
        let minute= x.getMinutes();
        let second= x.getSeconds();
        if(hour <10 ) hour='0'+hour;
        if(minute <10 ) minute='0' + minute;
        if(second<10) second='0' + second;

        return hour+':'+minute+':'+second;
    }

    callback(getTime());
    setTimeout(function() {
        writeActualTime(callback)
    },1000)
}

