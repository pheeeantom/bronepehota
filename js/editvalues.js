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
function createBackupCookies() {
    if (getCookie("object-machines") && Object.keys(JSON.parse(getCookie("object-machines"))).length) {
        document.cookie = "old-object-machines = " + getCookie("object-machines") + "; max-age=604800";
        document.cookie = "old-polaris-machines = " + getCookie("polaris-machines") + "; max-age=604800";
        document.cookie = "old-protectorat-machines = " + getCookie("protectorat-machines") + "; max-age=604800";
    }
}
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}
if (getCookie("isFirstTimeEdit") == 1) {
    createBackupCookies();
    if (localStorage.getItem("logs")) {
        localStorage.setItem("logs", localStorage.getItem("logs") + "<div class=\"turn\"><p>Прямое редактирование брони и боезапаса</p></div>");
    }
    else {
        localStorage.setItem("logs", "<div class=\"turn\"><p>Прямое редактирование брони и боезапаса</p></div>");
    }
    document.cookie = "isFirstTimeEdit = 0" + "; max-age=604800";
}
document.getElementById('menu').addEventListener("click", backToMenu);
var strength = document.getElementsByClassName('strength');
for (var i = 0; i < strength.length; i++) {
    strength[i].children[0].addEventListener("click", plusStrength);
}
for (var i = 0; i < strength.length; i++) {
    strength[i].children[1].addEventListener("click", minusStrength);
}
var ammunition = document.getElementsByClassName('ammunition');
for (var i = 0; i < ammunition.length; i++) {
    ammunition[i].children[0].addEventListener("click", plusAmmunition);
}
for (var i = 0; i < ammunition.length; i++) {
    ammunition[i].children[1].addEventListener("click", minusAmmunition);
}
function plusStrength() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("method=plusstrength&target=" + this.parentNode.parentNode.id);
    xhr.onload = function() {
        xhrSend("method=editvalues");
    }
}
function minusStrength() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("method=minusstrength&target=" + this.parentNode.parentNode.id);
    xhr.onload = function() {
        if (xhr.response == "yes") {
            swal({
                text: "Пилот убит!",
            })
            .then(function() {
                xhrSend("method=editvalues");
            });
        }
    }
}
function plusAmmunition() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("method=plusammunition&target=" + this.parentNode.parentNode.id);
    xhr.onload = function() {
        xhrSend("method=editvalues");
    }
}
function minusAmmunition() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send("method=minusammunition&target=" + this.parentNode.parentNode.id);
    xhr.onload = function() {
        xhrSend("method=editvalues");
    }
}
function backToMenu() {
    xhrSend("method=menu");
    //var strength = document.getElementsByClassName('strength');
    //var ammunition = document.getElementsByClassName('ammunition');
    //var req = "updatevalues:";
    //for (var i = 0; i < inputs.length; i++) {
    //    if (i != inputs.length - 1) {
    //        req += inputs[i].parentNode.id + "=" + inputs[i].value + ",";
    //    }
    //    else {
    //        req += inputs[i].parentNode.id + "=" + inputs[i].value + ";";
    //    }
    //}
    //var xhr = new XMLHttpRequest();
    //xhr.open('POST', '/', true);
    //xhr.send(req);
}