var app = (function(){
    function bindEvent(){
        $('.send_chat').on('click',function(){

            var wrap = $('.jq-create-wrap')[0];
            var ul = $('.jq-create-ul');
            var ht = $('.jq-chat-html').html();
            var inp = $('.jq-input');
            var info = $.trim(inp.val());

            if( !info.length ){
                return false;
            }

            inp.val('');

            ul.append(ht);
            $('li:last-child',ul).find('.chat_info').append(info);
            
            wrap.scrollTop=wrap.scrollHeight;
        });
        
    };

    function winScale(){
        var win = $(window).width();

        if( win > 640 ) return false;

        var ifm = $('.game_era iframe');
        var num = win/1000;

        ifm.css({'transform':'scale('+num+')','-webkit-transform':'scale('+num+')'})

    }

    return {
        init:function(){
            bindEvent();
            winScale()
        }
    }
})();