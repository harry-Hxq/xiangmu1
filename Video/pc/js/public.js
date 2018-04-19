
function formatDate(time){ 
    var timestamp = time, 
    date = new Date(timestamp), 
    datevalues = [

       date.getFullYear(),

       date.getMonth(),

       date.getDate(),

       date.getHours(),

       date.getMinutes(),

       date.getSeconds(),

    ];

     return datevalues;

}
 



function render(data, template) {

		var key = Object.keys(data);


		var logo = data.current.game;
		var words = data.current.awardNumbers.split(',');     

		var NumberS=(words[0]*1)+(words[1]*1)+(words[2]*1);

        var NumberSD=(((words[0]*1)+(words[1]*1)+(words[2]*1))%2);

     console.log(formatDate(data.time));

       var awardTime= Math.floor(((data.next.awardTimeInterval*1)/1000));

		var GamLogo ="";
    
    if(logo=="pc28a"){ 
        GamLogo="<img src='img/logob.png?"+new Date().getTime()+"'>"; 
    }else  { 
        GamLogo="<img src='img/logoa.png?"+new Date().getTime()+"'>";  
    }

		var combtxt="---";

		if(NumberS >=0 && NumberS <=13){

        combtxt="小";

    }else{

        combtxt="大";

    }

    var combtxt2="---";

		if(NumberSD ==1){

        combtxt2="单";

    }else{

        combtxt2="双";

    }

    var homeaway="---";

    

    if((words[0]*1) > (words[1]*1) ){

         homeaway="庄";

    }else if((words[0]*1) < (words[1]*1) ){

         homeaway="闲";

    }else{

         homeaway="和";

    }

     

    var bigsmall="---";

		if(NumberS >=0 && NumberS <=5){

        bigsmall="极小";

    }else if(NumberS >=22 && NumberS <=27){

        bigsmall="极大";

    }

    

    

    var triple="---";

		if(((words[0]*1)==(words[1]*1)) && ( (words[1]*1) ==(words[2]*1)) ){

	     	triple=words[0].concat((words[1]*1),(words[2]*1));

    } else if (((words[0]*1)==(words[1]*1)) || ( (words[1]*1) ==(words[2]*1)) || ( (words[0]*1) ==(words[2]*1)) ){

        triple="对子";

    } else if ((((((words[0]*1)+1))==(words[1]*1)) && ((((words[1]*1)+1))==(words[2]*1)) && ((((words[0]*1)+2))==(words[2]*1))) || (((((words[0]*1)-1))==(words[1]*1)) && ((((words[1]*1)-1))==(words[2]*1)) && ((((words[0]*1)-2))==(words[2]*1)))){

        triple="顺子";

    }else{

      triple="---";

    }

		
	template = template.replace(/{GamLogo}/g, GamLogo);
	template = template.replace(/{ThisResult}/g, data.current.periodNumber);   //上其開獎時間

    template = template.replace(/{ThisGame}/g, data.next.periodNumber);        //下其開獎時間

    //template = template.replace(/{ThisEnd}/g, awardTime-(data.next.delayTimeInterval*1));     // 下單截止

    template = template.replace(/{ThisEnd}/g, awardTime-fengpantime);     // 下單截止

    template = template.replace(/{NextStart}/g, awardTime);    //開獎時間

    template = template.replace(/{Number1}/g, words[0]);

    template = template.replace(/{Number2}/g, words[1]);

    template = template.replace(/{Number3}/g, words[2]);

    template = template.replace(/{NumberSum}/g, NumberS);

    template = template.replace(/{Combin}/g, combtxt);

    template = template.replace(/{Combin2}/g, combtxt2);

    template = template.replace(/{HomeAway}/g, homeaway);

    template = template.replace(/{BigSmall}/g,bigsmall);

    template = template.replace(/{Triple}/g, triple);

		//setTimer(awardTime);

		return template;

}





function setTimer(secI,divid,delaytime){

        //secI=5; 

    if(divid=="NextStart") { 
        var rollbacktime=secI; 
    }else{ 
         var rollbacktime=secI-delaytime; 
    }

     if(rollbacktime < 0){

             $("#"+divid).text("0");

              if(divid=="NextStart") { 
                reloadx(10,"NextStart"); 
              } 
             return; 
    }

    $('#'+divid).timer({ 
          		delay: 1000, 
          		repeat: secI, 
          		autostart: false, 
          		callback: function( index ) { 

                 if(rollbacktime==1){

                     $("#"+divid).text("0"); 
                     $('#'+divid).timer('stop');
                     if(divid=="NextStart"){ 
                          console.log("NextStart--"); 
                          reloadx(26,"reloadtime"); 
                     }  
                 }else{ 
                   $("#"+divid).text(--rollbacktime); 
                 } 
          		} 
    }); 
    $('#'+divid).timer('start'); 
}

//結束後delay 10 秒

function reloadx(secI,divid){ 
    var rollbacktime=secI;  
   $('#'+divid).timer({

          		delay: 1000, 
          		repeat: secI, 
          		autostart: false, 
          		callback: function( index ) { 
                 console.log(index); 
                 if(rollbacktime==1){ 
                      $('#'+divid).timer('stop'); 
                      init(); 
                 }else{ 
                      --rollbacktime; 
                 } 
          		} 
    }); 
    $('#'+divid).timer('start'); 
}







function init(){ 
     $.ajax({ 
        url: "ajax/pc28.php", 
        type: "GET", 
        dataType: "json", 
        success: function(Jdata) { 
        console.log(Jdata); 
        var html = render(Jdata,templatex); 
		$('.content').html(html); 
              var awardTime= Math.floor(((Jdata.next.awardTimeInterval*1)/1000)); 
              setTimer(awardTime,"NextStart",0); 
              // setTimer(awardTime,"ThisEnd",(Jdata.next.delayTimeInterval*1)); 
              setTimer(awardTime,"ThisEnd",30); 
              //  $('#NextStart').timer('start'); 
              //  $('#ThisEnd').timer('start'); 
              //alert("SUCCESS!!!"); 
        }, 
          error: function() {
          alert("ERROR!!!");
        }

   });

}



