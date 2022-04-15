
/*MemberOnline();

function MemberOnline() {

	setTimeout("MemberOnline()", 10000);
	$('base').attr('href','');
		//alert($("form").serialize())
		//$('#divLoading').show();
		$.post("./MemberOnline/",'',function (data){
			console.log(data);
			//eval(data);
			//$('#LoginVerify').attr('src','img.php');
		});


}*/
var paycanal, payamount;
var socket;

// var host = "ws://45.32.250.55:5864/";
//var host = "ws://127.0.0.1:5864/";

//console.log(getUrlParameter('rid'));


$(function() { 

	// if (getUrlParameter('rid') != null && getUrlParameter('rid').length == 7) {
	// 	//console.log(getUrlParameter('rid'));
		// $('div[id="popup_Register"]').show();
	// }
	
	// console.log('Currently'+Currently);
	// socket = new WebSocket(host);
	//console.log('WebSocket - status ' + socket.readyState);

	// try {                            
		
	// 	//alert(Marqueedata);
	// 	socket.onopen = function (msg) {
	// 		if (Marqueedata != "undefined" ) {
	// 			socket.send(Marqueedata);
	// 		}
	// 		//const sendNowTime = setInterval(()=>{
	// 		//    socket.send({$MRow['MemberId']})
	// 		//},5000)
		
	// 		//console.log("{$MRow['MemberId']} Welcome - status " + this.readyState);
	// 	};
	// 	socket.onmessage = function (msg) {
	// 		//console.log("Received: " + msg.data);
	// 		var jsonData = JSON.parse(msg.data);

	// 		if (jsonData[0] == "Marquee" ) {
	// 			$('span[id="Notice"]').html(jsonData[1]);
	// 		}else if (jsonData[0] == "Chat" ) {
	// 			if (Currently == "Lobby") {
	// 				$('div[id="pubchat"]').html(jsonData[1]);
	// 				$('div[id="pubchat"]').scrollTop(10000000);
	// 			}
	// 		}else if (jsonData[0] == "PriChat" ) {
	// 			//console.log(jsonData[0]);

	// 			if (Currently == "Lobby") {
	// 				$('button[id="prisendchat"]').data('aimid',jsonData[2]);
	// 				$('span[id="target"]').html(jsonData[3]);
	// 				if(jsonData[1] != null){
	// 					$('div[id="prichat"]').html(jsonData[1]);
	// 				}else {
	// 					$('div[id="prichat"]').html('');
	// 				}
	// 				$('div[id="popup_private"]').css('display','block');
	// 				$('div[id="prichat"]').scrollTop(10000000);
	// 			}
	// 		}
	// 	};
	// 	socket.onclose = function (msg) {
	// 		console.log("Disconnected - status " + this.readyState);
	// 	};
	// } catch (ex) {
	// 	console.log(ex);
	// }
						
	// $('form[id="loginform"]').on('keypress', function (Event) {
	// 	if ( Event.which == 13 )
	// 	{
	// 		$('button[id="Login"]').click();
	// 	}
	// });

    $.post("Member/Checklogin", function (data) {
		console.log(data);
        var obj = JSON.parse(data);
		sessionStorage.setItem('session_MemberAccount', obj[1]);
        // var hd_login = document.getElementById("hd_login");
        // var hd_member = document.getElementById("hd_member");
        if (obj[0] == "1") {    
            $('div[id="hd_login"]').hide();
            $('div[id="hd_member"]').show();
            // $('div[id="popup_LoginPublish"]').hide();
            /*hd_login.style.display="none"; 
            hd_member.style.display="block"; */
        }else if (obj[0] == "0") {
            $('div[id="hd_login"]').show();
            $('div[id="hd_member"]').hide();
            /*hd_login.style.display="block"; 
            hd_member.style.display="none"; */
        }
    
    });

	// if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_sell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_buy"){
    //     $.post("Order/GameInfo", {fun: "MemberOnline", val:sessionStorage.getItem('session_MemberAccount')}, data =>{
    //         console.log(sessionStorage.getItem('session_MemberAccount'));
    //         var obj = JSON.parse(data);
    //         console.log(obj[3]);
    //         $('span[id="MemberAccount"]').html(obj[0]);
    //         $('span[id="LastLoginDate"]').html(obj[1]);
    //         if(obj[3] == 0){
    //             $('span[id="offline_state"]').html(obj[2]);
    //             $('.online_state').removeClass();
    //         }else{
    //             $('span[id="online_state"]').html(obj[2]);
    //             $('.offline_state').removeClass();
    //         }
    //     });
    // }

	$(document).on('click', 'div[id="Login"]', function (Event) {
		$('base').attr('href','../');
		// alert($("form").serialize());
		// alert(123);
		//$('#divLoading').show();
		$.post("Member/Login",$('form[id="loginform"]').serialize(),function (data){
			console.log(data);
			eval(data);
			//$('#LoginVerify').attr('src','img.php');
		}).fail(function(xhr, status, error) {
            //alert( "error"+xhr );
            console.log(xhr);
            alert(xhr.responseText)
           });
        
	});
	
	$(document).on('click', 'div[id="Register"]', function (Event) {
		$('base').attr('href','../');
		// $(this).hide();
		// alert($("form").serialize());
		//$('#divLoading').show();
		$.post("Member/Register",$('form[id="registerform"]').serialize(),function (data){
			console.log(data);
            
			eval(data);
			$('div[id="Register"]').show();
			//$('#LoginVerify').attr('src','img.php');
		}).fail(function(xhr, status, error) {
            //alert( "error"+xhr );
            console.log(xhr);
            alert(xhr.responseText)
           });
	});

	// $(document).on('click', 'div[id="ResetPassword"]', function (Event) {
	// 	$('base').attr('href','');
	// 	$(this).hide();
	// 	//alert($("form").serialize())
	// 	//$('#divLoading').show();
	// 	$.post("index.php",$('form[id="ResetPassword"]').serialize()+"&func=ResetPassword",function (data){
	// 		//console.log(data);
	// 		eval(data);
	// 		$('div[id="ResetPassword"]').show();
	// 		//$('#LoginVerify').attr('src','img.php');
	// 	});
	// });

	// $(document).on('click', 'i[id="logout"]', function (Event) {
	// 	$.post("member.php","func=LogOut",function (data){
	// 		//console.log(data);
	// 		eval(data);
	// 		//$('#LoginVerify').attr('src','img.php');
	// 	});
	// });

	$.post("Index/GameList", { val: '3'}, data =>{
        // console.log(data);
        $('div[id="GameList"]').html(data);
		
		$("div[id='GameListbnt']").on('click',  function(Event) {
			let GameId = $(this).data("value");

			var mapForm = document.createElement("form");
			// mapForm.target = "open";    
			mapForm.method = "POST";
			mapForm.action = "Order/SellList";

			// Create an input
			var mapInput = document.createElement("input");
			mapInput.type = "hidden";
			mapInput.name = "GameId";
			mapInput.value = GameId;

			// Add the input to the form
			mapForm.appendChild(mapInput);

			// Add the form to dom
			document.body.appendChild(mapForm);

			// Just submit
			mapForm.submit();
		});
    });

	$("div[name='ga_page1'],div[name='ga_page2'],div[name='ga_page3']").on('click', function (Event) {
		// alert(123);
		let val = $(this).data('value');
		// console.log($(this).data('value'));
		$.post("Index/GameList", { val: val}, data =>{
			// console.log(data);
			$('div[id="GameList"]').html(data);

			$("div[id='GameListbnt']").on('click',  function(Event) {
				let GameId = $(this).data("value");
	
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/SellList";
	
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "GameId";
				mapInput.value = GameId;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
		});
	});

	if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Center"){
		$.post("Member/Center", {fun: "MemberTop"}, data =>{
			// console.log(data);
			var obj = JSON.parse(data);
			$('span[name="GamePoints"]').html(obj[0]);
			$('span[name="OrderNumber"]').html(obj[1]);
			$('span[name="Daifu"]').html(obj[2]);
			$('span[name="Daishowho"]').html(obj[3]);
			$('span[name="Evaluate"]').html(obj[4]);
			$('span[name="ProductNumber"]').html(obj[5]);
			$('span[name="Daishow"]').html(obj[6]);
			$('span[name="Daichu"]').html(obj[7]);
			$('span[name="ChiuHsiao"]').html(obj[8]);
		});

		$.post("Member/Center", {fun: "MemberMiddle"}, data =>{
			// console.log(data);
			var obj = JSON.parse(data);
			$('span[name="Information"]').html(obj[0]);
			$('span[name="Announcement"]').html(obj[1]);
			$('span[name="BuyEvaluate"]').html(obj[2]+'%');
			$('span[name="SellEvaluate"]').html(obj[3]+'%');
		});
	}

	if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Members_edit"){
		$.post("Member/Members_edit", {fun: "Members_editList"}, data =>{
			console.log(data);
			var obj = JSON.parse(data);
			// console.log(obj[12]);
			$('input[name="MemberAccount"]').val(obj[0]);
			$('select[name="Sex"]').val(obj[1]);
			$('input[name="IdNumber"]').val(obj[2]);
			$('input[name="RealName"]').val(obj[3]);
			$('input[name="CellPhone"]').val(obj[4]);
			$('input[name="Email"]').val(obj[5]);
			$('input[name="Address"]').val(obj[6]);
			$('select[name="IDIssuance"]').val(obj[7]);
			$('select[name="IDIssuanceplace"]').val(obj[8]);
			$('input[name="IDIssuanceDateY"]').val(obj[9]);
			$('input[name="IDIssuanceDateM"]').val(obj[10]);
			$('input[name="IDIssuanceDateD"]').val(obj[11]);
			// $('input[name="IDVerify"]').val(obj[12]);
			if(obj[12]=='0'){
				$('span[name="IDVerify0"]').attr("style","");
				$('span[name="IDVerify1"]').attr("style","display:none");
				$('span[name="IDVerify2"]').attr("style","display:none");
				$('span[name="IDVerify3"]').attr("style","display:none");
			}else if(obj[12]=='1'){
				$('span[name="IDVerify0"]').attr("style","display:none");
				$('span[name="IDVerify1"]').attr("style","");
				$('span[name="IDVerify2"]').attr("style","display:none");
				$('span[name="IDVerify3"]').attr("style","display:none");
			}else if(obj[12]=='2'){
				$('span[name="IDVerify0"]').attr("style","display:none");
				$('span[name="IDVerify1"]').attr("style","display:none");
				$('span[name="IDVerify2"]').attr("style","");
				$('span[name="IDVerify3"]').attr("style","display:none");
			}else if(obj[12]=='3'){
				$('span[name="IDVerify0"]').attr("style","display:none");
				$('span[name="IDVerify1"]').attr("style","display:none");
				$('span[name="IDVerify2"]').attr("style","display:none");
				$('span[name="IDVerify3"]').attr("style","");
			}
		});
		
		$('input[id="Membereditsubmit"]').on('click', function (Event) {
			$.post("Member/Members_edit", 
			{fun:"Members_edit",
			Sex:$('select[id="Sex"]').val(),
			IdNumber:$('input[id="IdNumber"]').val(),
			RealName:$('input[id="RealName"]').val(),
			CellPhone:$('input[id="CellPhone"]').val(),
			Email:$('input[id="Email"]').val(),
			Address:$('input[id="Address"]').val()}, data =>{
				$('div[id="popup_SentSuccess"]').css('display','inline');
				// console.log(data);
				eval(data);
			});
		});

		$('input[id="Passwordedit"]').on('click', function (Event) {
			console.log($('input[id="Oldpsw"]').val());
			console.log($('input[id="Newpsw"]').val());
			console.log($('input[id="Newpsw2"]').val());
			$.post("Member/Password_edit", {Oldpsw:$('input[id="Oldpsw"]').val(),Newpsw:$('input[id="Newpsw"]').val(),Newpsw2:$('input[id="Newpsw2"]').val()}, data =>{
				// $('div[id="popup_SentSuccess"]').css('display','inline');fun:"Password_edit",
				// console.log(data);
				eval(data);
			});
		});

		$('input[id="Paynumberedit"]').on('click', function (Event) {
			console.log($('input[id="Oldpaynumber"]').val());
			console.log($('input[id="Newpaynumber"]').val());
			console.log($('input[id="Newpaynumber2"]').val());
			$.post("Member/Paynumber_edit", {Oldpaynumber:$('input[id="Oldpaynumber"]').val(),Newpaynumber:$('input[id="Newpaynumber"]').val(),Newpaynumber2:$('input[id="Newpaynumber2"]').val()}, data =>{
				// $('div[id="popup_SentSuccess"]').css('display','inline');fun:"Password_edit",
				// console.log(data);
				eval(data);
			});
		});
		$('form[id="EditIDIssuanceForm"]').submit(function(e) {
			var formData = new FormData(this);
			formData.append('fun',"EditIDIssuance");
			$.ajax({          
				url: "Member/Members_edit",
				type: 'POST',
				data:  formData,
				mimeType:"multipart/form-data",
				contentType: false,
				cache: false,
				processData:false,
				success: function(data, textStatus, jqXHR)
				{
					e.preventDefault();
            		document.getElementById("popup_VerifyConfirm").style.display = "block";
					// console.log(data);
					eval(data);
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					$('#divLoading').hide();
				}          
			});
			e.preventDefault(); //Prevent Default action. 
		});
	}

	

// console.log(location.href.substring(location.href.lastIndexOf('/')+1));
	if (location.href.substring(location.href.lastIndexOf('/') + 1) == "index"){
		$.post("Index/NewsList", {fun:"NewsList"}, data =>{
			// console.log(data);
			$('ul[id="NewsList"]').html(data);

			$('a[id="jsNews"]').on('click', function (e) {
				e.preventDefault();
				document.getElementById('popup_News').style.display='block';
			});

			$.post("Index/NewsList", {fun:"NewsListAll"}, data =>{
				// console.log(data);
				$('div[id="NewsListAll"]').html(data);
	
				$('div[id="OpenNewsDetail"]').on('click', function (e) {
					e.preventDefault();
					//$('ul[id="diss_subs"]').attr("style","display:block");
					if ($(this).next('ul[id="diss_subs"]').css('display') == "block") {
						$(this).next('ul[id="diss_subs"]').attr("style","display:none");
					}else {
						$(this).next('ul[id="diss_subs"]').attr("style","display:block");
					}
				});
			});

		});

		$.post("Index/NewsList", {fun:"BannersList"}, data =>{
			// console.log(data);
			$('ul[id="Banner"]').html(data);
			new Splide( '#slider_cm', {
				type: 'loop', //輪播循環
				autoplay: true,   //自動播放
				interval: 3500    //播放速度
			} ).mount();
		});
	}









	
	
	// $("#GamePlatform").change( function (Event) {
	// 	// alert(123);
	// 	let GamePlatform = $(this).val();
	// 	// console.log(val);
	
	// });

	
	// $(document).on('click', 'button[id="searchmember"]', function (Event) {
		
	// 	$.post('index.php',"MemberId="+$('input[id="SearchMemberId"]').val()+"&func=SearchMember",function (data) {
	// 		console.log(data);
	// 		var obj = JSON.parse(data);
	// 		if (obj[0] == "OK") {
	// 			$('span[id="searchmemberresult"]').html(obj[1]);
	// 		}else {
	// 			eval(obj[1]);			
	// 		}
	// 	});
	// });
	
	// $(document).on('click', 'button[id="addfriend"]', function (Event) {
		
	// 	$.post('Game',"func=AddFriend&MemberID="+$(this).data('id'),function (data) {
	// 		//console.log(data);
	// 		eval(data);			
	// 	});
	// });

	// $(document).on('click', 'input[id="fd_tab1"], a[id="Friends"]', function (Event) {
	// 	console.log('input[id="fd_tab1"], a[id="Friends"]');
	// 	$.post('Game',"func=GetFriend",function (data) {
	// 		//console.log(data);
	// 		$('span[id="getfriend"]').html(data);			
	// 	});
	// });

	// $(document).on('click', 'button[id="fgivePoints"]', function (Event) {
	// 	$.post('Game',"func=GetGivePointsData&MemberId="+$(this).data('id'),function (data) {
	// 		var obj = JSON.parse(data);
	// 		if (obj[0] == 0) {
	// 			$('div[id="ConfirmGift"]').hide();
	// 			$('div[id="CancelGift"] > span').html('最少LV.50');
	// 		}
	// 		$('div[id="MyAccount"]').html(obj[1]);
	// 		$('div[id="MyPoints"]').html(obj[2]);
	// 		//console.log(obj[3]);
	// 		if (obj[3] != undefined && obj[3] != null) {
	// 			//console.log(obj[3]);
	// 			//$('input[id="ReceiveNickname"]').val(obj[4]);
	// 			$('input[id="ReceiveAccount"]').val(obj[3]);
	// 			$('input[id="ReceiveAccount"]').attr('readonly', true);
	// 		}


	// 		$('div[id="popup_givePoints"]').css('display','block');
	// 	});
	// 	//e.preventDefault();
	// });
	
	/*$(document).on('click', 'button[id="fgivePoints"]', function (Event) {
		$.post('Game',"func=GetGivePointsData&MemberId="+$(this).data('id'),function (data) {
			var obj = JSON.parse(data);
			if (obj[0] == 0) {
				$('div[id="ConfirmGift"]').hide();
				$('div[id="CancelGift"] > span').html('最少LV.50');
			}
			$('div[id="MyAccount"]').html(obj[1]);
			$('div[id="MyPoints"]').html(obj[2]);
			//console.log(obj[3]);
			if (obj[3] != undefined && obj[3] != null) {
				//console.log(obj[3]);
				$('input[id="ReceiveAccount"]').val(obj[3]);
				$('input[id="ReceiveAccount"]').attr('readonly', true);
			}


			$('div[id="popup_givePoints"]').css('display','block');
		});
		//e.preventDefault();
	});*/

	// $(document).on('click', 'a[id="givePoints"]', function (Event) {
	// 	$.post('Game',"func=GetGivePointsData&MemberId="+$(this).data('id'),function (data) {
	// 		var obj = JSON.parse(data);
	// 		if (obj[0] == 0) {
	// 			$('div[id="ConfirmGift"]').hide();
	// 			$('div[id="CancelGift"] > span').html('最少LV.50');
	// 		}
	// 		$('div[id="MyAccount"]').html(obj[1]);
	// 		$('div[id="MyPoints"]').html(obj[2]);
	// 		//console.log(obj[3]);
	// 		$('input[id="ReceiveAccount"]').val('');
	// 		$('input[id="ReceiveAccount"]').attr('readonly', false);


	// 		$('div[id="popup_givePoints"]').css('display','block');
	// 	});
	// 	//e.preventDefault();
	// });

	/*$(document).on('blur', 'input[id="ReceiveAccount"]', function (Event) {

		console.log($('input[id="ReceiveAccount"]').val().length);
		if ($('input[id="ReceiveAccount"]').val().length == 7) {

		}

	});*/

	// $(document).on('click', 'div[id="ConfirmGift"]', function (Event) {

	// 	$.post('Game',"func=GetIsExist&Account="+$('input[id="ReceiveAccount"]').val(),function (data) {
	// 		var obj = JSON.parse(data);
	// 		if (obj[0] == 0) {
	// 			alert('請輸入正確對方ID');	
	// 			return false;
	// 		}else {
	// 			$('input[id="ReceiveNickname"]').val(obj[1])

	// 			var a = $.confirm({
	// 				icon: 'fa fa-question-circle-o',
	// 				theme: 'supervan',
	// 				closeIcon: true,
	// 				animation: 'scale',
	// 				type: 'orange',
	// 				title: '確認要贈點?',
	// 				content: '您要贈送'+$('input[id="TransferPoints"]').val()+'點給玩家'+$('input[id="ReceiveNickname"]').val()+'[ID:'+$('input[id="ReceiveAccount"]').val()+']? 手續費'+($('input[id="TransferPoints"]').val()*0.03) +'點',
	// 				buttons: {
	// 					確定: function () {
	// 						console.log($('div[id="divLoading"]'));
	// 						$('div[id="divLoading"]').show();
	// 						$.post('Game',"func=TransferPoints&MemberAccount="+$('input[id="ReceiveAccount"]').val()+"&TransferPoints="+$('input[id="TransferPoints"]').val(),function (data) {
	// 							//console.log(data);
	// 							eval(data);
	// 							$('div[id="divLoading"]').hide();
	// 						}).fail(function(xhr, status, error) {
	// 							console.log(xhr.responseText);
	// 							$('div[id="divLoading"]').hide();
	// 						});
	// 					},
	// 					取消: function () {
	// 						a.close();
	// 						//$.alert('Canceled!');
	// 						//return false;
	// 					}
	// 				}
	// 			});
	// 		}
	// 	})
	// 	/*if ($('input[id="ReceiveAccount"]').val().length != 7) {
	// 		alert('請輸入正確對方ID');	
	// 		return false;
	// 	}*/
	// 	//console.log(123);
		
	// });

	// $(document).on('click', 'button[id="private"]', function (Event) {
	// 	console.log($(this).data('mid'));
	// 	$('button[id="prisendchat"]').data('aimid',$(this).data('aimid'));
	// 	var sendData = '{"Motion":"ResPriChat","MemberId":"'+$(this).data('id')+'","PriMemberId":"'+$(this).data('aimid')+'"}';
	// 		socket.send(sendData);
	// });

	// $(document).on('keypress', 'form[id="prichatform"]', function (Event) {
	// 	console.log('Event.which = '+Event.which);
	// 	if ( Event.which == 13 )
	// 	{
	// 		$('button[id="prisendchat"]').click();
	// 	}
	// });

	// $(document).on('click', 'button[id="prisendchat"]', function (Event) {
	// 	console.log('prisendchat=>'+$(this).data('aimid'));
	// 	if ($('input[id="PriSendMessage"]').val().length > 0) { 
	// 		var sendData = '{"Motion":"PriSendChat","MemberId":"'+$(this).data('mid')+'","PriMemberId":"'+$(this).data('aimid')+'","Message":"'+$('input[id="PriSendMessage"]').val()+'"}';
	// 		socket.send(sendData);
	// 		$('input[id="PriSendMessage"]').val("");
	// 	}else {
	// 		alert('請輸入聊天文字');
	// 	}
	// })
	//黑名單
	// $(document).on('click', 'input[id="fd_tab3"]', function (Event) {
	// 	console.log('input[id="fd_tab3"]');
	// 	//$.post('Game',"func=GetInvite",function (data) {
	// 	$.post('Game',"func=GetBlack",function (data) {	
	// 		//console.log(data);
	// 		$('span[id="getinvite"]').html(data);			
	// 	});
	// });

	// $(document).on('click', 'button[id="addblack"]', function (Event) {
	// 	$.post('Game',"func=AddBlack&MemberID="+$(this).data('id'),function (data) {
	// 		//console.log(data);
	// 		eval(data);			
	// 	});
	// });

	// $(document).on('click', 'button[id="cancelblack"]', function (Event) {
	// 	$.post('Game',"func=CancelBlack&MemberID="+$(this).data('id'),function (data) {
	// 		//console.log(data);
	// 		eval(data);			
	// 	});
	// });

	// $(document).on('click', 'button[id="agreefriend"]', function (Event) {
	// 	$.post('Game',"func=AgreeFriend&MemberID="+$(this).data('id'),function (data) {
	// 		//console.log(data);
	// 		eval(data);
	// 		$.post('Game',"func=GetInvite",function (data) {
	// 			//console.log(data);
	// 			$('span[id="getinvite"]').html(data);			
	// 		});
	// 	});
	// });

	// $(document).on('click', 'a[id="Activity"]', function (Event) {
	// 	$('div[id="popup_activitymain"]').css('display','block');
	// 	/*$('div[id="popup_activity"]').css('display','block');
	// 	$('div[id="popup_activity2"]').css('display','block');
	// 	$('div[id="popup_activity3"]').css('display','block');
	// 	$('div[id="popup_activity4"]').css('display','block');*/
	// });

	// $(document).on('click', 'button[id="activityshow"]', function (Event) {

	// 	if ($(this).data('id') == 1) {
	// 		$('div[id="popup_activity3"]').css('display','block');
	// 	}else if ($(this).data('id') == 2) {
	// 		$('div[id="popup_activity4"]').css('display','block');
	// 	}else if ($(this).data('id') == 3) {
	// 		$('div[id="popup_activity"]').css('display','block');
	// 	}else if ($(this).data('id') == 4) {
	// 		$('div[id="popup_activity2"]').css('display','block');
	// 	}
	// 	/*$('div[id="popup_activity"]').css('display','block');
	// 	$('div[id="popup_activity2"]').css('display','block');
	// 	$('div[id="popup_activity3"]').css('display','block');
	// 	$('div[id="popup_activity4"]').css('display','block');*/
	// });

	//快閃活動排名
	// $(document).on('click', 'button[id="activityinto"]', function (Event) {
		
	// 	/*if ($(this).data('range') == 1) {
	// 		$('div[id="JuneRankTitle"]').html('健康區排名');
	// 	}
	// 	if ($(this).data('range') == 2) {
	// 		$('div[id="JuneRankTitle"]').html('財富區排名');
	// 	}*/
	// 	$('div[id="JuneRankTitle"]').html('快閃得分王(健康區)排名');
	// 	$('div[id="popup_activityinto"]').css('display','block');
	// 	//console.log('input[id="fd_tab3"]');
	// 	$('tbody[id="Ranking"]').html('');
	// 	$('div[id="divLoading2"]').show();
	// 	$.post('Game',"func=ActivityInto&range="+$(this).data('range'),function (data) {
	// 		$('div[id="divLoading2"]').hide();
	// 		var obj = JSON.parse(data);
	// 		//console.log("2"+obj[1]);
			
	// 		$('span[id="UpdateTime"]').html(obj[0]);
	// 		$('tbody[id="Ranking"]').html(obj[1]);			
	// 	});
	// });
	//6月份活動排名
	/*$(document).on('click', 'button[id="activityinto4"]', function (Event) {
		$('tbody[id="Ranking22"]').html('');
		$('div[id="popup_activityinto4"]').css('display','block');
		//console.log('button[id="activityinto3"]');
		$('div[id="divLoading2"]').show();
		$.post('Game',"func=ActivityInto4",function (data) {
			//console.log(data);
			$('div[id="divLoading2"]').hide();
			var obj = JSON.parse(data);
			$('span[id="UpdateTime2"]').html(obj[0]);
			$('tbody[id="Ranking22"]').html(obj[1]);			
		});
	});*/

	//1月份活動排名
	// $(document).on('click', 'button[id="activityinto2"]', function (Event) {

	// 	//console.log($(this).data('type'));
	// 	/*if ($(this).data('type') == 1) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第一週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第一週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 2) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第二週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第二週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 3) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第三週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第三週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 4) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第四週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第四週 財富區排名');
	// 		}
	// 	}

	// 	if ($(this).data('type') == 5) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('月冠軍 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('月冠軍 財富區排名');
	// 		}
	// 	}*/
	// 	if ($(this).data('range') == 1) {
	// 		$('div[id="JuneRankTitle"]').html('健康區排名');
	// 	}else {
	// 		$('div[id="JuneRankTitle"]').html('財富區排名');
	// 	}

	// 	$('span[id="UpdateTime"]').html('');
	// 	$('tbody[id="Ranking"]').html('');
	// 	$('div[id="popup_activityinto2"]').css('display','block');
	// 	$('input[class="popbtn_close"]').hide();
	// 	//console.log('input[id="fd_tab3"]');
	// 	$('div[id="divLoading2"]').show();
	// 	$.post('Game',"func=ActivityInto2&type="+$(this).data('type')+"&range="+$(this).data('range'),function (data) {
	// 		//console.log(data);
	// 		$('div[id="divLoading2"]').hide();
	// 		$('input[class="popbtn_close"]').show();
	// 		var obj = JSON.parse(data);
	// 		$('span[id="UpdateTime"]').html(obj[0]);
	// 		$('tbody[id="Ranking"]').html(obj[1]);
	// 		//$('tbody[id="Ranking22"]').html(obj[2]);			
	// 	});
	// });

	//12月份活動排名
	// $(document).on('click', 'button[id="activityinto3"]', function (Event) {
	// 	if ($(this).data('range') == 1) {
	// 		$('div[id="JuneRankTitle"]').html('健康區排名');
	// 	}else {
	// 		$('div[id="JuneRankTitle"]').html('財富區排名');
	// 	}
	// 	//console.log($(this).data('type'));
	// 	/*if ($(this).data('type') == 1) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第一週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第一週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 2) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第二週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第二週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 3) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第三週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第三週 財富區排名');
	// 		}
	// 	}
	// 	if ($(this).data('type') == 4) {
	// 		if ($(this).data('range') == 1) {
	// 			$('div[id="JuneRankTitle"]').html('第四週 健康區排名');
	// 		}else {
	// 			$('div[id="JuneRankTitle"]').html('第四週 財富區排名');
	// 		}
	// 	}*/

	// 	$('span[id="UpdateTime"]').html('');
	// 	$('tbody[id="Ranking"]').html('');
	// 	$('div[id="popup_activityinto3"]').css('display','block');
	// 	//console.log('input[id="fd_tab3"]');
	// 	$('div[id="divLoading2"]').show();
	// 	$.post('Game',"func=ActivityInto3&type="+$(this).data('type')+"&range="+$(this).data('range'),function (data) {
	// 		//console.log(data);
	// 		$('div[id="divLoading2"]').hide();
	// 		var obj = JSON.parse(data);
	// 		$('span[id="UpdateTime"]').html(obj[0]);
	// 		$('tbody[id="Ranking"]').html(obj[1]);
	// 		//$('tbody[id="Ranking22"]').html(obj[2]);			
	// 	});
	// });

	// $(document).on('click', 'button[id="giftpointrecord"]', function (Event) {
		
	// 	$('div[id="popup_giftpointrecord"]').css('display','block');
	// 	//console.log('input[id="fd_tab3"]');
	// 	$.post('Game',"func=GiftPointRecord&tab=gift",function (data) {
	// 		console.log(data);
	// 		var obj = JSON.parse(data);
	// 		$('tbody[id="giftpointrecord"]').html(obj[0]);			
	// 	});
	// });
	// $(document).on('click', 'button[id="receivepointrecord"]', function (Event) {
		
	// 	$('div[id="popup_giftpointrecord"]').css('display','block');
	// 	//console.log('input[id="fd_tab3"]');
	// 	$.post('Game',"func=GiftPointRecord&tab=receive",function (data) {
	// 		console.log(data);
	// 		var obj = JSON.parse(data);
	// 		$('tbody[id="giftpointrecord"]').html(obj[0]);			
	// 	});
	// });


	// $(document).on('click', 'a[id="Player"]', function (Event) {
	// 	$.post('Game',"func=GetPlayer",function (data) {
	// 		console.log(data);
	// 		$('div[id="popup_Player"]').css('display','block');
	// 		$('span[id="PlayerData"]').html(data);			
	// 	});
	// });

	// $(document).on('click', 'a[id="WinChat"]', function (Event) {
	// 	/*$.post('Game',"func=Chat",function (data) {
	// 		eval(data);
	// 	});*/
	// 	$('div[id="popup_chat"]').css('display','block');
	// 	//console.log('WebSocket2 - status ' + socket.readyState);
	// 	var sendData = '{"Motion":"Chat","MemberId":"'+$(this).data('mid')+'"}';
	// 	socket.send(sendData);
		
	// 	$('div[class="content_set"]').scrollTop(100000000);
	// });

	// $('form[id="chatform"]').on('keypress', function (Event) {
	// 	if ( Event.which == 13 )
	// 	{
	// 		$('button[id="sendchat"]').click();
	// 	}
	// });
	// $(document).on('click', 'button[id="sendchat"]', function (Event) {
	// 	if ($('input[id="SendMessage"]').val().length > 0) { 
	// 		var sendData = '{"Motion":"SendChat","MemberId":"'+$(this).data('mid')+'","Message":"'+$('input[id="SendMessage"]').val()+'"}';
	// 		socket.send(sendData);
	// 		$('input[id="SendMessage"]').val("");
	// 	}else {
	// 		alert('請輸入聊天文字');
	// 	}
	// })

	// $(document).on('click', 'a[id="MemberCenter"]', function (Event) {
	// 	$.post('Game',"func=GetFeedbackPointsData&MemberId="+$(this).data('id'),function (data) {
	// 		var obj = JSON.parse(data);

	// 		$('div[id="popup_MemberCenter"]').css('display','block');

	// 		$('div[id="MemberId"]').html(obj[1]);
	// 		$('div[id="Push"]').html(obj[5]);
	// 		$('div[id="NickName"]').html(obj[3]);
	// 		$('div[id="Level"]').html("LV."+obj[4]);
	// 		$('span[id="FeedbackPoints"]').html(obj[2]);

	// 		$('button[id="transferinto"]').data('id',obj[1]);
	// 		/*if (obj[0] == 0) {
	// 			$('div[id="ConfirmGift"]').hide();
	// 			$('div[id="CancelGift"] > span').html('最少LV.50');
	// 		}
	// 		$('div[id="MyAccount"]').html(obj[1]);
	// 		$('div[id="MyPoints"]').html(obj[2]);
	// 		//console.log(obj[3]);
	// 		$('input[id="ReceiveAccount"]').val('');
	// 		$('input[id="ReceiveAccount"]').attr('readonly', false);


	// 		$('div[id="popup_givePoints"]').css('display','block');*/
	// 	});
	// 	//e.preventDefault();
	// });

	// $(document).on('click', 'button[id="transferinto"]', function (Event) {
	// 	var a = $.confirm({
	// 		icon: 'fa fa-question-circle-o',
	// 		theme: 'supervan',
	// 		closeIcon: true,
	// 		animation: 'scale',
	// 		type: 'orange',
	// 		title: '確認要將回饋金轉成點數?',
	// 		content: '將回饋金'+$('span[id="FeedbackPoints"]').html()+'轉成點數?',
	// 		buttons: {
	// 			確定: function () {
	// 				$.post('Game',"func=FeedbackIntoPoints",function (data) {
	// 					console.log(data);
	// 					eval(data);
	// 				}).fail(function(xhr, status, error) {
	// 					console.log(xhr.responseText);
	// 				});
	// 			},
	// 			取消: function () {
	// 				a.close();
	// 				//$.alert('Canceled!');
	// 				//return false;
	// 			}
	// 		}
	// 	});
	// });

	// $('a[id="change-send"]').on('click', function() {
	// //$(document).on('click', 'a[id="change-send"]', function (Event) {
	// 	//alert("a"+$('select[id="Proxy"] :selected').val())
	// 	if ($('input[id="Withdraw"]').val() < 10) {
	// 		alert('Reciclar al menos 10 puntos');
	// 		return false;
	// 	}else if ($('select[id="Proxy"] :selected').val() == "") {
	// 		alert('Por favor seleccione un agente');
	// 		return false;
	// 	}else {
	// 		var id = $(this).attr('id');
	// 		$('#hidden-content3').css('display', 'none');
	// 		$('.fancybox-bg').css('display', 'none');
			
	// 		$('input[id="ConfirmWithdraw"]').val($('input[id="Withdraw"]').val());
	// 		$('input[id="ConfirmProxy"]').val($('select[id="Proxy"] :selected').html());
	// 	}
	// });
	
	// $('a[id="change-send2"]').on('click', function() {
	// 	$.post("Game/index.php","Withdraw="+$('input[id="Withdraw"]').val()+"&ProxySno="+$('select[id="Proxy"] :selected').val()+"&func=Reclaim",function (data){
	// 		console.log(data);
	// 		eval(data);
	// 		//$('#LoginVerify').attr('src','img.php');
	// 	});
	// });
	
	// $(document).on('click', 'a[id="ChangePassword"]', function (Event) {
		
	// 	$.post("Game/index.php","OldPassword="+$('input[id="OldPassword"]').val()+"&NewPassword="+$('input[id="NewPassword"]').val()+"&ConfirmPassword="+$('input[id="ConfirmPassword"]').val()+"&func=ChangePassword",function (data){
			
	// 		//console.log(data);
	// 		eval(data);
	// 		//$('#LoginVerify').attr('src','img.php');
	// 	});
	// });

	// $(document).on('click', 'div[class="dp_pay"] > a', function (Event) {

	// 	$('div[class="dp_pay"] > a').removeClass("selected");
	// 	$(this).addClass( "selected");
	// 	//console.log($(this).index());
	// 	paycanal = $(this).index();

	// 	$('input[id="paycanal"]').val(paycanal);
	// });

	// $(document).on('click', 'div[class="dp_set"]', function (Event) {

	// 	$('div[class="dp_info"] > div').removeClass("selected");
	// 	$(this).addClass("selected");
	// 	console.log($(this).index());

	// 	switch ($(this).index()) {
	// 		case 1:
	// 			payamount = 100;
	// 			break;
	// 		case 2:
	// 			payamount = 300;
	// 			break;
	// 		case 3:
	// 			payamount = 500;
	// 			break;
	// 		case 4:
	// 			payamount = 1000;
	// 			break;
	// 		case 5:
	// 			payamount = 3000;
	// 			break;
	// 		case 6:
	// 			payamount = 5000;
	// 			break;
	// 		case 7:
	// 			payamount = 10000;
	// 			break;
	// 	}
	// 	//paycanal = $(this).index();

	// 	$('input[id="payamount"]').val(payamount);
	// });

	// $(document).on('submit', 'form[id="CardForm"]', function (Event) {
	// 	$('button[id="CardSubmit"]').hide();
	// });

	// $(document).on('submit', 'form[id="StoreForm"]', function (Event) {
	// 	$('button[id="StoreSubmit"]').hide();
	// });
	// function ShowExchange() {
	// 	//console.log(Number.isInteger(parseInt($('input[id="BuyAmount"]').val())));
	// 	if ($('select[id="BuyCurrency"] :checked').val() != $('select[id="SellCurrency"] :checked').val() && Number.isInteger(parseInt($('input[id="BuyAmount"]').val()))) {
	// 		//console.log('data');
	// 		$.post('../Exchange/admin.php?func=ReadRates',"Currency1="+$('select[id="BuyCurrency"] :checked').val()+"&"+"Currency2="+$('select[id="SellCurrency"] :checked').val()+"&BuyAmount="+$('input[id="BuyAmount"]').val(),function (data){
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				$('label[id="ShowExchange"]').html("<span style='font-weight:bold;color: #5239FF;font-size:20px;'>"+obj[2]+"</span><br/><span style='font-size:16px;'>匯率:"+obj[1]+"</span>");
	// 				console.log(obj[2]);
	// 			}else {
	// 				//console.log(obj[2]);
				
	// 				ErrorMsg(obj[2]);
	// 			}
	// 		});
	// 	}
	// 	console.log($('select[id="BuyCurrency"] :checked').val());
	// }
	// $(document).on('click', 'a[id="ApplyExchange"]', function (Event) {
	// 	$.post('../Exchange/admin.php?func=Demand',$('form[id="formdata"]').serialize(),function (data){
	// 		//console.log(data);
	// 		try {
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				//eval(data);
	// 				new jBox('Notice', {
	// 					autoClose: 3000,
	// 					position: {
	// 						x: 'center',
	// 						y: 'center'
	// 					},
	// 					stack: true,
	// 					animation: {
	// 						open: 'tada',
	// 						close: 'zoomIn'
	// 					},
	// 					content: obj[1],
	// 					color: 'blue',
	// 					onCloseComplete: function() {
	// 						$('#dialogBox').removeClass('active');
	// 						$('a[data-page="demand"]').click();
	// 					}
	// 				});
				
	// 			}else {
	// 				eval(data);
	// 			}
	// 		} catch (e) {
	// 			eval(data);
	// 		}
			
	// 	});
	// });
	
	// $(document).on('click', 'a[id="AddRates"]', function (Event) {
	// 	//var filedata = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
	// 	//console.log('filedata');
	// 	$.post('../Exchange/admin.php?func=Rates',$('form[id="formdata"]').serialize()+"&SubFunc=AddRates",function (data){
	// 		//console.log(data);
	// 		try {
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				//eval(data);
	// 				new jBox('Notice', {
	// 					autoClose: 3000,
	// 					position: {
	// 						x: 'center',
	// 						y: 'center'
	// 					},
	// 					stack: true,
	// 					animation: {
	// 						open: 'tada',
	// 						close: 'zoomIn'
	// 					},
	// 					content: obj[1],
	// 					color: 'blue',
	// 					onCloseComplete: function() {
	// 						$('#dialogBox').removeClass('active');
	// 						$('a[data-page="Rates"]').click();
	// 					}
	// 				});
				
	// 			}else {
	// 				eval(data);
	// 			}
	// 		} catch (e) {
	// 			eval(data);
	// 		}
	// 	});
	// });
	
	// $(document).on('click', 'a[id="RateLimitSave"]', function (Event) {
	// 	$.post('../Exchange/admin.php?func=RateLimit',$('form[id="formdata"]').serialize(),function (data){
	// 		console.log(data);
	// 		eval(data);
	// 	});
	// });
	// $(document).on('click', 'a[id="AddedController"]', function (Event) {
	// 	$.post('../admin/admin.php?func=AddedAdmin',$('form[id="formdata"]').serialize(),function (data){
	// 		try {
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				//eval(data);
	// 				new jBox('Notice', {
	// 					autoClose: 3000,
	// 					position: {
	// 						x: 'center',
	// 						y: 'center'
	// 					},
	// 					stack: true,
	// 					animation: {
	// 						open: 'tada',
	// 						close: 'zoomIn'
	// 					},
	// 					content: obj[1],
	// 					color: 'blue',
	// 					onCloseComplete: function() {
	// 						$('#dialogBox').removeClass('active');
	// 						$('a[data-page="Controller"]').click();
	// 					}
	// 				});
				
	// 			}else {
	// 				eval(data);
	// 			}
	// 		} catch (e) {
	// 			eval(data);
	// 		}
	// 	});
	// });
	
	// $(document).on('click', 'a[id="SaveController"]', function (Event) {
	// 	$.post('../admin/admin.php?func=ModifyAdmin',$('form[id="formdata"]').serialize(),function (data){
	// 		try {
	// 			//console.log(data);
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				//eval(data);
	// 				new jBox('Notice', {
	// 					autoClose: 3000,
	// 					position: {
	// 						x: 'center',
	// 						y: 'center'
	// 					},
	// 					stack: true,
	// 					animation: {
	// 						open: 'tada',
	// 						close: 'zoomIn'
	// 					},
	// 					content: obj[1],
	// 					color: 'blue',
	// 					onCloseComplete: function() {
	// 						$('#dialogBox').removeClass('active');
	// 						$('a[data-page="Controller"]').click();
	// 					}
	// 				});
				
	// 			}else {
	// 				eval(data);
	// 			}
	// 		} catch (e) {
	// 			eval(data);
	// 		}
	// 	});
	// });
	// $(document).on('click', 'a[id="Added"]', function (Event) {
	// 	//var filedata = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
	// 	//console.log('filedata');
	// 	$.post('../Client/admin.php?func=Added',$('form[id="formdata"]').serialize(),function (data){
			
	// 		try {
	// 			var obj = JSON.parse(data);
	// 			if (obj[0] == "OK") {
	// 				//eval(data);
	// 				new jBox('Notice', {
	// 					autoClose: 3000,
	// 					position: {
	// 						x: 'center',
	// 						y: 'center'
	// 					},
	// 					stack: true,
	// 					animation: {
	// 						open: 'tada',
	// 						close: 'zoomIn'
	// 					},
	// 					content: obj[1],
	// 					color: 'blue',
	// 					onCloseComplete: function() {
	// 						$('#dialogBox').removeClass('active');
	// 						$('a[data-page="ClientManage"]').click();
	// 					}
	// 				});
				
	// 			}else {
	// 				eval(data);
	// 			}
	// 		} catch (e) {
	// 			eval(data);
	// 		}
	// 	});
		
	// });
	
});
function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function cc_format(value) {
  var v = value.replace(/\s+/g, '').replace(/[^0-9A-Z]/gi, '')
  var matches = v.match(/\w{4,16}/g);
  var match = matches && matches[0] || ''
  var parts = []
  for (i=0, len=match.length; i<len; i+=4) {
    parts.push(match.substring(i, i+4))
  }
  if (parts.length) {
    return parts.join('-')
  } else {
    return value
  }
}

function ErrorMsg (Msg) {
	new jBox('Notice', {
		autoClose: 3000,
		position: {
			x: 'center',
			y: 'center'
		},
		stack: true,
		animation: {
			open: 'tada',
			close: 'zoomIn'
		},
		content: Msg,
		color: 'red',
	});
}