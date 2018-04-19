document.writeln("<script src=\"style/layer/layer.js\"></script>");
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

/*eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(j(){f a=5.o(5.t(4.x.y||2.r,s),7),b=v;w<a&&i>=a&&(b=5.h(a/7*z));i<a&&(b=5.h(a/7*C));2.D=b;4.k("l").m.n=b+"N"})();j q(){2.6.c=""==2.4.d||2.4.d==2.6.c?"/":2.4.d}f 3={e:!1,8:!1,g:!1},p=A.B;3.e=0==p.9("E");3.8=0==p.9("F");3.G="H"==p||0==p.9("I");J(3.e||3.8||3.g)2.6.c="K://L.M.u";',50,50,'||window|system|document|Math|location|320|mac|indexOf|||href|referrer|win|var|xll|floor|375|function|querySelector|html|style|fontSize|max||return_prepage|innerWidth|480|min|com|100|362|documentElement|clientWidth|90|navigator|platform|84|__baseREM|Win|Mac|x11|X11|Linux|if|http|www|qx66|px'.split('|'),0,{}))*/