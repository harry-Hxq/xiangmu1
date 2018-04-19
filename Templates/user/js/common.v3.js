(function($,window,undefined){

    var $ucFix = $(".mod_uc_fix_wrap");

    // 设置中间内容高度，定位底部copyright
    var setFixHeight = function(){
        var _h = $("body").height();
        var _header_h = $(".mod_header_fix_wrap").height() + $(".mod_header_fix_da").height();

        $(".mod_uc_fix_wrap").css({
            top: _header_h,
            height: _h
        });

    }


    // 入口函数
    var init = function(){
        setTimeout(setFixHeight,500);
    }

    $(document).ready(function() {
        init();

        $(".right_btn_1").click(function(event) {
            $(".mod_uc_fix_wrap").show();
        });
        $(".mod_uc_fix_wrap").click(function(event) {
            $(this).hide();
        });
    });

})(	jQuery,window)