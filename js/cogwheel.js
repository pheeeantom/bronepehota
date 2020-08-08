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
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}
document.getElementById("showChance").addEventListener("click", showChance);
document.getElementById("cogwheel").parentNode.addEventListener("mouseover", showSettings);
if (getCookie("show-chance") == 1) {
	document.getElementById("showChance").checked = 1;
}
else if (getCookie("show-chance") == 0) {
	document.getElementById("showChance").checked = 0;
}
else {
	document.getElementById("showChance").checked = 1;
}
function showSettings() {
	document.getElementById("listSettings").style = "display:block";
	document.getElementById("listSettings").addEventListener("mouseout", notShowSettings);
}
function notShowSettings() {
	document.getElementById("listSettings").style = "display:none";
	document.getElementById("listSettings").removeEventListener("mouseout", notShowSettings);
}
function showChance() {
	if (this.checked) {
		document.cookie = "show-chance = 1";
		xhrSend("method=menu");
	}
	else {
		document.cookie = "show-chance = 0";
		xhrSend("method=menu");
	}
}