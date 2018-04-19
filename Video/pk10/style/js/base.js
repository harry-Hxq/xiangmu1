document.writeln("<script src=\"./style/layer/layer.js\"></script>");
function goTop(times){
 if(!!!times){
  $(window).scrollTop(0);
  return;
 }
  
 var sh=$('body').scrollTop();//移动总距离
 var inter=13.333;//ms,每次移动间隔时间
 var forCount=Math.ceil(times/inter);//移动次数
 var stepL=Math.ceil(sh/forCount);//移动步长
 var timeId=null;
 function ani(){
  !!timeId&&clearTimeout(timeId);
  timeId=null;
  //console.log($('body').scrollTop());
  if($('body').scrollTop()<=0||forCount<=0){//移动端判断次数好些，因为移动端的scroll事件触发不频繁，有可能检测不到有<=0的情况
   $('body').scrollTop(0);
   return;
  }
  forCount--;
  sh-=stepL;
  $('body').scrollTop(sh);
  timeId=setTimeout(function(){ani();},inter);
 }
 ani();
}
