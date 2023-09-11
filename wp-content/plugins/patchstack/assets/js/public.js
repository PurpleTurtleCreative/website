function setCookieForNotice(e) {
    var t = new Date, i = t.getTime();
    "1week" == e && (i += 6048e5), "1month" == e && (i += 2629743e3), "1year" == e && (i += 31556916e3), t.setTime(i), document.cookie = "after_exit" == e ? "cookie_notice_seen=1; path=/" : "cookie_notice_seen=1; expires=" + t.toUTCString() + "; path=/", document.getElementById("patchstack-cookie-notice").style.cssText = "visibility:hidden !important"
}

(function(window, document, undefined){

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    window.onload = init;

    function init(){
        if(getCookie('cookie_notice_seen') !== '1' && document.getElementById('patchstack-cookie-notice') !== null){
            document.getElementById('patchstack-cookie-notice').style.visibility = 'visible';
        }
    }

})(window, document, undefined);