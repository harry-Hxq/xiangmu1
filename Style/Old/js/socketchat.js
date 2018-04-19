    if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    // 如果浏览器不支持websocket，会使用这个flash自动模拟websocket协议，此过程对开发者透明
    WEB_SOCKET_SWF_LOCATION = "/Style/Old/swf/WebSocketMain.swf";
    // 开启flash的websocket debug
    WEB_SOCKET_DEBUG = true;
	  
    var ws, name, client_list={};

    // 连接服务端
    function connect() {
       // 创建websocket
       ws = new WebSocket("ws://"+document.domain+":1892");
       // 当socket连接打开时，输入用户名
       ws.onopen = onopen;
       // 当有消息时根据消息类型显示不同信息
       ws.onmessage = onmessage; 
       ws.onclose = function() {
    	  console.log("连接关闭，定时重连");
          connect();
       };
       ws.onerror = function() {
     	  console.log("出现错误");
       };
    }

    // 连接建立时发送登录信息
    function onopen()
    {
        name = info['nickname'];
        if(!name)
        {
            window.location.href = "http://"+location.host+"/?room="+info['roomid'];
        }
        // 登录
        var login_data = '{"type":"login","client_name":"'+name.replace(/"/g, '\\"')+'","room_id":"'+info['roomid']+'", "user_id":"'+info['userid']+'", "headimg":"'+info['headimg']+'", "game": "'+info['game']+'"}';
        //console.log("websocket握手成功，发送登录数据:"+login_data);
        ws.send(login_data);
    }

    // 服务端发来消息时
    function onmessage(e)
    {
        //console.log(e.data);
        var data = eval("("+e.data+")");
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send('{"type":"pong"}');
                break;;
            // 登录 更新用户列表
            case 'login':
                //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
                say(data['client_id'], data['client_name'],  data['client_name']+' 加入了聊天室', data['time']);
                if(data['client_list'])
                {
                    client_list = data['client_list'];
                }
                else
                {
                    client_list[data['client_id']] = data['client_name']; 
                }
                flush_client_list();
                //console.log(data['client_name']+"登录成功");
                break;
            // 发言
            case 'say':
                //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                say(data['from_client_img'], data['from_client_name'], data['content'], data['time']);
                break;
            // 用户退出 更新用户列表
            case 'logout':
                //{"type":"logout","client_id":xxx,"time":"xxx"}
                say(data['from_client_id'], data['from_client_name'], data['from_client_name']+' 退出了', data['time']);
                delete client_list[data['from_client_id']];
                flush_client_list();
        }
    }



    // 提交对话
    function onSubmit() {
      var input = $('#Message');
      var to_client_id = "all";
      var to_client_name = "所有人";
      ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.val().replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')+'"}');
      input.val('');
      input.focus();
    }

    // 发言
    function say(from_client_img, from_client_name, content, time){
        var str = '<div class="saidleft">' +
                  '<img src="' + from_client_img + '">' +
                  '<div class="tousaid">' +
                  '<span class="tousaid1">' + from_client_name + '</span>&nbsp;&nbsp;' +
                  '<span class="tousaid2">' + time + '</span>' +
                  '</div>' +
                  '<div class="tsf">' +
                  '<b></b>' + 
                  '<span class="neirongsaid" style="">' + content + '</span>' +
                  '</div>' + 
                  '</div>';
    	$(".rightdiv").prepend(str);
    }

    $(function(){
    	select_client_id = 'all';
        connect();
        FirstGetContent();
        getUserInfo();
        setInterval(getUserInfo,5000);

        $('.sendemaill').click(function(){
            onSubmit();
        });
    });

    function getUserInfo(){
		$.ajax({
			url:'/Application/ajax_getuserinfo.php',
			type: 'get',
			cache:false,
			dataType:'json',
			success:function(data){
				if(data.success){
					$('.balance').html(data.price);
					$('.online').html(data.online);
				}else{
					alert('登录过期,请重新登录！');
					window.location.href="http://" + location.host + "/?room=" + info['roomid'];
				}
			},
			error:function(){}
		});
    }
    
    function FirstGetContent(){
        $.ajax({
            url: '/Application/ajax_chat.php?type=first',
            type: 'get',
            dataType: 'json',
            success:function(data){
                addMessage(data);
                WelcomMsg(welcome,welHeadimg);
            },
            error:function(){}
        });
        $('.zytips').remove();
    }

    function WelcomMsg(data,welHeadimg){
        if(data == null || data.length<0){
            return;
        }
        var str="";
        if(welHeadimg == ''){
            welHeadimg = "/Style/images/Sys.png";
        }
        for(i=0;i<data.length;i++){
            sendtime = new Date().format("hh:mm:ss");
            str += '<div class="saidright">' +
                  '<img src="' + welHeadimg + '">' +
                  '<div class="tousaidl">' +
                  '<span class="tousaid2">'+sendtime+'</span>&nbsp;&nbsp;' +
                  '<span class="tousaid1">管理员</span>' +
                  '</div>' +
                  '<div class="ts">' +
                  '<b style="border-color:transparent  transparent transparent #98E165;"></b>' + 
                  '<span class="neirongsaidl" style="background-color:#98E165;">' + data[i] + '</span>' +
                  '</div>' + 
                  '</div>';
        }
        $('.rightdiv').prepend(str);
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

    function addMessage(data){
        if(data==null || data.length<0){
            return;
        }
        //S1代理  S2待定  S3机器人  S4全局公告
        var str="";
        for(i=0;i<data.length;i++){		
            var type = data[i].type;
            if(type.substr(0,1) == 'U'){
                str += '<div class="saidleft">' +
                  '<img src="' + data[i].headimg + '">' +
                  '<div class="tousaid">' +
                  '<span class="tousaid1">' + data[i].nickname + '</span>&nbsp;&nbsp;' +
                  '<span class="tousaid2">' + data[i].addtime + '</span>' +
                  '</div>' +
                  '<div class="tsf">' +
                  '<b></b>' + 
                  '<span class="neirongsaid" style="">' + data[i].content + '</span>' +
                  '</div>' + 
                  '</div>';
            }else if(type == 'S3'){
                var headimg = data[i].headimg == "" ? "/Style/images/robot.png" : data[i].headimg;
                str += '<div class="saidright">' +
                  '<img src="' + headimg + '">' +
                  '<div class="tousaidl">' +
                  '<span class="tousaid2">' + data[i].addtime + '</span>&nbsp;&nbsp;' +
                  '<span class="tousaid1">' + data[i].nickname + '</span>' +
                  '</div>' +
                  '<div class="ts">' +
                  '<b></b>' + 
                  '<span class="neirongsaidl" style="">' + data[i].content + '</span>' +
                  '</div>' + 
                  '</div>';
            }else if(type == 'S1'){
                var headimg = data[i].headimg == "" ? "/Style/images/Sys.png" : data[i].headimg;
                str += '<div class="saidright">' +
                  '<img src="' + headimg + '">' +
                  '<div class="tousaidl">' +
                  '<span class="tousaid2">' + data[i].addtime + '</span>&nbsp;&nbsp;' +
                  '<span class="tousaid1">' + data[i].nickname + '</span>' +
                  '</div>' +
                  '<div class="ts">' +
                  '<b></b>' + 
                  '<span class="neirongsaid" style="background-color:#98E165;">' + data[i].content + '</span>' +
                  '</div>' + 
                  '</div>';
            }
        }
        $('.rightdiv').prepend(str);
    }