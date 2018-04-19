var page = 1,isLoading=false,hasMore=true,listId="#datalist",moreId =".loading",loaderr= 0;moreURL  = document.location.href;
var zy = zy||{};
zy.tips=function(msg,time){
    var zytips=$(".zytips");
    if(zytips.length==0){
      zytips=$('<div class="zytips"></div>').appendTo("body");
    }
    zytips.html("<div>"+msg+"</div>");
    $(".zytips").fadeOut('fast');
    zytips.fadeIn();
    time = time || 2;
    var tt = window.setTimeout(function(){
	    $(".zytips").fadeOut('fast');
	 },time*1000);
}
function load_index(){
	$.get("/index/ajax").then(function(res){$(".main").html(res);});
}
function load_slide_menu(){
	$.get("/index/menu").then(function(res){$("#ss_menu").html(res);});
}
function load_more(url,data){
	query_arg=data;
	if(isLoading || !hasMore) return;
	isLoading = true;
	if(data.p==1){$(listId).html("")}
	$(moreId).show();
	$.ajax({url:url,dataType:"json",data:data,type:"POST",error: function(){
			isLoading = false;loaderr++;
			if(loaderr>3){
				layer.open({content:"Sorry!加载失败",skin: 'msg',time: 2});
			}else{
				load_more(url,data);
			}
		},success: function(res){
			if(res.status){
				page++;loaderr=0;
				if(res.map.total == 0){
					$(listId).html(res.content);	
				}else{
					if(res.map.page == 1){
						$(listId).html(res.content);	
					}else{
						$(res.content).appendTo($(listId));	
					}
				}
				if(res.map.page>=res.map.totalpage){hasMore = false;}
				else{hasMore = true}
				$(moreId).hide();isLoading = false;
			}
	}});
}

$(function(){
	$('body').append('<section id="ajax_container" class="win-show score-view-box"></section>');
})

$(document).on("click",".ajax_load",function(){
	var a = $(this).data();
	location.href = "#win";
	$.get(a.uri).then(function(res){
		layer.closeAll();
		layer.open({type: 1 ,content: res ,anim: a.anim || 'up'
					,style: 'position:fixed;'+(a.css||'bottom: 0;')+'left:1%;width:98%; max-height:100%; min-height:40%; border: none;border-top-left-radius: 10px; border-top-right-radius: 10px; -webkit-animation-duration: .5s; animation-duration: .5s;'
		});
	});
});

$(document).on("click",".ajax_charge .ftype a",function(){
	$(this).addClass("on").siblings().removeClass('on');
	if($(this).data("type") == 'tixian'){
	    var that = $(this).parents().find("input[name='money']");
	    if($(that).val() * 1 > $(that).data("max")*1) $(that).val($(that).data("max"));
	}
});

$(document).on("click",'show_load',function(){
	 layer.open({type: 2,content: '加载中'});
});

$(document).on("keyup",".ajax_charge input[name='money']",function(){
	var a = $(this).parents('.ajax_charge').find(".ftype a.on").data();
	if(a.type == 'tixian'){
	    if($(this).val() * 1 > $(this).data("max")*1) $(this).val($(this).data("max"));
	}
});

$(document).on("click",'.game-list a.disabled',function(){
	layer.open({content:'暂未开通',skin: 'msg',time: 2});
});

$(document).on("click",".ajax_charge .submit",function(){
	var p = $(this).parents('.ajax_charge');
	var t = $(p).find(".ftype a.on").data('type');
	var m = $(p).find("input[name='money']").val();
	layer.open({
		content: '确定要提交吗？',btn: ['确定', '取消'],yes: function(index){
	      layer.close(index);
		$.ajax({
			type:"post",
			url:"/member/ajax_charge",
			data:{type:t,money:m},
			success:function(res){
				layer.closeAll();
				layer.open({content:res.info,skin: 'msg',time: 2});
				location.reload();
			},
			error:function(){
				
			}
		});
	  }
	});
});

$(document).on("click",".agent_config .submit",function(){
	 var that  = $(this);
	 var ratio = $(that).parents('.agent_config').find('.ratio').val() * 1.0;
	 if(ratio <0 || ratio > 100){
//	 	layer.closeAll();
		layer.open({content:'数字错误，请设置0~100',skin: 'msg',time: 2});
	 	return;
	 }
	 layer.open({
	    content: '确定要保存吗？',btn: ['确定', '取消'],yes: function(index){
	      layer.close(index);
	      $.ajax({
	      	type:"post",
	      	url:$(that).data('uri'),
	      	data:{ratio:ratio},
	      	success:function(res){
	      		layer.open({content:res.info,skin: 'msg',time: 2});
	      		location.reload();
	      	}
	      });
	    }
	  });
});

$(document).on("click",".close_layer",function(){layer.closeAll();});


$(document).on("click",".ajax_uri",function(){
	var uri = $(this).data('uri');
	if(!uri) return;
	location.href = uri;
});


$(document).on("click",".ajax_page",function(){
	var uri = $(this).data('uri');
	if(!uri) return;
	data = $(this).data();
	delete data.uri;
	$.ajax({type:"get",url:uri,data:data,
		success:function(html){
			$("#ajax_container").html(html).addClass("on");
			location.href = "#ajax_page";
		},
		error:function(){
			
		}
	});
});

$(document).on("click","#ajax_container .back",function(){
	$("#ajax_container").removeClass("on");
	location.href = "#";
})

window.onhashchange =function(){
	if(location.href.indexOf("#win")==-1){
		layer.closeAll();
	}
	if(location.href.indexOf("#ajax_page")==-1){
		$("#ajax_container,.win-show").removeClass("on");
	}
}


