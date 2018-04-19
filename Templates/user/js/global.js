;(function($){
	jQuery.extend({
		'GLOBAL' : {
			PASSPORT :  'https://passport.jfz.com',
			_timeID  :  '',
			showMsg  :  function(msg){//全局信息显示
				clearTimeout($.GLOBAL._timeID);
				if(msg.length>15){
            		$("#global_msg").css("height","72px");
            	}
				$("#global_msg").html(msg).show();
				$.GLOBAL._timeID = setTimeout(function() {
			    	$("#global_msg").hide();
			    	$("#global_msg").css("height","36px");
			    }, 2000);
			    return false;
			}
		}
	});
})(jQuery);

var Jfz = (function(){
	var autologin = {};
	autologin.config = {
		url : {
			'remote_check':  $.GLOBAL.PASSPORT + '/passport/user/loginStatus?cb=?',
			'local_check' :  '/public/login/LoginState',
			'auth_login'  :  $.GLOBAL.PASSPORT + '/wechat/bind/authLogin?cb=?',
			'callback'    :  '/login/index?authToken='
		},
		callback : function(){return true;}
	}
	autologin.request = function(type, data){
		var _urls = autologin.config.url;		
		$.getJSON(_urls[type], data, function (data) {
            if (data[0] == 10000) {
            	$.getJSON(_urls['callback'] + data[2]['authToken'], function (data) {
                    if (data[0] == 10000) {
                    	autologin.config.callback();
						executeIsLogin();
                    }
                })
            }
        });
	}
	autologin.checkLogin = function(){
		autologin.request('remote_check',{});
	}
	autologin.authLogin = function(_openId){
		autologin.request('auth_login',{'openId':_openId, 'type':6});
	}
	
	return {
		autologin : autologin
	}
})();

$(document).ready(function(){
	if($("meta[name='jfz_login_status']").attr("content")==0){
		var _openId = $("#jfz_wechat_bind_openId").attr("data-content");
		if(typeof(_openId)!='undefined' && _openId!=0){
			Jfz.autologin.authLogin(_openId);
		}else{
			Jfz.autologin.checkLogin();
		}
	}else{
		executeIsLogin();
	}
});

function executeIsLogin(){
	if (typeof(eval($.GLOBAL.isLogin)) == "function") {
		$.GLOBAL.isLogin();
	}
}