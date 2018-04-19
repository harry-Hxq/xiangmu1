var sendtime = 0;
var id = 1;
$(function(){
	FirstGetContent();
	$('#sendbtn').click(function() { // 重点是这里，从这里向服务器端发送数据
		var msgtxt = $('#c0').val();
		var str = "";
		var date = new Date().format("hh:mm:ss");
		var time = new Date().getTime();

		if(time - sendtime < 2000){ alert('距离上次发送时间过短,请稍后再试!'); return; }
		if(msgtxt == ""){
			alert("不能发送空消息!");
		}else{
			$.ajax({
				url: '/Application/ajax_chat.php?type=send',
				type: 'post',
				data: {content: msgtxt},
				dataType: 'json',
				success:function(data){
					if(data.success){
						sendtime = new Date().getTime();
						str = "<table class='msgItem msgUsr'><tbody><tr><td class='msgItem1'><img src='" + headimg + "'></td><td class='msgItem2'><span class='msgName'>" + nickname + "</span><span class='msgTime'>"+ date +"</span><br><div class='bubble bubbleL bubbleU2'>"+ msgtxt +"</div></td></tr></tbody></table>"
                      str = '<li>' +
                            '<div class="left">' +
                            '<img style="border-radius:50px" src="' + headimg + '">' +
                            '</div>' +
                            '<div class="right">' +
                            '<p class="username">' + nickname + '<span>' + date + '</span></p>' +
                            '<p class="chat_info" style="color:#333333;font-size:12px;width:auto;" align="left"><i></i>' + msgtxt + '</p>' +
                            '</div>' +
                            '</li>';
						$('#chat').prepend(str);
						$('#c0').val('');
					}else{
						alert(data.msg);
					}
				},
				error:function(){}
			});
		}
	});
	$("#hidvod").click(function(){
		if($(this).hasClass('hid')){
			$(this).html('隐藏视频').removeClass('hid');
			$("#vodbox").animate({ height: "245px", }, 400 );
			$("#uchar").animate({ top: "640px", }, 400 );
		}else{
			$(this).html('显示视频').addClass('hid');
			$("#vodbox").animate({ height: "32px", }, 400 );
			$("#uchar").animate({ top: "200px", }, 400 );
		}
	});

});

function FirstGetContent(){
	$.ajax({
		url: '/Application/ajax_chat.php?type=first',
		type: 'get',
		dataType: 'json',
		success:function(data){
			addMessage(data);
			addWelcome(welcome,welHeadimg);
		},
		error:function(){}
	});
	$('#messageLoading').remove();
	setInterval(updateContent,1000);
}

function updateContent(){
	$.ajax({
		url: '/Application/ajax_chat.php?type=update&id=' + id,
		type: 'get',
		dataType: 'json',
		success:function(data){
			addMessage(data);
		},
		error:function(){}
	});
}

function addMessage(data){
	if(data==null || data.length<0){
		return;
	}
	//S1代理  S2待定  S3机器人  S4全局公告
	var str="";
	for(i=0;i<data.length;i++){		
		if(parseInt(data[i].id) > id){
			id = data[i].id;
		}
		var type = data[i].type;
		if(type.substr(0,1) == 'U'){

            str = str + '<li>' +
                      '<div class="left">' +
                      '<img style="border-radius:50px" src="' + data[i].headimg + '">' +
                      '</div>' +
                      '<div class="right">' +
                      '<p class="username">' + data[i].nickname + '<span>' + data[i].addtime + '</span></p>' +
                      '<p class="chat_info" style="color:#333333;font-size:12px;width:auto;" align="left"><i></i>' + data[i].content + '</p>' +
                      '</div>' +
                      '</li>';
		}else if(type == 'S3'){
			var headimg = data[i].headimg == "" ? "/Style/images/robot.png" : data[i].headimg;
			str = str+ '<li>' +
                  '<div class="me_right"><img style="border-radius:50px" src="' + headimg + '" /></div>' +
                  '<div class="me_left">' +
                  '<p class="username">' +
                  '<span>' + data[i].addtime + '</span>机器人' +
                  '</p>' +
                  '<p class="chat_info" style="color:#333333; font-size:12px; "><i></i>' + data[i].content + '</p> ' +
                  '</div>' +
                  '</li>';
		}else if(type == 'S1'){
		    var headimg = data[i].headimg == "" ? "/Style/images/Sys.png" : data[i].headimg;
			str = str + '<li>' +
                  '<div class="me_right"><img style="border-radius:50px" src="' + headimg + '" /></div>' +
                  '<div class="me_left">' +
                  '<p class="username">' +
                  '<span>' + data[i].addtime + '</span>管理员' +
                  '</p>' +
                  '<p class="chat_info" style="color:#333333; font-size:12px; "><i></i>' + data[i].content + '</p> ' +
                  '</div>' +
                  '</li>';
		}
	}
	$('#chat').prepend(str);
}

function addWelcome(data, headimg){
	if(data == null || data.length <= 0){
		return;
	}
	if(headimg == ''){
		headimg = "/Style/images/Sys.png";
	}
	sendtime = new Date().format("hh:mm:ss");
	var str = '';
	for(var i=0;i<data.length;i++){
		str = str + '<li>' +
                '<div class="me_right"><img style="border-radius:50px" src="' + headimg + '" /></div>' +
                '<div class="me_left">' +
                '<p class="username">' +
                '<span>' + sendtime + '</span>管理员' +
                '</p>' +
                '<p class="chat_info" style="color:#333333; font-size:12px; "><i></i>' + data[i] + '</p> ' +
                '</div>' +
                '</li>';
	}
	$('#chat').prepend(str);
}

Date.prototype.format = function(format)
{
 var o = {
 "M+" : this.getMonth()+1, //month
 "d+" : this.getDate(),    //day
 "h+" : this.getHours(),   //hour
 "m+" : this.getMinutes(), //minute
 "s+" : this.getSeconds(), //second
 "q+" : Math.floor((this.getMonth()+3)/3),  //quarter
 "S" : this.getMilliseconds() //millisecond
 }
 if(/(y+)/.test(format)) format=format.replace(RegExp.$1,
 (this.getFullYear()+"").substr(4 - RegExp.$1.length));
 for(var k in o)if(new RegExp("("+ k +")").test(format))
 format = format.replace(RegExp.$1,
 RegExp.$1.length==1 ? o[k] :
 ("00"+ o[k]).substr((""+ o[k]).length));
 return format;
}