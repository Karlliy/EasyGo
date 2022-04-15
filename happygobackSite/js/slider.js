// 每一頁_選單按下縮放效果 slider_menu.js *************************************
$(function() {
	var Accordion = function(el, multiple) {
		this.el = el || {};
		this.multiple = multiple || false;

		// Variables privadas
		var links = this.el.find('.link');
		// Evento
		links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
	}

	Accordion.prototype.dropdown = function(e) {
		var $el = e.data.el;
			$this = $(this),
			$next = $this.next();

		$next.slideToggle();
		$this.parent().toggleClass('open');

		if (!e.data.multiple) {
			$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
		};
	}

	var accordion = new Accordion($('#inside'), false);
});


// 每一頁_側欄選單滑出效果 slider.js *************************************
var bShow = false;
	$(function(){
		var w = $("#slider_content").width();

		$("#MenuBtn").mousedown(function(){
			if(!bShow){
				bShow = true;
				if ($("#sidebar").css('left') == '-'+w+'px')
				{
					$("#BlackBG").show();
					$("#sidebar").show();
					$("#sidebar").animate({ left:'0px' }, 500 ,'swing');
				}
			}else{
				bShow =false;
				$("#BlackBG").hide();
				$("#sidebar").hide();
				$("#sidebar").animate( { left:'-'+w+'px' }, 500 ,'swing');
			}
		});
	});


// (RWD手機版)每一頁_上方隱藏的下拉選單顯示 toggle.js *************************************
jQuery(function($){
	$("a.navbar_toggle").css({cursor:"pointer"}).click(function(){
	$(this).next().toggle("bold");
	});
});

// 最新優惠_下拉選單顯示
jQuery(function($){
	$("div.ad_title").css({cursor:"pointer"}).click(function(){
	$(this).next().toggle("bold");
	});
});


// 回到頂端(按鈕) go_top.js *************************************
$(function(){
    // 點下按鈕之後，會以animate動態效果讓捲軸捲到網頁最頂端
    $("#btn_goTop").click(function(){
        jQuery("html,body").animate({
            scrollTop:0
        },500);
    });

    // 目前的位置距離網頁頂端，大於200px就顯示按鈕，小於就隱藏
    $(window).scroll(function() {
        if ( $(this).scrollTop() > 200){
            $('#btn_goTop').fadeIn("fast");
        } else {
            $('#btn_goTop').stop().fadeOut("fast");
        }
    });
});