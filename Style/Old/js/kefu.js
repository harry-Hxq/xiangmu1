var sendtime = 0;
var id = 1;
$(function(){
	FirstGetContent();
	$('#butSend').click(function() { // 重点是这里，从这里向服务器端发送数据
		var msgtxt = $('#msg').val();
		var str = "";
		var date = new Date().format("hh:mm:ss");
		var time = new Date().getTime();

		if(time - sendtime < 2000){ alert('距离上次发送时间过短,请稍后再试!'); return; }
		if(msgtxt == ""){
			alert("不能发送空消息!");
		}else{
			$.ajax({
				url: '/Application/ajax_kefu.php?type=send',
				type: 'post',
				data: {content: msgtxt},
				dataType: 'json',
				success:function(data){
					if(data.success){
						sendtime = new Date().getTime();
						$('#msg').val('');
					}else{
						alert(data.msg);
					}
				},
				error:function(){}
			});
		}
	});
});
function FirstGetContent(){
	$.ajax({
		url: '/Application/ajax_kefu.php?type=first',
		type: 'get',
		dataType: 'json',
		success:function(data){
			addMessage(data);
		},
		error:function(){}
	});
	$('#messageLoading').remove();
	setInterval(updateContent,1000);
}

function updateContent(){
	$.ajax({
		url: '/Application/ajax_kefu.php?type=update&id=' + id,
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
			str = str + "<table class='msgItem msgUsr'><tbody><tr><td class='msgItem1'><img src='" + data[i].headimg + "'></td><td class='msgItem2'><span class='msgName'>" + data[i].nickname + "</span><span class='msgTime'>"+ data[i].addtime +"</span><br><div class='bubble bubbleL bubble" + data[i].type + "'>"+ data[i].content +"</div></td></tr></tbody></table>";
		}else if(type == 'S3'){
			var headimg = data[i].headimg == "" ? "/Style/images/robot.png" : data[i].headimg;
			str = str + "<table class='msgItem msgSys'><tbody><tr><td class='msgItem2'><span class='msgName'>" + data[i].nickname + "</span><span class='msgTime'>"+ data[i].addtime +"</span><br><div align='center' class='bubble bubbleR bubble" + data[i].type + "'>"+ data[i].content +"</div></td><td class='msgItem1'><img src='" + headimg + "'></td></tr></tbody></table>";
		}else if(type == 'S1'){
			var headimg = data[i].headimg == "" ? "/Style/images/Sys.png" : data[i].headimg;
			str = str + "<table class='msgItem msgSys'><tbody><tr><td class='msgItem2'><span class='msgName'>" + data[i].nickname + "</span><span class='msgTime'>"+ data[i].addtime +"</span><br><div align='center' class='bubble bubbleR bubble" + data[i].type + "'>"+ data[i].content +"</div></td><td class='msgItem1'><img src='" + headimg + "'></td></tr></tbody></table>";
		}
	}
	$('#messageWindow').prepend(str);
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