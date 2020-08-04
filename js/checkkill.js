function xhrSend (s) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(s);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            document.open();
            document.write(xhr.responseText);
            document.close();
        }
    }
}
document.getElementById('ok').addEventListener("click", okButtonListener);
document.getElementsByTagName('body')[0].addEventListener("keypress", menuKeyListener);
function okButtonListener() {
    xhrSend("method=testshot:chooseattacker");
    localStorage.setItem("logs", localStorage.getItem("logs") + "</div>");
}
function menuKeyListener(e) {
    if (e.keyCode == 13) {
        xhrSend("method=testshot:chooseattacker");
        localStorage.setItem("logs", localStorage.getItem("logs") + "</div>");
    }
}