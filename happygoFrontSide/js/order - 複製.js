
$(function() { 


    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "AddOrder") {
        $.post("Order/AddOrder", { fun: "ProductId" }, data => {
            // console.log(data);
            // alert(123);
            let obj = JSON.parse(data);
            if (obj.ErrorCode) {
                alert(obj.ErrorText);
            } else {
                for(let i=0;i<obj.length;i++){
                    $("select[id='ProductId']").append(
                    `<option value="${obj[i].ProductId}">${obj[i].ProductName}</option>`
                    );
                }
            }
        });
    
        $.post("Order/AddOrder", { fun: "GameName" }, data => {
            // console.log(data);
            // alert(123);
            let obj = JSON.parse(data);
            if (obj.ErrorCode) {
                alert(obj.ErrorText);
            } else {
                for(let i=0;i<obj.length;i++){
                    $("select[id='GameName']").append(
                    `<option value="${obj[i].GameId}">${obj[i].GameName}</option>`
                    );
                }
            }
        });

        $.post("Order/AddOrder", { fun: "OrderId", val: "2"}, data =>{
            // console.log(data);
            let obj = JSON.parse(data);
            // console.log(obj);
            if (obj.ErrorCode) {
                alert(obj.ErrorText);
            } else {
                $('span[id="ProductNumber"]').html(obj);
                $('input[name="ProductNumber"]').val(obj.join(''));
            }
        });

        // $.post("Order/AddOrder",data =>{
        //     console.log(data);
        //     eval(data);
        // });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_sell") {
        $.post("Member/Order_sell", data =>{
            // console.log(data);
            $('tbody[id="OrderSellForm"]').html(data);

            $("input[id='OrderSellInfo']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	            console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/OrderSellInfo";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
            $("input[id='OrderSellEvalu']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);

                Event.preventDefault();
                document.getElementById("popup_BuyComment").style.display = "block";
                
                $("input[id='ConfirmSellEvalu']").on('click',  function(Event) {
                    console.log(OrderNumber);
                    console.log($('input[name="members"]:checked').val());
                    console.log($('textarea[id="OrderSellEvaluInfo"]').val());
                    $.post("Order/OrderSellInfo", {fun:"OrderSellEvaluInsert", OrderNumber:OrderNumber, Evalu:$('input[name="members"]:checked').val(), EvaluInfo:$('textarea[id="OrderSellEvaluInfo"]').val()}, data =>{
                        eval(data);
                    });
                });
			});
            $("input[id='OrderSellDel']").on('click',  function(Event) {
                let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);
                Event.preventDefault();
                document.getElementById("popup_SellCanclePay").style.display = "block";
            });
        });
    }
    
    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_buy") {
        $.post("Member/Order_buy", data =>{
            // console.log(data);
            $('tbody[id="OrderBuyForm"]').html(data);
            // $.getScript("js/all_popup.js");

            $("input[id='Paynow']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	            console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/Shopping_cart";
                // mapForm.fun= "Shopping_cart";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
            $("input[id='OrderBuyInfo']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/OrderBuyInfo";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
            $("input[id='OrderBuyEvalu']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);

                Event.preventDefault();
                document.getElementById("popup_BuyComment").style.display = "block";
                
                $("input[id='ConfirmBuyEvalu']").on('click',  function(Event) {
                    console.log(OrderNumber);
                    console.log($('input[name="members"]:checked').val());
                    console.log($('textarea[id="OrderBuyEvaluInfo"]').val());
                    $.post("Order/OrderBuyInfo", {fun:"OrderBuyEvaluInsert", OrderNumber:OrderNumber, Evalu:$('input[name="members"]:checked').val(), EvaluInfo:$('textarea[id="OrderBuyEvaluInfo"]').val()}, data =>{
                        eval(data);
                    });
                });
			});
            $("input[id='OrderBuyDel']").on('click',  function(Event) {
                let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);
                Event.preventDefault();
                document.getElementById("popup_CanclePay").style.display = "block";
            }); 
        });    
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet") {
        $.post("Member/Wallet", data =>{
            // console.log(data);
            $('tbody[id="WalletForm"]').html(data);
        });
    }
    
    //$("#GameCurrency,#GameAccount,#GamePointCard,#Gameprops,#GameMall,#GameGift,#GameOther label").on('click', function (Event) {
    $("label[id='GameCurrency'],label[id='GameAccount'],label[id='GamePointCard'],label[id='Gameprops'],label[id='GameMall'],label[id='GameGift'],label[id='GameOther']").on('click', function (Event) {
        let val = $(this).data('value');
        console.log($(this).data('value'));
        // console.log($('input[name="panel_radio"]').val());
        $.post("Order/AddOrder", { fun: "OrderId", val: val}, data =>{
            // console.log(data);
            let obj = JSON.parse(data);
            // console.log(obj);
            if (obj.ErrorCode) {
                alert(obj.ErrorText);
            } else {
                $('span[id="ProductNumber"]').html(obj);
                $('input[name="ProductNumber"]').val(obj.join(''));
            }
        });
    });

    $("label[id='Order_sell1'],label[id='Order_sell2'],label[id='Order_sell3'],label[id='Order_sell4'],label[id='Order_sell5']").on('click', function (Event) {
        let val = $(this).data('value');
        console.log($(this).data('value'));
        // console.log($('input[name="panel_radio"]').val());
        $.post("Member/Order_sell", {val: val}, data =>{
            $('tbody[id="OrderSellForm"]').html(data);

            $("input[id='OrderSellInfo']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/OrderSellInfo";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
        });
        $("input[id='OrderSellEvalu']").on('click',  function(Event) {
            let OrderNumber = $(this).data("value");
            sessionStorage.setItem('session_OrderNumber', OrderNumber);
            console.log(OrderNumber);

            Event.preventDefault();
            document.getElementById("popup_BuyComment").style.display = "block";
            
            $("input[id='ConfirmSellEvalu']").on('click',  function(Event) {
                console.log(OrderNumber);
                console.log($('input[name="members"]:checked').val());
                console.log($('textarea[id="OrderSellEvaluInfo"]').val());
                $.post("Order/OrderSellInfo", {fun:"OrderSellEvaluInsert", OrderNumber:OrderNumber, Evalu:$('input[name="members"]:checked').val(), EvaluInfo:$('textarea[id="OrderSellEvaluInfo"]').val()}, data =>{
                    eval(data);
                });
            });
        });
        $("input[id='OrderSellDel']").on('click',  function(Event) {
            let OrderNumber = $(this).data("value");
            sessionStorage.setItem('session_OrderNumber', OrderNumber);
            console.log(OrderNumber);
            Event.preventDefault();
            document.getElementById("popup_SellCanclePay").style.display = "block";
        });
    });

    $("label[id='Order_buy1'],label[id='Order_buy2'],label[id='Order_buy3'],label[id='Order_buy4'],label[id='Order_buy5']").on('click', function (Event) {
        let val = $(this).data('value');
        console.log($(this).data('value'));
        // console.log($('input[name="panel_radio"]').val());
        $.post("Member/Order_buy", {val: val}, data =>{
            $('tbody[id="OrderBuyForm"]').html(data);

            $("input[id='Paynow']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	// console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/Shopping_cart";
                // mapForm.fun= "Shopping_cart";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
            $("input[id='OrderBuyInfo']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
	console.log(OrderNumber);
				var mapForm = document.createElement("form");
				// mapForm.target = "open";    
				mapForm.method = "POST";
				mapForm.action = "Order/OrderBuyInfo";
				// Create an input
				var mapInput = document.createElement("input");
				mapInput.type = "hidden";
				mapInput.name = "OrderNumber";
				mapInput.value = OrderNumber;
	
				// Add the input to the form
				mapForm.appendChild(mapInput);
	
				// Add the form to dom
				document.body.appendChild(mapForm);
	
				// Just submit
				mapForm.submit();
			});
            $("input[id='OrderBuyEvalu']").on('click',  function(Event) {
				let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);

                Event.preventDefault();
                document.getElementById("popup_BuyComment").style.display = "block";
                
                $("input[id='ConfirmBuyEvalu']").on('click',  function(Event) {
                    console.log(OrderNumber);
                    console.log($('input[name="members"]:checked').val());
                    console.log($('textarea[id="OrderBuyEvaluInfo"]').val());
                    $.post("Order/OrderBuyInfo", {fun:"OrderBuyEvaluInsert", OrderNumber:OrderNumber, Evalu:$('input[name="members"]:checked').val(), EvaluInfo:$('textarea[id="OrderBuyEvaluInfo"]').val()}, data =>{
                        eval(data);
                    });
                });
			});
            $("input[id='OrderBuyDel']").on('click',  function(Event) {
                let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', OrderNumber);
                console.log(OrderNumber);
                Event.preventDefault();
                document.getElementById("popup_CanclePay").style.display = "block";
            }); 
        });
    });

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "OrderBuyInfo") {
        $.post("Order/OrderBuyInfo", {fun:"OrderBuyInfo",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            var obj = JSON.parse(data);
            // console.log(data);
            $('span[name="MemberId"]').html(obj[0]);
			$('span[name="ProductTitle"]').html(obj[1]);
			$('span[name="ProductNumber"]').html(obj[2]);
			$('span[name="OrderNumber"]').html(obj[3]);
			$('span[name="MemberId"]').html(obj[4]);
			$('span[name="CreateDate"]').html(obj[5]);
			$('span[name="GamePlatform"]').html(obj[6]);
			$('span[name="TypeName"]').html(obj[7]);
			$('span[name="PointCardKind"]').html(obj[8]);
            $('span[name="SumPrice"]').html(obj[9]);
            $('span[name="SumPricePlusHand"]').html(obj[10]);
            $('span[name="PaymentMethod"]').html(obj[11]);
            $('span[name="State"]').html(obj[12]);
        });
        $.post("Order/OrderBuyInfo", {fun:"OrderBuyQA",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            // console.log(data);
            $('div[id="BuyQAInfo"]').html(data);
        });
        $('input[id="edit_confirm"]').on('click', function (Event) {
            // console.log($('textarea[id="BuyQAtext"]').val());
            // $('textarea[id="BuyQAtext"]').val();
            $.post("Order/OrderBuyInfo", {fun:"OrderBuyQAInsert",OrderNumber:sessionStorage.getItem('session_OrderNumber'),QAtext:$('textarea[id="BuyQAtext"]').val()}, data =>{
                // console.log(data);
                eval(data);
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "OrderSellInfo") {
        $.post("Order/OrderSellInfo", {fun:"OrderSellInfo",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            var obj = JSON.parse(data);
            // console.log(data);
            $('span[name="MemberId"]').html(obj[0]);
			$('span[name="ProductNumber"]').html(obj[1]);
            $('span[name="OrderNumber"]').html(obj[2]);
			$('span[name="TypeName"]').html(obj[3]);
			$('span[name="GamePlatform"]').html(obj[4]);
			$('span[name="ProductTitle"]').html(obj[5]);
			$('span[name="PointCardKind"]').html(obj[6]);
			$('span[name="OrderState"]').html(obj[7]);
			$('span[name="PaymentMethod"]').html(obj[8]);
			$('span[name="SumPrice"]').html(obj[9]);
            // $('span[name="SumPricePlusHand"]').html(obj[10]);
            $('span[name="CreateDate"]').html(obj[11]);
            $('div[name="State"]').attr('class',obj[12]);
        });
        $.post("Order/OrderSellInfo", {fun:"OrderSellQA",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            // console.log(data);
            $('div[id="SellQAInfo"]').html(data);
        });
        $('input[id="edit_confirm"]').on('click', function (Event) {
            console.log($('textarea[id="BuyQAtext"]').val());
            $('textarea[id="BuyQAtext"]').val();
            $.post("Order/OrderSellInfo", {fun:"OrderSellQAInsert",OrderNumber:sessionStorage.getItem('session_OrderNumber'),QAtext:$('textarea[id="BuyQAtext"]').val()}, data =>{
                // console.log(data);
                eval(data);
            });
        });
    }

    $("label[id='Wallet9'],label[id='Wallet1'],label[id='Wallet2'],label[id='Wallet3'],label[id='Wallet4'],label[id='Wallet5'],label[id='Wallet6']").on('click', function (Event) {
        let val = $(this).data('value');
        console.log($(this).data('value'));
        // console.log($('input[name="panel_radio"]').val());
        $.post("Member/Wallet", {val: val}, data =>{
            $('tbody[id="WalletForm"]').html(data);
        });
    });

    $('form[id="OrderForm"]').submit(function(e) {
        //alert('123');
        var id = $(this).data('id');
        var formObj = $(this);
        var formURL = formObj.attr("action");
        var formData = new FormData(this);
        formData['id'] = id;
        $.ajax({
            //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
            url: "Order/AddOrder",
            type: 'POST',
            data:  formData,
            mimeType:"multipart/form-data",
            contentType: false,
            cache: false,
            processData:false,
            success: function(data, textStatus, jqXHR)
            {
                $('div[id="popup_SentSuccess"]').css('display','inline');
                console.log(data);
                eval(data);
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                $('#divLoading').hide();
            }          
        });
        e.preventDefault(); //Prevent Default action. 
    });

    $.post("Order/SellListSelect", { fun: "TypeId" }, data => {
        // console.log(data);
        // alert(123);
        let obj = JSON.parse(data);
        if (obj.ErrorCode) {
            alert(obj.ErrorText);
        } else {
            for(let i=0;i<obj.length;i++){
                $("select[id='TypeId']").append(
                `<option value="${obj[i].TypeId}">${obj[i].TypeName}</option>`
                );
            }
        }
    });

    // console.log(location.href.substring(location.href.lastIndexOf('/') + 1));
    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "SellList") {
        $.post("Order/SellList", "func=GetData&GameId="+JSGameId,  data =>{
            // alert(JSGameId);
            // console.log(data);
            $('tbody[id="SellListForm"]').html(data);

            $("input[id='detailbutton']").on('click',  function(Event) {
                let ProductNumber = $(this).data("value");
                sessionStorage.setItem('session_ProductNumber', $(this).data("value"));
                console.log(ProductNumber);
                var mapForm = document.createElement("form");
                mapForm.target = "_blank";    
                mapForm.method = "POST";
                mapForm.action = "Order/GameInfo";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "ProductNumber";
                mapInput.value = ProductNumber;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });
        });
    }

	$("input[id='gamelistsearch']").on('click', function (Event) { //gamelist上方搜尋
		// alert(123);
		let GamePlatformval = $("#GamePlatform").change();
		console.log(GamePlatformval);
		// $.post("Index/GameList", { val: val}, data =>{
		// 	// console.log(data);
		// 	$('div[id="GameList"]').html(data);
		// });
	});

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "GameInfo"){
        $.post("Order/GameInfo", {fun: "SellMember", val:sessionStorage.getItem('session_ProductNumber')}, data =>{
            // console.log(session_ProductNumber);
            var obj = JSON.parse(data);
            // console.log(obj[3]);
            $('span[id="MemberAccount"]').html(obj[0]);
            $('span[id="LastLoginDate"]').html(obj[1]);
            if(obj[3] == 0){
                $('span[id="offline_state"]').html(obj[2]);
                $('.online_state').removeClass();
            }else{
                $('span[id="online_state"]').html(obj[2]);
                $('.offline_state').removeClass();
            }
        });
    }
    // console.log(location.href.substring(location.href.lastIndexOf('/') + 1));
    // if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Center" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_sell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_buy" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Members_edit" || location.href.substring(location.href.lastIndexOf('/') + 1) == "OrderBuyInfo" || location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentSell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentBuy"){
    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Center" || "Order_sell" || "Order_buy" || "Wallet" || "Members_edit" || "OrderBuyInfo" || "CommentSell" || "CommentBuy" || "QASell" || "QABuy" || "MailMembers" || "Complain"){
        // console.log(sessionStorage.getItem('session_MemberAccount'));
        $.post("Order/GameInfo", {fun: "MemberOnline", val:sessionStorage.getItem('session_MemberAccount')}, data =>{
            // console.log(data);
            var obj = JSON.parse(data);
            // console.log(obj);
            $('span[id="MemberAccount"]').html(obj[0]);
            $('span[id="LastLoginDate"]').html(obj[1]);
            if(obj[3] == 0){
                $('span[id="offline_state"]').html(obj[2]);
                $('.online_state').removeClass();
            }else{
                $('span[id="online_state"]').html(obj[2]);
                $('.offline_state').removeClass();
            }
        });
    }

	$(function () {
		//加號
		var price1 = parseFloat($('span[id="NeedPay"]').html());
		var num = parseInt($('input[name="Quantity"]').attr('value'));
		$('input[id="plus"]').on('click', function (Event) {
			if(num<$('i[id="KuTsuenQuantity"]').html()){
				num++;
				$('input[name="Quantity"]').attr('value',num);
				var total = num * price1;
				$('span[id="NeedPay"]').html(total.toFixed());
			}
		});
		//減號
		$('input[id="minus"]').on('click', function (Event) {
			if(num>1){
				num--;
				$('input[name="Quantity"]').attr('value',num);
				// console.log(num);
				var total = num * price1;
				$('span[id="NeedPay"]').text(total.toFixed());
			}
		});
	});

	$('input[id="CheckBuy"]').on('click', function (Event) {
        var ProductNumber = $('span[name="ProductNumber"]').text();
        var Quantity = $('input[name="Quantity"]').val();
        var SumPrice = $('span[name="NeedPay"]').text();
        var GameName = $('span[name="GameName"]').text();
        var GameServer = $('span[name="GameServer"]').text();
        var SellMemberId = $('span[name="MemberAccount"]').text();
        $.post("Order/GameInfo", {fun:"CheckBuy",ProductNumber:ProductNumber,Quantity:Quantity,SumPrice:SumPrice,GameName:GameName,GameServer:GameServer,SellMemberId:SellMemberId}, data =>{
            // console.log(isJSON(data));
            // alert(123);
            function isJSON(data) {
                if (typeof data == "string") {
                    try {
                        if(typeof JSON.parse(data) == "object"){
                            return true;
                        }else{
                            return false;
                        }
                    } catch(e) {;
                        return false;
                    }
                }
            }
            if(isJSON(data) == true){
                var obj = JSON.parse(data);
                $('div[id="popup_LoginPublish"]').attr('style',obj[0]);
                // console.log(123);
            }else{
                eval(data);
            }
		});
	});

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Shopping_cart" && document.referrer.substring(document.referrer.lastIndexOf('/') + 1) != "Order_buy") {
        // console.log(document.referrer.substring(document.referrer.lastIndexOf('/') + 1));
        // $.post("Order/SellList",
        $.post("Order/Shopping_cart",{fun: "Shopping_cart", ProductNumber:sessionStorage.getItem('session_ProductNumber')}, data =>{
            function isJSON(data) {
                if (typeof data == "string") {
                    try {
                        if(typeof JSON.parse(data) == "object"){
                            return true;
                        }else{
                            return false;
                        }
                    } catch(e) {;
                        return false;
                    }
                }
            }
            if(isJSON(data) == true){
                var obj = JSON.parse(data);
                // console.log(obj[8]);
                $('img[name="FileName"]').attr("src","../快易購FrontSide(前台20211025)/picturedata/"+obj[0]);
                $('span[name="GameName"]').html(obj[1]);
                $('span[name="TypeName"]').html(obj[2]);
                $('span[name="ProductTitle"]').html(obj[3]);
                $('span[name="ProductNumber"]').html(obj[4]);
                $('span[name="GamePlatform"]').html(obj[5]);
                $('span[name="GameServer"]').html(obj[6]);
                $('span[name="PointCardKind"]').html(obj[7]);
                $('li[id="PointCardKind"]').attr("style",obj[8]);
                $('span[name="TypeName"]').attr('class',obj[9]);
                $('span[name="Quantity"]').html(obj[10]);
                $('span[name="SumPrice"]').html(obj[11]);
                $('span[name="SumPricePlusHand"]').html(obj[12]);
                $('span[name="ATMSumPricePlusHand"]').html(obj[12]);
                $('span[name="ShopSumPricePlusHand"]').html(obj[12]);
                $('span[name="HandlingFee"]').html(obj[13]);
                $('div[name="ATMVirtualAccount"]').html(obj[14]);
                $('div[name="ShopPaymentCode"]').html(obj[15]);
                $('div[name="ShopPaymentEndDate"]').html(obj[16]);
            }else{
                eval(data);
            }
        });
    }
    
    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Shopping_cart" && document.referrer.substring(document.referrer.lastIndexOf('/') + 1) == "Order_buy") {
        // console.log(sessionStorage.getItem('session_OrderNumber'));
        // $.post("Order/SellList",
        $.post("Order/Shopping_cart",{fun: "Shopping_cart", OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            function isJSON(data) {
                if (typeof data == "string") {
                    try {
                        if(typeof JSON.parse(data) == "object"){
                            return true;
                        }else{
                            return false;
                        }
                    } catch(e) {;
                        return false;
                    }
                }
            }
            if(isJSON(data) == true){
                var obj = JSON.parse(data);
                // console.log(obj[1]);
                $('img[name="FileName"]').attr("src","../快易購FrontSide(前台20211025)/picturedata/"+obj[0]);
                $('span[name="GameName"]').html(obj[1]);
                $('span[name="TypeName"]').html(obj[2]);
                $('span[name="ProductTitle"]').html(obj[3]);
                $('span[name="ProductNumber"]').html(obj[4]);
                $('span[name="GamePlatform"]').html(obj[5]);
                $('span[name="GameServer"]').html(obj[6]);
                $('span[name="PointCardKind"]').html(obj[7]);
                $('li[id="PointCardKind"]').attr("style",obj[8]);
                $('span[name="TypeName"]').attr('class',obj[9]);
                $('span[name="Quantity"]').html(obj[10]);
                $('span[name="SumPrice"]').html(obj[11]);
                $('span[name="SumPricePlusHand"]').html(obj[12]);
                $('span[name="ATMSumPricePlusHand"]').html(obj[12]);
                $('span[name="ShopSumPricePlusHand"]').html(obj[12]);
                $('span[name="HandlingFee"]').html(obj[13]);
                $('div[name="ATMVirtualAccount"]').html(obj[14]);
                $('div[name="ShopPaymentCode"]').html(obj[15]);
                $('div[name="ShopPaymentEndDate"]').html(obj[16]);
            }else{
                eval(data);
            }
        });
    }

    $('input[id="AtmPay"]').on('click', function (Event) {
        var PaymentType = 2; // 付款方式
        
        $.post("Order/Shopping_cart", {
            fun:"ATMConfirmBuy",
            ProductTitle:$('span[id="ProductTitle"]').text(),
            OrderNumber:sessionStorage.getItem('session_OrderNumber'),
            ProductNumber:$('span[id="ProductNumber"]').text(),
            // PaymentMethod:sessionStorage.getItem('session_PaymentMethod'),
            SumPricePlusHand:$('span[id="ShopSumPricePlusHand"]').text(),
            PaymentType:PaymentType}, 
            data =>{
            console.log(data);
            var obj = JSON.parse(data);
            // console.log(obj);
            var mapForm = document.createElement("form");
            mapForm.target = "_blank";    
            mapForm.method = "POST";
            mapForm.action = "http://www.17adpay.com/VirAccountPayment.php";

            // Create an input
            for (var key in obj) {
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = key;
                mapInput.value = obj[key];
                mapForm.appendChild(mapInput);
            }
            // Add the form to dom
            document.body.appendChild(mapForm);

            // Just submit
            mapForm.submit();
		});
	});
    
    $('input[id="CvsPay"]').on('click', function (Event) {
        var PaymentType = 3; // 付款方式
        
        $.post("Order/Shopping_cart", {
            fun:"ShopConfirmBuy",
            ProductTitle:$('span[id="ProductTitle"]').text(),
            OrderNumber:sessionStorage.getItem('session_OrderNumber'),
            ProductNumber:$('span[id="ProductNumber"]').text(),
            // PaymentMethod:sessionStorage.getItem('session_PaymentMethod'),
            SumPricePlusHand:$('span[id="ShopSumPricePlusHand"]').text(),
            PaymentType:PaymentType}, 
            data =>{
            console.log(data);
            var obj = JSON.parse(data);
            // console.log(obj);
            var mapForm = document.createElement("form");
            mapForm.target = "_blank";    
            mapForm.method = "POST";
            mapForm.action = "http://www.17adpay.com/StorePayment.php";

            // Create an input
            for (var key in obj) {
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = key;
                mapInput.value = obj[key];
                mapForm.appendChild(mapInput);
            }
            // Add the form to dom
            document.body.appendChild(mapForm);

            // Just submit
            mapForm.submit();
		});
	});

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet") {
        $.post("Member/Wallet",{fun: "Wallet_top", Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
            // console.log(sessionStorage.getItem('session_MemberAccount'));
            var obj = JSON.parse(data);
            // console.log(obj[7]);
            $('span[name="GamePoints"]').html(obj[1]);
            $('span[name="Finaltake"]').html(obj[2]);
            $('span[name="Goingtake"]').html(obj[3]);
            $('span[name="BankName"]').html(obj[4]);
            $('span[name="BankAccount"]').html(obj[5]);
            $('input[id="AddBank"]').attr("style",obj[6]);
            $('input[id="DelBank"]').attr("style",obj[7]);
        });
    }

    $('input[id="AddBankOk"]').on('click', function (Event) {
        $('span[name="BankNameOk"]').html($('select[id="BankName"]').val());
        $('span[name="BankAccountOk"]').html($('input[id="BankAccount"]').val());
        $('span[name="BankAreaOk"]').html($('select[id="BankArea"]').val());
        $('span[name="BranchNameOk"]').html($('input[id="BranchName"]').val());
        $('span[name="BankIdOk"]').html($('input[id="BankId"]').val());
	});

    $('input[id="AddBanksub"]').on('click', function (Event) {
        var BankName = $('span[id="BankNameOk"]').html();
        var BankAccount = $('span[id="BankAccountOk"]').html();
        var BankArea = $('span[id="BankAreaOk"]').html();
        var BranchName = $('span[id="BranchNameOk"]').html();
        var BankId = $('span[id="BankIdOk"]').html();
        sessionStorage.setItem('session_BankName', BankName);
        sessionStorage.setItem('session_BankAccount', BankAccount);
        sessionStorage.setItem('session_BankArea', BankArea);
        sessionStorage.setItem('session_BranchName', BranchName);
        sessionStorage.setItem('session_BankId', BankId);
        $.post("Member/Wallet", {fun:"AddBank",BankName:BankName,BankAccount:BankAccount,BankArea:BankArea,BranchName:BranchName,BankId:BankId}, data =>{
            eval(data);
		});
	});

    $('input[id="AddBankInfo"]').on('click', function (Event) {
        $('span[name="BankNameInfo"]').html(sessionStorage.getItem('session_BankName'));
        $('span[name="BankAccountInfo"]').html(sessionStorage.getItem('session_BankAccount'));
        $('span[name="BankAreaInfo"]').html(sessionStorage.getItem('session_BankArea'));
        $('span[name="BranchNameInfo"]').html(sessionStorage.getItem('session_BranchName'));
        $('span[name="BankIdInfo"]').html(sessionStorage.getItem('session_BankId'));
	});

    $('input[id="DelBanksub"]').on('click', function (Event) {
        console.log(sessionStorage.getItem('session_MemberAccount'));
        $.post("Member/Wallet", {fun:"DelBank",Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
            eval(data);
		});
	});

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentSell") {
        $.post("Order/OrderBuyInfo",{fun: "CommentSell_top"}, data =>{
            var obj = JSON.parse(data);
            $('span[name="nowgood"]').html(obj[0]);
            $('span[name="beforegood"]').html(obj[1]);
            $('span[name="nowsoso"]').html(obj[2]);
            $('span[name="beforesoso"]').html(obj[3]);
            $('span[name="nowbad"]').html(obj[4]);
            $('span[name="beforebad"]').html(obj[5]);
            $('div[name="good"]').html(obj[6]);
            $('div[name="bad"]').html(obj[7]);
            $('span[name="good_bad"]').html(obj[8]);
            $('span[name="avgEvalu"]').html(obj[9]);
            // $('input[id="DelBank"]').attr("style",obj[7]);
        });
        $.post("Order/OrderBuyInfo",{fun: "CommentSell"}, data =>{
            $('tbody[id="EvaluForm"]').html(data);

            $('input[id="ReplyComment"]').on('click', function (Event) {
                let OrderNumber = $(this).data('value');
                // console.log($(this).data('value'));
                Event.preventDefault();
                document.getElementById("popup_ReplyComment").style.display = "block";

                $('input[id="ConfirmReply"]').on('click', function (Event) {
                    console.log($('textarea[id="ReplyInfo"]').val());
                    // console.log(OrderNumber);
                    $.post("Order/OrderBuyInfo",{fun: "CommentReplySell", ReplyInfo:$('textarea[id="ReplyInfo"]').val(), OrderNumber:OrderNumber}, data =>{
                        eval(data);
                    });
                });
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentBuy") {
        $.post("Order/OrderSellInfo",{fun: "CommentBuy_top"}, data =>{
            console.log(data);
            var obj = JSON.parse(data);
            $('span[name="nowgood"]').html(obj[0]);
            $('span[name="beforegood"]').html(obj[1]);
            $('span[name="nowsoso"]').html(obj[2]);
            $('span[name="beforesoso"]').html(obj[3]);
            $('span[name="nowbad"]').html(obj[4]);
            $('span[name="beforebad"]').html(obj[5]);
            $('div[name="good"]').html(obj[6]);
            $('div[name="bad"]').html(obj[7]);
            $('span[name="good_bad"]').html(obj[8]);
            $('span[name="avgEvalu"]').html(obj[9]);
            // $('input[id="DelBank"]').attr("style",obj[7]);
        });
        $.post("Order/OrderSellInfo",{fun: "CommentBuy"}, data =>{
            $('tbody[id="EvaluForm"]').html(data);

            $('input[id="ReplyComment"]').on('click', function (Event) {
                let OrderNumber = $(this).data('value');
                // console.log($(this).data('value'));
                Event.preventDefault();
                document.getElementById("popup_ReplyComment").style.display = "block";

                $('input[id="ConfirmReplyBuy"]').on('click', function (Event) {
                    console.log($('textarea[id="ReplyInfoBuy"]').val());
                    // console.log(OrderNumber);
                    $.post("Order/OrderSellInfo",{fun: "CommentReplyBuy", ReplyInfo:$('textarea[id="ReplyInfoBuy"]').val(), OrderNumber:OrderNumber}, data =>{
                        eval(data);
                    });
                });
            });
        });
    }




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