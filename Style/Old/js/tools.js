var tz_types = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
$(function () {
	$(".keybord").on('touchstart', function () {
		$(this).toggleClass("gray");
		$(".keybord_div").toggle();
		$("#Message").attr("readonly", !$(this).hasClass('gray'));
	});

	$(".keybord_div em").on('touchstart', function () {
		$(this).addClass("on").siblings().removeClass('on');
		if ($(this).hasClass("c2")) return;
		var val = $("#Message").val();
		var vkey = $(this).html();
		if (vkey == "清") {
			return $("#Message").val('');
		}
		if (vkey == "←" || vkey == "删") {
			return $("#Message").val(val.substr(0, val.length - 1));
		}
		if (vkey == "×") {
			$('.keybord').addClass("gray");
			$(".keybord_div").hide();
			$("#Message").attr("readonly", false);
			return;
		}
		$("#Message").val(val + vkey);
	});

	$(".keybord_div em").on('touchend', function () {
		$(this).removeClass("on");
	})

	$(".keybord_div em.c2").on('touchstart', function () {
		$('.sendemaill').click();
	});

	$(document).on("click", "i.close", function () {
		var a = $(this).data();
		a.id ? $(a.id).hide() : $(this).parents().hide();
	})

	$('.txtbet').click(function () {
		var box = $('.game-box');
		if (box.css('display') == 'none') {
			box.css('display', '');
			$('.txtbet').addClass('on');
		} else {
			box.css('display', 'none');
			$('.txtbet').removeClass('on');
		}
	});

	$(".leftdiv li,.nav_banner .lottery li").click(function () {
		var o = $(this), d = o.data();
		if (d.uri) { location.href = d.uri; return; }
		console.log(d);
		switch (d.id) {
			case "home":
				location.href = "#menu";
				$("#ss_menu").show().find("ul").show();
				break;
			case "lottery":
				location.href = "#menu";
				$("#ss_menu").find("ul.menu").hide();
				$("#ss_menu").show().find('ul.lottery').show();
				break;
			case "reload":
				//location.href = "http://" + location.host + "/?room=" + info['roomid'];
				window.location.reload();
				break;
			case "reload2":
				document.ifarms.location.reload();
				break;
			case "wenzi":
				$(".neirong").show();
				$(".changlong").hide();
				$('#ifarms').attr('src', '/Templates/Old/BetTrend.php');
				break;
			case "donghua":
				$(".neirong").hide();
				$(".changlong").hide();
				$('#ifarms').attr('src', '/Templates/Old/shipin.php');
				break;
			case "changlong":
				$(".neirong").hide();
				$(".changlong").show();
				$("#ifarms").hide();
				break;
			case "skefu":
				//$(".kefu").show();
				$(".touzu").hide();
				$(".rightdiv").hide();
				//$('#iframe').hide();
				$('#iframe').attr('src', '/Templates/Old/kefu.php');
				$('#iframe').show();
				$('.game-box').css('display', 'none');
				$('.txtbet').removeClass('on');
				break;
			case "guess":
				$(".touzu").show();
				$(".rightdiv").show();
				$(".kefu").hide();
				$('#iframe').hide();
				$('#iframe').attr('src', '');
				$('.game-box').css('display', 'none');
				$('.txtbet').removeClass('on');
				break;
			case 'logs':
				$('#iframe').attr('src', '/Templates/Old/Bet.php');
				$('#iframe').show();
				$(".kefu").hide();
				$(".touzu").hide();
				$(".rightdiv").hide();
				$('.game-box').css('display', 'none');
				$('.txtbet').removeClass('on');
				break;
			case 'guize':
				$('#iframe').attr('src', '/Templates/Old/rule.php');
				$('#iframe').show();
				$(".kefu").hide();
				$(".touzu").hide();
				$(".rightdiv").hide();
				$('.game-box').css('display', 'none');
				$('.txtbet').removeClass('on');
				break;
			case 'caiwu':
				$('#msgdialog').modal('show');
				break;
			case 'tgzq':
				window.location.href = "/Templates/New/tgzq.php";
				break;
			case "smallwindows":
				if ($("#ifarms").is(":visible")) {
					$("#ifarms").toggleClass("minmax");
					$(this).find('span').text($("#ifarms").hasClass("minmax") ? "大窗" : "小窗");
					return;
				}
				if ($(".neirong").is(":visible")) {
					$(".neirong").toggleClass("minmax");
					$(this).find('span').text($(".neirong").hasClass("minmax") ? "大窗" : "小窗");
				}
				break;
		}
	});
});

function iFrameHeight() {
	var ifm = document.getElementById("iframe");
	var subWeb = document.frames ? document.frames["iframe"].document : ifm.contentDocument;
	if (ifm != null && subWeb != null) {
		if (subWeb.body.scrollHeight < 1500) {
			ifm.height = 1500;
		} else {
			ifm.height = subWeb.body.scrollHeight;
		}

	}
}

function iFrameHeight2() {
	var src = $('#ifarms').attr('src');
	if ((info['game'] == 'xy28' || info['game'] == 'jnd28') && src.indexOf("shipin") != -1) {
		$('#ifarms').animate({ height: "430px" });
	}else{
		$('#ifarms').animate({ height: "630px" });
		$('#ifarms').css("height","630px");
	}
}

var in_array = function (sVal, aVal) {
	for (s = 0; s < aVal.length; s++) {
		tVal = aVal[s].toString();
		if (tVal == sVal) {
			return true;
		}
	}
	return false;
}
var zy = zy || {};
zy.tips = function (msg, time) {
	var zytips = $(".zytips");
	if (zytips.length == 0) {
		zytips = $('<div class="zytips"></div>').appendTo("body");
	}
	zytips.html("<div>" + msg + "</div>");
	$(".zytips").fadeOut('fast');
	zytips.fadeIn();
	time = time || 2;
	var tt = window.setTimeout(function () {
		$(".zytips").fadeOut('fast');
	}, time * 1000);
}