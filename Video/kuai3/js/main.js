var opening = null;
$(function() {
	$(".animate").find(".loading").fadeOut(1000, function() {});
	$(".kuai3Animate").on("click", ".kaimodule", function() {
		k3v.tryPlay();
	});
	$("#soundBtn").on("click", "#spanbtn", function() {
		var audioId = document.getElementById("audio");
		if($("#spanbtn").attr("class") == "sounds") {
			$("#soundBtn").children().removeClass("sounds").addClass("sounds2");
			k3v.sound.stop("");
		} else {
			$("#soundBtn").children().removeClass("sounds2").addClass("sounds");
			k3v.sound.play("all");
		}
	});
})

var k3v = {},
	tryflag = true, //试试手
	timer = null, //试试手
	ifpaused = "", //是不是音乐都暂停了
	animateId = {};
k3v.startGame = function(flag) {
	//操作号码
	var obj = this;
	obj.codePlay = function() {
		var li = $("#code").find("li");
		obj.run(2, "80", "0", li);
		obj.run(5, "80", "1", li);
		obj.run(8, "80", "2", li);
	}
	obj.run = function(num, space, id, li) {
		var interval = setInterval(function() {
			$(li).eq(id).attr("class", "k3v0" + num);
			num++;
			if(num >= 8) {
				num = 1;
			}
		}, space);
		animateId[id] = interval;
	}
	if(flag) {
		obj.codePlay();
	}
	$(".linelist").find("li").addClass("redli");
	//添加音乐播放
	ifpaused = "audioidB";
	if(ifopen && $("#spanbtn").hasClass("sounds")) {
		k3v.sound.play("audioidR");
	}
	k3v.bressBG(10); //呼吸动作
}
k3v.stopGame = function(arr) {
	this.stop = function(i, arr) {
		setTimeout(function() {
			clearInterval(animateId[i]);
			var li = $("#code").find("li");
			$(li).eq(i).attr("class", "k3v" + arr);
		}, i * 800);
	}
	for(var i = 0, len = 3; i < len; i++) {
		this.stop(i, arr[i]);
	}
}
//模拟开奖
var trytime = [];
k3v.tryPlay = function() {
	var arr = [];
	if(tryflag) {
		$("#timetitle").text("模拟开奖");
		$("#hourtxt").hide();
		$("#opening").show();
		tryflag = false;
		k3v.startGame(true);
		var time1 = setTimeout(function() {
			for(var i = 0; i < 3; i++) {
				arr.push(Math.round(Math.random() * 5 + 1));
			}
			k3v.stopGame(arr);
			var time2 = setTimeout(function() {
				var codetop = $("#codetop").find("li");
				var resultArr = [];
				for(var i = 0, len = codetop.length; i < len; i++) {
					resultArr.push($(codetop).eq(i).text());
				}
				k3v.stopGame(resultArr);
				setTimeout(function() {
					tryflag = true;
				}, 3000);
			}, 8000);
			var time3 = setTimeout(function() {
				$("#timetitle").text("倒计时");
				$("#hourtxt").show();
				$("#opening").hide();
				var hourtxt = $("#hourtxt").text().split(":");
				var hour = hourtxt[0];
				var minute = hourtxt[1];
				var second = hourtxt[2];
				hour = hour < 10 ? hour.substring(hour.length - 1, hour.length) : hour;
				minute = minute < 10 ? minute.substring(minute.length - 1, minute.length) : minute;
				second = second < 10 ? second.substring(second.length - 1, second.length) : second;
				var seconds = hour * 3600 + minute * 60 + second * 1;
				k3v.cutTime(seconds);
				ifpaused = "audioidB";
				if(ifopen && $("#spanbtn").hasClass("sounds")) {
					k3v.sound.play("audioidB");
				}
				k3v.bressBG();
			}, 2000);
			trytime.push(time1);
			trytime.push(time2);
			trytime.push(time3);
		}, 5000);
	} else {
		$(".noinfor").fadeIn(200, "", function() {
			setTimeout(function() {
				$(".noinfor").fadeOut("300");
			}, 1000)
		});

	}
}
//倒计时完成启动动画
k3v.playGame = function() {
	k3v.startGame(true);
}
//停止开奖时更新数据
k3v.updateData = function(data) {
	var arrCode = data.preDrawCode;
	var num1 = $("#num1").text(arrCode[0]);
	var num2 = $("#num2").text(arrCode[1]);
	var num3 = $("#num3").text(arrCode[2]);
	var sumNum = $("#sumNum").text(data.sumNum);
	var sumBigSmall = $("#sumBigSmall").text(k3v.sumBigSmall(data.sumNum));
	var drawIssue = $("#drawIssue").text(data.drawIssue);
	if(data.drawTime != undefined){
		var drawTimestr = data.drawTime.substr(data.drawTime.length - 8, 8);		
	} else{
		var drawTimestr = "";
	}
	var drawTime = $("#drawTime").text(drawTimestr);
}
//倒计时
k3v.cutTime = function(sys_second) {
	if(timer != null) {
		clearInterval(timer);
	}
	var sys_second = sys_second;
	timer = setInterval(function() {
		if(sys_second >= 1) {
			sys_second -= 1;
			//var day = Math.floor((sys_second / 3600) / 24);
			var hour = Math.floor(sys_second / (60 * 60));
			var minute = Math.floor((sys_second / (60)) % 60);
			var second = Math.floor((sys_second) % 60);
			var html = "";
			//如果时间小于0则删除时间显示
			html = (hour < 10 ? ("0" + hour) : hour) + ":";
			html = html + "" + (minute < 10 ? ("0" + minute) : minute) + ":" + (second < 10 ? ("0" + second) : second);
			$("#hourtxt").text(html); //计算小时
			if(sys_second < 10) {
				var lilist = $(".linelist").find("li");
				$(lilist).eq(sys_second).addClass("redli");
			}
			if(sys_second < 20) {
				tryflag = false;
				$(".noinfor").text("即将开奖，禁止模拟");
			}
		} else {
			$(".noinfor").text("正在开奖，禁止模拟");
			clearInterval(timer); //清除当前定时器
			k3v.playGame();
			opening = setInterval(function(){
				getopen();
			}, 1000)
			$("#timetitle").text("正在开奖");
			$("#hourtxt").hide();
			$("#opening").show();
		}
	}, 1000);
}
k3v.sound = {
	play: function(id) {
		if($("#spanbtn").attr("class") == "sounds" && ifopen) {
			if(id == "all") {
				document.getElementById(ifpaused).play();
			} else {
				document.getElementById("audioidB").pause();
				document.getElementById("audioidR").pause();
				document.getElementById(id).play();
			}
		}
	},
	stop: function(id) {
		var audioidB = document.getElementById("audioidB");
		if(audioidB.paused) {
			ifpaused = "audioidR";
		} else {
			ifpaused = "audioidB";
		}
		document.getElementById("audioidB").pause();
		document.getElementById("audioidR").pause();

	}
}
//停止动画
k3v.stopVideo = function(data) {
	/*var arr=[];
	for(var i = 0; i < 3; i++) {
		arr.push(Math.round(Math.random() * 5 + 1));
	}*/
	/*var data = {
		preDrawCode:'2,4,6',
		sumNum:12,
		sumBigSmall:"小",
		drawIssue:"170517061",
		drawTime:"2017-05-17 18:40:00"
	}*/
	//终止游戏
	k3v.stopGame(data.preDrawCode);
	//更新数据
	k3v.updateData(data);
	setTimeout(function() {
		$("#timetitle").text("倒计时");
		$("#hourtxt").fadeIn();
		$("#opening").hide();
		$(".linelist").find("li").removeClass("redli");
		k3v.cutTime(data.seconds);
		ifpaused = "audioidB";
		if(ifopen && $("#spanbtn").hasClass("sounds")) {
			k3v.sound.play("audioidB");
		}
		k3v.bressBG();
		tryflag = true;
	}, 2000);
}
k3v.bressBG = function(space) {
	var opacityV = 1,
		flag = false;
	if(animateId["bressBG"] != undefined) {
		clearInterval(animateId["bressBG"]);
	}
	if(space == undefined) {
		space = 80;
	}
	var timesetInterval = setInterval(function() {
		$(".bodybg").find("img").stop().animate({
			opacity: "0." + opacityV
		}, space);
		if(flag) {
			opacityV -= 1;
			if(opacityV < 1) {
				flag = false;
			}
		} else {
			opacityV += 1;
			if(opacityV > 8) {
				flag = true;
			}
		}
	}, space);
	animateId["bressBG"] = timesetInterval;
}
k3v.sumBigSmall = function(sumBigSmall) {
	if(sumBigSmall <= 10) {
		return "小";
	} else {
		return "大";
	}
}

