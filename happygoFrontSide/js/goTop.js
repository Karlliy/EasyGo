//回到頂端 JQ-1
$(document).ready(function() {
    $(".go_top").click(function() {
        // 點下按鈕後，會以動態效果讓捲軸捲到網頁最頂端，500為速度
        $("html, body").animate({
            scrollTop: 0
        }, 500);
    });
});
// 回到頂端 JQ-2
$(window).scroll(function() {
    // 目前的位置距離網頁頂端，大於350px就顯示按鈕，小於就隱藏
    if ( $(this).scrollTop() > 350){
        $(".go_top").fadeIn("fast");
    } else {
        $(".go_top").stop().fadeOut("fast");
    }
});


//最新消息 JQ
$(function() {
    //點擊主選單(.diss_main)，則子選單(ul.diss_subs)向上滑動隱藏，移除主選單(.diss_main)的class(open)，
    //如果被點擊的這個元素相鄰的ul，則向下滑動出相鄰的ul並新稱class(open)。
    $(".diss_main").click(function() {
        $("ul.diss_subs").slideUp();
        //移除在(.diss_main)的class屬性(open)
        $(".diss_main").removeClass("open");
        if ($("+ul", this).css("display") == "none") {
            $("+ul", this).slideDown();
            //被點擊的元素新增一組class(open)
            $(this).addClass("open");
        }
    })
});

//浮動視窗_會員大頭照
$(function(){
    $(".share_hide").click(function(){
        $(".rwd_menu").toggle("500");
        $(".share_hide, .share_open").hide();
        $(".share_show").show();
    });
    $(".share_show").click(function(){
        $(".rwd_menu").toggle("fast");
        $(".share_show, .share_open").hide();
        $(".share_hide").show();
    });

    //點擊任何地方，則全選單隱藏，只留下快捷按鈕
    $('.popup_share').click(function(event){
        var e = window.event || event;
        if(e.stopPropagation){
            e.stopPropagation();
        }else{
            e.cancelBubble = true;
        }
    });
    document.onclick = function(){
        $(".rwd_menu, .share_hide").hide();
        $(".share_show").show();
    };
});