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
var delayedMachine = "";
var tds = document.getElementsByClassName('unit');
for (var i = 0; i < tds.length; i++) {
    tds[i].addEventListener("click", tdClickListener);
}
var tdsm = document.getElementsByClassName('machine');
for (var i = 0; i < tdsm.length; i++) {
    tdsm[i].addEventListener("click", tdClickListener);
}
var tdsb = document.getElementsByClassName('blowup');
for (var i = 0; i < tdsb.length; i++) {
    tdsb[i].addEventListener("click", tdClickListener);
}
document.getElementsByTagName("button")[0].addEventListener("click", buttonClickListener);
document.getElementsByTagName("button")[1].addEventListener("click", menuClickListener);
document.getElementById("icon").addEventListener("click", changeSide);
//document.getElementById("combat").children[0].addEventListener("click", changeCombat);
var req = "";
var req2 = "";
var id = "";
var id2 = "";
var ammoreq = "";
var closecombat;
var closecombat2;
var sideIcon;
var block = false;
if (getCookie("side")) {
    sideIcon = getCookie("side");
    if (getCookie("side") == "polaris") {
        document.getElementById("icon").src = "/img/polaris1.jpg";
    }
    else {
        document.getElementById("icon").src = "/img/protectorat1.jpeg";
    }
}
else {
    sideIcon = "polaris";
    document.getElementById("icon").src = "/img/polaris1.jpg";
}
setTimeout(() => { 
    for (var i = 0; i < tdsm.length; i++) {
        var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
        tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
        tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
    }
}, 100);
if (getCookie("is-close") == 1) {
    document.getElementById("combat").children[0].innerHTML = "Ближний бой";
    if (getCookie("is-machines") == 0) {
        document.getElementById("combat").innerHTML += "-<span>Пехота</span>";
    }
    else if (getCookie("is-machines") == 1) {
        document.getElementById("combat").innerHTML += "-<span>Техника</span>";
    }
    else {
        document.getElementById("combat").innerHTML += "-<span>Пехота</span>";
    }
    document.getElementById("combat").children[1].addEventListener("click", changeTypeArmy);
    //document.cookie = "is-close = 1";
    var tds1 = document.getElementsByClassName('blowup');
    for (var i = 0; i < tds1.length; i++) {
        tds1[i].style = "display:none";
    }
    if (getCookie("is-machines") == 0) {
        var tds2 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:none";
        }
    }
    else if (getCookie("is-machines") == 1) {
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:none";
        }
        setTimeout(() => {
            var tdsm = document.getElementsByClassName('machine');
            for (var i = 0; i < tdsm.length; i++) {
                var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
                tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
                tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
            }
        }, 150);
    }
    else {
        var tds2 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:none";
        }
    }
    var hrs = document.getElementsByTagName("hr");
    for (var i = 0; i < hrs.length; i++) {
        hrs[i].style = "display:none";
    }
}
document.getElementById("combat").children[0].addEventListener("click", changeCombat);
var attackobj = "";
var blu = false;
var spoilerVisible = false;
function changeSideMachine() {
    block = true;
    var arrPolaris = JSON.parse(getCookie('polaris-machines'));
    var arrProtectorat = JSON.parse(getCookie('protectorat-machines'));
    if (this.parentNode.parentNode.parentNode.parentNode.parentNode.id == "polaris-machines") {
        for (var i = 0; i < arrPolaris.length; i++) {
            if (Number(this.parentNode.id.slice(3, this.parentNode.id.indexOf("-"))) == arrPolaris[i]) {
                arrProtectorat.push(arrPolaris[i]);
                arrPolaris.splice(i, 1);
            }
        }
    }
    else if (this.parentNode.parentNode.parentNode.parentNode.parentNode.id == "protectorat-machines") {
        for (var i = 0; i < arrProtectorat.length; i++) {
            if (Number(this.parentNode.id.slice(3, this.parentNode.id.indexOf("-"))) == arrProtectorat[i]) {
                arrPolaris.push(arrProtectorat[i]);
                arrProtectorat.splice(i, 1);
            }
        }
    }
    document.cookie = "polaris-machines = " + JSON.stringify(arrPolaris) + "; max-age=604800";
    //document.cookie = "size-polaris-machines = " + arrPolaris.length;
    document.cookie = "protectorat-machines = " + JSON.stringify(arrProtectorat) + "; max-age=604800";
    //document.cookie = "size-protectorat-machines = " + arrProtectorat.length;
    xhrSend("method=testshot:chooseattacker");
}
function clearAll(th) {
    var side = th.parentNode.parentNode.parentNode;
    var sideTrs = side.children[0].children;
    for (var j = 0; j < sideTrs.length; j++) {
        var sideTds = sideTrs[j].children;
        for (var i = 0; i < sideTds.length; i++) {
            sideTds[i].style = "";
        }
    }
    if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
        if (document.getElementById("polaris-machines")) {//если есть вообще машины на странице
            if (side.parentNode.id.includes("machines")) {
                //обнуляем пехоту
                var sideTrs = document.getElementById(side.parentNode.id.slice(0, side.parentNode.id.length - 9)).children[0].children[0].children;
                for (var j = 0; j < sideTrs.length; j++) {
                    var sideTds = sideTrs[j].children;
                    for (var i = 0; i < sideTds.length; i++) {
                        sideTds[i].style = "";
                    }
                }
            }
            else {
                if (document.getElementById(side.parentNode.id + "-machines").innerHTML != "") {
                    //обнуляем технику
                    var sideTrs = document.getElementById(side.parentNode.id + "-machines").children[0].children[0].children;
                    for (var j = 0; j < sideTrs.length; j++) {
                        var sideTds = sideTrs[j].children;
                        for (var i = 0; i < sideTds.length; i++) {
                            sideTds[i].style = "";
                        }
                    }
                }
            }
        }
    }
}
function computeChance() {
    if (req && req2 && !spoilerVisible) {
        if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
            var num = document.getElementById(id).dataset.num;
            var side = document.getElementById(id).dataset.side;
            var armor = document.getElementById(id2).dataset.armor;
            var singleChance = (side - armor) / side;
            if (singleChance > 1) {
                singleChance = 1;
            }
            else if (singleChance < 0) {
                singleChance = 0;
            }
            var chance = (1 - Math.pow(1 - singleChance, num)) * 100;
            document.getElementById("chance").innerHTML = "Вероятность пробития: " + chance + "%";
        }
        else {
            var dif;
            if (document.getElementById("combat").children[1].innerHTML == "Пехота") {
                var closecombat = document.getElementById(id).dataset.cc;
                var armor = document.getElementById(id2).dataset.armor;
                dif = parseInt(closecombat) - parseInt(armor);
            }
            else {
                dif = parseInt(document.getElementById(id).dataset.armor) + parseInt(closecombat) - parseInt(document.getElementById(id2).dataset.armor) - parseInt(closecombat2);
            }
            var chance = 0;
            for (var i = 0; i < 6; i++) {
                var temp = (dif + i)/6;
                if (temp > 1) {
                    temp = 1;
                }
                else if (temp < 0) {
                    temp = 0;
                }
                chance += temp;
            }
            chance /= 6;
            chance *= 100;
            document.getElementById("chance").innerHTML = "Вероятность пробития: " + chance + "%";
        }
    }
}
function changeCombat() {
    if (this.innerHTML == "Дальний бой") {
        this.innerHTML = "Ближний бой";
        if (getCookie("is-machines") == 0) {
            this.parentNode.innerHTML += "-<span>Пехота</span>";
        }
        else if (getCookie("is-machines") == 1) {
            this.parentNode.innerHTML += "-<span>Техника</span>";
        }
        else {
            this.parentNode.innerHTML += "-<span>Пехота</span>";
        }
        document.getElementById("combat").children[0].addEventListener("click", changeCombat);
        document.getElementById("combat").children[1].addEventListener("click", changeTypeArmy);
        document.cookie = "is-close = 1" + "; max-age=604800";
        var tds1 = document.getElementsByClassName('blowup');
        for (var i = 0; i < tds1.length; i++) {
            tds1[i].style = "display:none";
        }
        if (getCookie("is-machines") == 0) {
            var tds2 = document.getElementsByClassName('machine');
            for (var i = 0; i < tds2.length; i++) {
                tds2[i].style = "display:none";
            }
        }
        else if (getCookie("is-machines") == 1) {
            var tds2 = document.getElementsByClassName('unit');
            for (var i = 0; i < tds2.length; i++) {
                tds2[i].style = "display:none";
            }
            setTimeout(() => {
                var tdsm = document.getElementsByClassName('machine');
                for (var i = 0; i < tdsm.length; i++) {
                    var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
                    tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
                    tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
                }
            }, 100);
        }
        else {
            var tds2 = document.getElementsByClassName('machine');
            for (var i = 0; i < tds2.length; i++) {
                tds2[i].style = "display:none";
            }
            setTimeout(() => {
                var tdsm = document.getElementsByClassName('machine');
                for (var i = 0; i < tdsm.length; i++) {
                    var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
                    tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
                    tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
                }
            }, 100);
        }
        var hrs = document.getElementsByTagName("hr");
        for (var i = 0; i < hrs.length; i++) {
            hrs[i].style = "display:none";
        }
    }
    else {
        this.parentNode.innerHTML = "<span>Дальний бой</span>";
        document.getElementById("combat").children[0].addEventListener("click", changeCombat);
        document.cookie = "is-close = 0" + "; max-age=604800";
        var tds1 = document.getElementsByClassName('blowup');
        for (var i = 0; i < tds1.length; i++) {
            tds1[i].style = "display:table-cell";
        }
        var tds2 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:table-cell";
        }
        var tds3 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds3.length; i++) {
            tds3[i].style = "display:table-cell";
        }
        var hrs = document.getElementsByTagName("hr");
        for (var i = 0; i < hrs.length; i++) {
            hrs[i].style = "display:block";
        }
        var tdsm = document.getElementsByClassName('machine');
        for (var i = 0; i < tdsm.length; i++) {
            var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
            tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
            tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
        }
    }
    blu = false;
    /*var tds1 = document.getElementsByClassName('machine');
    for (var i = 0; i < tds1.length; i++) {
        tds1[i].style = "";
    }*/
    if (getCookie("is-machines") == 1) {
        var tds2 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "";
        }
    }
    else if (getCookie("is-machines") == 0) {
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "";
        }
    }
    else {
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "";
        }
    }
    req = "";
    id = "";
    req2 = "";
    id2 = "";
    document.getElementById("chance").innerHTML = "Вероятность пробития: ?";
}
function changeTypeArmy() {
    if (this.innerHTML == "Пехота") {
        this.innerHTML = "Техника";
        document.cookie = "is-machines = 1" + "; max-age=604800";
        var tds1 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds1.length; i++) {
            tds1[i].style = "display:table-cell";
        }
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:none";
        }
        var tdsm = document.getElementsByClassName('machine');
        for (var i = 0; i < tdsm.length; i++) {
            var posTop = tdsm[i].getBoundingClientRect().top + window.pageYOffset;
            tdsm[i].getElementsByTagName('img')[1].style = "display: block; position: absolute; left: " + tdsm[i].getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999;";
            tdsm[i].getElementsByTagName('img')[1].addEventListener("click", changeSideMachine);
        }
    }
    else {
        this.innerHTML = "Пехота";
        document.cookie = "is-machines = 0" + "; max-age=604800";
        var tds1 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds1.length; i++) {
            tds1[i].style = "display:none";
        }
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "display:table-cell";
        }
    }
    req = "";
    id = "";
    req2 = "";
    id2 = "";
    document.getElementById("chance").innerHTML = "Вероятность пробития: ?";
}
function tdClickListener() {
    if (!block) {
        var allspoilers = document.getElementsByClassName("spoiler");
        for (var i = 0; i < allspoilers.length; i++) {
            allspoilers[i].style = "display: none;";
        }
        var allspoilerslrw = document.getElementsByClassName("spoiler-lrw");
        for (var i = 0; i < allspoilerslrw.length; i++) {
            allspoilerslrw[i].style = "display: none;";
        }
        spoilerVisible = false;
        if (this.id.includes("inf")) {
            if (!blu) {
                clearAll(this);
            }
            else {
                var tds1 = document.getElementsByClassName('machine');
                for (var i = 0; i < tds1.length; i++) {
                    tds1[i].style = "";
                }
                var tds2 = document.getElementsByClassName('unit');
                for (var i = 0; i < tds2.length; i++) {
                    tds2[i].style = "";
                }
            }
            var posTop = this.getBoundingClientRect().top + window.pageYOffset;
            var spoiler = document.getElementById("spoiler" + this.id.slice(3));
            spoiler.style = "display: block; position: absolute; left: " + this.getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999; background-color: white; border-radius: 5px; z-index: 1000;";
            spoilerVisible = true;
            var infsRows = spoiler.children[0].children[0].children;
            for (var i = 0; i < infsRows.length; i++) {
                var infs = infsRows[i].children;
                for (var j = 0; j < infs.length; j++) {
                    infs[j].addEventListener("click", infsListener);
                }
            }
        }
        else if (this.id.includes("obj")) {
            if (!blu) {
                clearAll(this);
                if (this.parentNode.parentNode.parentNode.parentNode.id.includes(sideIcon)) {
                    if (this.id.slice(0, this.id.indexOf("-")) == id2.slice(0, id2.indexOf("-"))) {
                        alert("Машина не может стрелять в саму себя!");//это уже не нужно
                    }
                    else {
                        attackobj = this.id;
                        if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
                            ammoreq = "method=machinesammunition&target=" + this.id.slice(0, this.id.indexOf("-"));
                            var posTop = this.getBoundingClientRect().top + window.pageYOffset;
                            var spoiler = document.getElementById("spoiler-lrw" + this.className.slice(3,this.className.indexOf(' ')));
                            spoiler.style = "display: block; position: absolute; left: " + this.getBoundingClientRect().left + "px; top: " + posTop + "px; z-index:999; background-color: white; border-radius: 5px;";
                            spoilerVisible = true;
                            var lrwRows = spoiler.children[0].children[0].children;
                            for (var i = 0; i < lrwRows.length; i++) {
                                var lrw = lrwRows[i].children;
                                for (var j = 0; j < lrw.length; j++) {
                                    lrw[j].addEventListener("click", lrwListener);
                                }
                            }
                        }
                        else {
                            closecombat = prompt("ББ:");
                            if (Number.isInteger(parseInt(closecombat)) && /^[0-9]+$/.test(closecombat)) {
                                if (closecombat >= 0) {
                                    id = this.id;
                                    req = "method=setttacker&attacker=" + id.slice(0, id.indexOf("-"));
                                    this.style = "border: 6px solid orange; border-radius: 5px;";
                                }
                                else {
                                    alert("Нужно ввести неотрицательное число");
                                }
                            }
                            else {
                                alert("Нужно ввести число");
                            }
                            computeChance();
                        }
                    }
                }
                else {
                    if (ammoreq.includes(this.id.slice(0, this.id.indexOf("-")))) {
                        alert("Машина не может стрелять в саму себя!");//это уже не нужно
                    }
                    else {
                        if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
                            id2 = this.id;
                            req2 = "method=settarget&target=" + id2.slice(0, id2.indexOf("-"));
                            this.style = "border: 6px solid orange; border-radius: 5px;";
                        }
                        else {
                            closecombat2 = prompt("ББ:");
                            if (Number.isInteger(parseInt(closecombat2)) && /^[0-9]+$/.test(closecombat2)) {
                                if (closecombat2 >= 0) {
                                    id2 = this.id;
                                    req2 = "method=settarget&target=" + id2.slice(0, id2.indexOf("-"));
                                    this.style = "border: 6px solid orange; border-radius: 5px;";
                                }
                                else {
                                    alert("Нужно ввести неотрицательное число");
                                }
                            }
                            else {
                                alert("Нужно ввести число");
                            }
                        }
                        computeChance();
                    }
                }
            }
            else {
                var tds1 = document.getElementsByClassName('machine');
                for (var i = 0; i < tds1.length; i++) {
                    tds1[i].style = "";
                }
                var tds2 = document.getElementsByClassName('unit');
                for (var i = 0; i < tds2.length; i++) {
                    tds2[i].style = "";
                }
                id2 = this.id;
                req2 = "method=settarget&target=" + id2.slice(0, id2.indexOf("-"));
                this.style = "border: 6px solid orange; border-radius: 5px;";
                computeChance();
            }
            
        }
        else if (this.id.includes("blu")) {
            ammoreq = "";
            if (blu) {
                if (id == this.id) {
                    blu = false;
                    var tds1 = document.getElementsByClassName('machine');
                    for (var i = 0; i < tds1.length; i++) {
                        tds1[i].style = "";
                    }
                    var tds2 = document.getElementsByClassName('unit');
                    for (var i = 0; i < tds2.length; i++) {
                        tds2[i].style = "";
                    }
                    this.style = "";
                    req = "";
                    req2 = "";
                    id = "";
                    id2 = "";
                    document.getElementById("chance").innerHTML = "Вероятность пробития: ?";
                }
                else {
                    blu = true;
                    var blus = document.getElementsByClassName('blowup');
                    for (var i = 0; i < blus.length; i++) {
                        blus[i].style = "";
                    }
                    req = "method=setttacker&attacker=" + this.id;
                    id = this.id;
                    this.style = "border: 3px solid orange; border-radius: 5px;";
                    computeChance();
                }
            }
            else {
                blu = true;
                var tds1 = document.getElementsByClassName('machine');
                for (var i = 0; i < tds1.length; i++) {
                    tds1[i].style = "";
                }
                var tds2 = document.getElementsByClassName('unit');
                for (var i = 0; i < tds2.length; i++) {
                    tds2[i].style = "";
                }
                req = "method=setttacker&attacker=" + this.id;
                id = this.id;
                req2 = "";
                id2 = "";
                this.style = "border: 3px solid orange; border-radius: 5px;";
                document.getElementById("chance").innerHTML = "Вероятность пробития: ?";
            }
        }
    }
}
function infsListener() {
    var melee = false;
    if (!blu) {
        if (document.getElementById("inf" + this.parentNode.parentNode.parentNode.parentNode.id.slice(7)).parentNode.parentNode.parentNode.parentNode.id == sideIcon) {
            if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
                if (this.className.includes("melee")) {
                    alert("Нельзя выбрать ближника атакующим!");
                    melee = true;
                }
                else {
                    ammoreq = "";
                    req = "method=setttacker&attacker=inf" + this.id.slice(4);
                    id = this.id;
                }
            }
            else {
                req = "method=setttacker&attacker=inf" + this.id.slice(4);
                id = this.id;
            }
        }
        else {
            req2 = "method=settarget&target=inf" + this.id.slice(4);
            id2 = this.id;
        }
    }
    else {
        req2 = "method=settarget&target=inf" + this.id.slice(4);
        id2 = this.id;
    }
    var sp = this.parentNode.parentNode.parentNode.parentNode;
    sp.style = "display: none;";
    spoilerVisible = false;
    if (!melee || document.getElementById("combat").children[0].innerHTML == "Ближний бой") {
        var rootInf = document.getElementById("inf" + sp.id.slice(7));
        var infs2 = this.cloneNode(true);
        infs2.id = "inf" + sp.id.slice(7);
        infs2.className = "unit";
        rootInf.parentNode.replaceChild(infs2, rootInf);
        infs2.style = "border: 3px solid orange; border-radius: 5px;";
        infs2.addEventListener("click", tdClickListener);
    }
    this.removeEventListener("click", infsListener);
    computeChance();
}
function lrwListener() {
    id = this.id;
    req = "method=setttacker&attacker=" + id;
    var sp = this.parentNode.parentNode.parentNode.parentNode;
    sp.style = "display: none;";
    spoilerVisible = false;
    clearAll(document.getElementById(attackobj));
    document.getElementById(attackobj).style = "border: 6px solid orange; border-radius: 5px;";
    this.removeEventListener("click", lrwListener);
    computeChance();
};
function getCompareDistance() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.onload = function() {
        var xhr2 = new XMLHttpRequest();
        xhr2.open('POST', '/', true);
        xhr2.onload = function() {
            var xhr3 = new XMLHttpRequest();
            xhr3.open('POST', '/', true);
            //xhr.responseType = 'Text';
            xhr3.onload = function() {
                var div = document.createElement("div");
                div.id = "comparedistance";
                div.innerHTML = xhr3.response;
                document.body.appendChild(div);
                document.getElementById("send").removeChild(document.getElementById("send").children[0]);
                document.getElementById("send").parentNode.removeChild(document.getElementById("send"));
                document.getElementById("menu").removeChild(document.getElementById("menu").children[0]);
                document.getElementById("menu").parentNode.removeChild(document.getElementById("menu"));
                tds = document.getElementsByClassName('unit');
                for (var i = 0; i < tds.length; i++) {
                    tds[i].removeEventListener("click", tdClickListener);
                }
                tdsm = document.getElementsByClassName('machine');
                for (var i = 0; i < tdsm.length; i++) {
                    tdsm[i].removeEventListener("click", tdClickListener);
                }
                tdsb = document.getElementsByClassName('blowup');
                for (var i = 0; i < tdsb.length; i++) {
                    tdsb[i].removeEventListener("click", tdClickListener);
                }
                document.getElementById("icon").removeEventListener("click", changeSide);
                var s = document.createElement("script");
                s.src = "/js/comparedistance.js";
                document.head.appendChild(s);
            }
            xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr3.send("method=testshot:comparedistance");
        }
        xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr2.send(req2);
    }
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(req);
}
function getCheckKill() {
    getRes(true);
}
function createBackupCookies() {
    if (getCookie("object-machines") && Object.keys(JSON.parse(getCookie("object-machines"))).length) {
        document.cookie = "old-object-machines = " + getCookie("object-machines") + "; max-age=604800";
        //document.cookie = "old-size-object-machines = " + getCookie("size-object-machines");
        document.cookie = "old-polaris-machines = " + getCookie("polaris-machines") + "; max-age=604800";
        //document.cookie = "old-size-polaris-machines = " + getCookie("size-polaris-machines");
        document.cookie = "old-protectorat-machines = " + getCookie("protectorat-machines") + "; max-age=604800";
        //document.cookie = "old-size-protectorat-machines = " + getCookie("size-protectorat-machines");
    }
}
function buttonClickListener() {
    if (req && req2 && !spoilerVisible) {
        if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
            //alert(ammoreq);
            //alert(req);
            //alert(req2);
            var flag = null;
            if (ammoreq != "") {
                var xhr0 = new XMLHttpRequest();
                xhr0.open('POST', '/', true);
                xhr0.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr0.send(ammoreq);
                xhr0.onreadystatechange = function() {
                    if (xhr0.readyState == XMLHttpRequest.DONE) {
                        if (xhr0.responseText.split("//logs//")[0] == "error") {
                            alert("Не хватает боезапаса!");
                            //flag = false;
                            //xhrSend("menu");
                        }
                        else if (xhr0.responseText.split("//logs//")[0] == "ok") {
                            //flag = true;
                            delayedMachine = xhr0.responseText.split("//logs//")[1];
                            createBackupCookies();
                            getCompareDistance();
                        }
                    }
                }
            }
            else if (req.includes("blu")) {
                createBackupCookies();
                getCheckKill();
            }
            else {
                //flag = true;
                createBackupCookies();
                getCompareDistance();
            }
            document.cookie = "side = " + sideIcon + "; max-age=604800";
            //hile (flag == null) { sleep(100); }
            //if (flag) {
            //}
            /*if (id.includes("lrw")) {
                xhrSend("testshot:ammunition;");
            }
            else if (id.includes("inf") || id.includes("blu")) {
                xhrSend("testshot:choosetarget;");
            }*/
        }
        else {
            createBackupCookies();
            getCloseCombatResult();
            document.cookie = "side = " + sideIcon + "; max-age=604800";
        }
    }
    else {
        alert("Не выбраны атакующий или цель или все сразу!");
    }
}
function getRes(flag) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/', true);
    xhr.onload = function() {
        var xhr2 = new XMLHttpRequest();
        xhr2.open('POST', '/', true);
        xhr2.onload = function() {;
            var xhr4 = new XMLHttpRequest();
            xhr4.open('POST', '/', true);
            xhr4.onload = function() {
                if (localStorage.getItem("logs")) {
                    localStorage.setItem("logs", localStorage.getItem("logs") + "<div class=\"turn\">" + xhr4.response);
                }
                else {
                    localStorage.setItem("logs", "<div class=\"turn\">" + xhr4.response);
                }
                var xhr3 = new XMLHttpRequest();
                xhr3.open('POST', '/', true);
                //xhr.responseType = 'Text';
                xhr3.onload = function() {
                    var div = document.createElement("div");
                    div.innerHTML = xhr3.response.split("//logs//")[0];
                    document.body.appendChild(div);
                    document.getElementById("send").removeChild(document.getElementById("send").children[0]);
                    document.getElementById("send").parentNode.removeChild(document.getElementById("send"));
                    document.getElementById("menu").removeChild(document.getElementById("menu").children[0]);
                    document.getElementById("menu").parentNode.removeChild(document.getElementById("menu"));
                    tds = document.getElementsByClassName('unit');
                    for (var i = 0; i < tds.length; i++) {
                        tds[i].removeEventListener("click", tdClickListener);
                    }
                    tdsm = document.getElementsByClassName('machine');
                    for (var i = 0; i < tdsm.length; i++) {
                        tdsm[i].removeEventListener("click", tdClickListener);
                    }
                    tdsb = document.getElementsByClassName('blowup');
                    for (var i = 0; i < tdsb.length; i++) {
                        tdsb[i].removeEventListener("click", tdClickListener);
                    }
                    document.getElementById("icon").removeEventListener("click", changeSide);
                    var s = document.createElement("script");
                    s.src = "/js/checkkill.js";
                    document.head.appendChild(s);
                    if (localStorage.getItem("logs")) {
                        localStorage.setItem("logs", localStorage.getItem("logs") + xhr3.response.split("//logs//")[1] + "</div>");
                    }
                    else {
                        localStorage.setItem("logs", xhr3.response.split("//logs//")[1] + "</div>");
                    }
                }
                xhr3.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                if (!flag) {
                    xhr3.send("method=closecombat&closecombat=" + closecombat + "&closecombat2=" + closecombat2);
                }
                else {
                    xhr3.send("method=testshot:checkkill");
                }
            }
            xhr4.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr4.send("method=logs:getattackerdefender");
        }
        xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr2.send(req2);
    }
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(req);
}
function getCloseCombatResult() {
    getRes(false);
}
function menuClickListener() {
    xhrSend("method=menu");
}
function changeSide() {
    if (document.getElementById("combat").children[0].innerHTML == "Дальний бой") {
        var tds1 = document.getElementsByClassName('machine');
        for (var i = 0; i < tds1.length; i++) {
            tds1[i].style = "";
        }
        var tds2 = document.getElementsByClassName('unit');
        for (var i = 0; i < tds2.length; i++) {
            tds2[i].style = "";
        }
        var blus = document.getElementsByClassName('blowup');
        for (var i = 0; i < blus.length; i++) {
            blus[i].style = "";
        }
    }
    else {
        if (document.getElementById("combat").children[1].innerHTML == "Пехота") {
            var tds2 = document.getElementsByClassName('unit');
            for (var i = 0; i < tds2.length; i++) {
                tds2[i].style = "";
            }
        }
        else {
            var tds1 = document.getElementsByClassName('machine');
            for (var i = 0; i < tds1.length; i++) {
                tds1[i].style = "";
            }
        }
    }
    blu = false;
    req = "";
    req2 = "";
    id = "";
    id2 = "";
    ammoreq = "";

    if (this.getAttribute('src') == "/img/polaris1.jpg") {
        this.src = "/img/protectorat1.jpeg";
        sideIcon = "protectorat";
    }
    else {
        this.src = "/img/polaris1.jpg";
        sideIcon = "polaris";
    }
    document.getElementById("chance").innerHTML = "Вероятность пробития: ?";
}