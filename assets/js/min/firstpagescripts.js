"use strict";function O(o){return"object"==(void 0===o?"undefined":_typeof(o))?o:document.getElementById(o)}function checkAndLogin(){var o=void 0;""!=(o=encodeURIComponent($(".loginInput").val()))?$.ajax({method:"GET",url:"./core/loginControler/loginChecker.php?q="+o+"&sendResult=true",success:function(n){""!=n?$.get("./core/loginControler/login.php?user="+o,function(o){$("body").html(o)}):$("#errorHolder").html("<span class = 'error'>Please check your domain username</span>")}}):$("#errorHolder").html("<span class = 'error'>Please enter your domain username</span>")}var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(o){return typeof o}:function(o){return o&&"function"==typeof Symbol&&o.constructor===Symbol&&o!==Symbol.prototype?"symbol":typeof o},paintCanvas=function(){var o=O("logo").getContext("2d"),n=o.createLinearGradient(0,0,0,89),e=new Image;e.src="./assets/image/ico-leaf.png",window.onload=function(){o.font='bold italic 70px "PT Sans"',o.textBaseline="top",n.addColorStop(0,"white"),n.addColorStop(.33,"rgba(75, 183, 10, 0.8)"),n.addColorStop(.66,"#2ea04d"),o.fillStyle=n,o.fillText("QTT Panel",0,0),o.drawImage(e,310,0)}};$(".loginInput").on("blur",function(){$.get("./core/loginControler/loginChecker.php?q="+encodeURIComponent($(".loginInput").val()),function(o){$("#loginChecker").html(o)})}),$(".loginInput").keypress(function(o){13==o.which&&""!=$(this).val()&&(o.preventDefault(),checkAndLogin())}),$(".submitLogin").on("click",function(){checkAndLogin()}),paintCanvas();