function getajax(){
	$.get('ajax/index.php',function(data){
		console.log(term,data.term);
		var preDrawCode = [].slice.call(data.code);
		var datas = {
			preDrawCode: preDrawCode,
			preDrawIssue: data.term,
			sumNum:data.sumNum,
			sumBigSmall:data.dx,
			drawIssue:parseInt(data.term)+1,
			drawTime:data.nexttime,
			seconds:data.count
		}
		if(term != data.term && data.term != ''){
			term = data.term;
			$('.nowDraw').text(String(datas.preDrawIssue).substr(-3, 3));
			k3v.stopVideo(datas);
		}
	},'json');
}

function getopen(){
	$.get('ajax/index.php',function(data){
		console.log(term,data.term);
		var preDrawCode = [].slice.call(data.code);
		var datas = {
			preDrawCode: preDrawCode,
			preDrawIssue: data.term,
			sumNum:data.sumNum,
			sumBigSmall:data.dx,
			drawIssue:parseInt(data.term)+1,
			drawTime:data.nexttime,
			seconds:data.count
		}
		if(term != data.term && data.term != ''){
			term = data.term;
			$('.nowDraw').text(String(datas.preDrawIssue).substr(-3, 3));
			k3v.stopVideo(datas);
			clearInterval(opening);
		}
	},'json');
}