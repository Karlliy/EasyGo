
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

        $("select[id='ProductId']").on('change',  function(Event) {
            //console.log( $(this).val());
            var id = $(this);
            // $("#GameName option").remove();
            // $("select[id='GameName']").append(`<option value="">請選擇</option>`);

            $(this).parent().next().next().next().children("#GameName").children().remove();
            id.parent().next().next().next().children('select[id="GameName"]').append(`<option value="">請選擇</option>`);
            let ProductId = ($(this).val() == "") ? 99 : $(this).val();

            $.post("Order/AddOrder", { fun: "GameName", ProductId:ProductId}, data => {
                console.log(data);
                if(data != "null"){
                    let obj = JSON.parse(data);
                    if (obj.ErrorCode) {
                        alert(obj.ErrorText);
                    } else {
                        for(let i=0;i<obj.length;i++){
                            id.parent().next().next().next().children('select[id="GameName"]').append(`<option value="${obj[i].GameId}">${obj[i].GameName}</option>`);
                        }
                    }
                }
            });
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
                $("input[id='DelCheck']").on('click',  function(Event) {
                    $.post("Member/Order_sell", {fun:"OrderSellDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                        // console.log(data);
                        eval(data);
                    });
                });
            });
        });
        $("input[id='OrderSellSearch']").on('click',  function(Event) {
            $.post("Member/Order_sell", {
                OrderNumber:$('input[id="OrderNumber"]').val(),
                BuyMemberAccount:$('input[id="BuyMemberAccount"]').val(),
                PaymentCode:$('input[id="PaymentCode"]').val()
            }, data =>{
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
                    $("input[id='DelCheck']").on('click',  function(Event) {
                        $.post("Member/Order_sell", {fun:"OrderSellDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                            // console.log(data);
                            eval(data);
                        });
                    });
                });
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
                $("input[id='DelCheck']").on('click',  function(Event) {
                    $.post("Member/Order_buy", {fun:"OrderBuyDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                        // console.log(data);
                        eval(data);
                    });
                });
            });
        });

        $("input[id='OrderBuySearch']").on('click',  function(Event) {
            $.post("Member/Order_buy", {
                OrderNumber:$('input[id="OrderNumber"]').val(),
                SellMemberAccount:$('input[id="SellMemberAccount"]').val(),
                PaymentCode:$('input[id="PaymentCode"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="OrderBuyForm"]').html(data);
                
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
                    $("input[id='DelCheck']").on('click',  function(Event) {
                        $.post("Member/Order_buy", {fun:"OrderBuyDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                            // console.log(data);
                            eval(data);
                        });
                    });
                }); 
            });
        }); 
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet") {
        $.post("Member/Wallet", data =>{
            // console.log(data);
            $('tbody[id="WalletForm"]').html(data);

            $("input[id='Detail']").on('click',  function(Event) {
                let RowNumber = $(this).data("value");
                console.log(RowNumber.substring(2,0));
                if(RowNumber.substring(2,0) == "PO" || RowNumber.substring(2,0) == "WP" || RowNumber.substring(2,0) == "PA" || RowNumber.substring(2,0) == "BC"){
                    Event.preventDefault();
                    document.getElementById("popup_WalletIncome").style.display = "block";
                    $.post("Member/Wallet", {fun:"Detail",val:RowNumber.substring(2,0),RowNumber:RowNumber}, data =>{
                        console.log(data);
                        var obj = JSON.parse(data);
                        $('span[name="CreateDate"]').html(obj[0]);
                        $('span[name="PointChangState"]').html(obj[1]);
                        $('span[name="SumPrice"]').html(obj[2]);
                        $('span[name="GamePointsWallet"]').html(obj[3]);
                        $('span[name="OrderNumber"]').html(obj[4]);
                    });
                }else if(RowNumber.substring(2,0) == "WA"){
                    Event.preventDefault();
                    document.getElementById("popup_WithdrawIncome").style.display = "block";
                    $.post("Member/Wallet", {fun:"Detail",val:RowNumber.substring(2,0),RowNumber:RowNumber}, data =>{
                        console.log(data);
                        var obj = JSON.parse(data);
                        $('span[name="ApplyDate"]').html(obj[0]);
                        $('span[name="TakeMoney"]').html(obj[1]);
                        $('span[name="WithdrawState"]').html(obj[2]);
                        $('span[name="BankNameDetail"]').html(obj[3]);
                        $('span[name="BankAccountDetail"]').html(obj[4]);
                    });
                }
                
                // $.post("Member/Wallet", {fun:"Detail",OrderNumber:OrderNumber}, data =>{

                // });
            }); 
            // $("input[id='Detail']").on('click',  function(Event) {
			// 	let OrderNumber = $(this).data("value");
            //     sessionStorage.setItem('session_OrderNumber', OrderNumber);
	        //     console.log(OrderNumber);
			// 	var mapForm = document.createElement("form");
			// 	// mapForm.target = "open";    
			// 	mapForm.method = "POST";
			// 	mapForm.action = "Order/OrderBuyInfo";
			// 	// Create an input
			// 	var mapInput = document.createElement("input");
			// 	mapInput.type = "hidden";
			// 	mapInput.name = "OrderNumber";
			// 	mapInput.value = OrderNumber;
	
			// 	// Add the input to the form
			// 	mapForm.appendChild(mapInput);
	
			// 	// Add the form to dom
			// 	document.body.appendChild(mapForm);
	
			// 	// Just submit
			// 	mapForm.submit();
			// });
        });

        $.post("Member/Wallet",{fun: "Wallet_top", Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
            // console.log(sessionStorage.getItem('session_MemberAccount'));
            var obj = JSON.parse(data);
            // console.log(data);
            $('input[name="Withdraw"]').attr("data-value",obj[0]);
            $('span[name="GamePoints"]').html(obj[1]);
            $('span[name="Finaltake"]').html(obj[2]);
            $('span[name="Goingtake"]').html(obj[3]);
            $('span[name="BankName"]').html(obj[4]);
            $('span[name="BankAccount"]').html(obj[5]);
            $('input[id="AddBank"]').attr("style",obj[6]);
            $('input[id="DelBank"]').attr("style",obj[7]);

            $('span[name="BankNameInfo"]').html(obj[4]);
            $('span[name="BankAccountInfo"]').html(obj[5]);
            $('span[name="BankAreaInfo"]').html(obj[8]);
            $('span[name="BranchNameInfo"]').html(obj[9]);
            $('span[name="BankIdInfo"]').html(obj[10]);
        });
        $('input[id="Withdraw"]').on('click', function (Event) {
            let Withdraw = $(this).data("value");
            var today = new Date();
            let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
            let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
            let Datee2 = (today.setDate(today.getDate()+3) < 10) ? ('0'+today.setDate(today.getDate()+3)) : today.getDate();
            let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
            let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
            let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
            var ApplyDate = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
            var PredictDate = today.getFullYear()+"-"+Month+"-"+Datee2+" "+Hours+":"+Minutes+":"+Seconds;
            console.log(Withdraw);
            $.post("Member/Wallet",{fun: "Wallet_top", Withdraw:Withdraw}, data =>{
                var obj = JSON.parse(data);
                $('span[name="ApplyDate"]').html(ApplyDate);
                $('span[name="WithdrawNumber"]').html(obj[11]);
                $('span[name="GamePoints"]').html(obj[1]);
                $('span[name="PredictDate"]').html(PredictDate);
                $('span[name="BankName"]').html(obj[4]);
                $('span[name="BankAccount"]').html(obj[5]);
            });
        });
        $('input[id="ConfirmWithdraw"]').on('click', function (Event) {
            // console.log(Withdraw);
            ApplyDate = $('span[id="ApplyDate"]').html();
            WithdrawNumber = $('span[id="WithdrawNumber"]').html();
            TakeMoney = $('input[id="TakeMoney"]').val();
            PredictDate = $('span[id="PredictDate"]').html();
            BankName = $('span[id="BankName"]').html();
            BankAccount = $('span[id="BankAccount"]').html();
            console.log(ApplyDate);console.log(WithdrawNumber);console.log(TakeMoney);
            console.log(PredictDate);console.log(BankName);console.log(BankAccount);
            $.post("Member/WithdrawApply",{ApplyDate:ApplyDate,WithdrawNumber:WithdrawNumber,TakeMoney:TakeMoney,PredictDate:PredictDate,BankName:BankName,BankAccount:BankAccount}, data =>{
                eval(data);
                // console.log(data);
            });
        });
    }
    
    //$("#GameCurrency,#GameAccount,#GamePointCard,#Gameprops,#GameMall,#GameGift,#GameOther label").on('click', function (Event) {

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
            $("input[id='DelCheck']").on('click',  function(Event) {
                $.post("Member/Order_sell", {fun:"OrderSellDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                    // console.log(data);
                    eval(data);
                });
            });
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
                $("input[id='DelCheck']").on('click',  function(Event) {
                    $.post("Member/Order_buy", {fun:"OrderBuyDel",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
                        // console.log(data);
                        eval(data);
                    });
                });
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
            $('span[name="PaymentCode"]').html(obj[13]);
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
			$('span[name="OrderNumber"]').html(obj[1]);
            $('span[name="ProductNumber"]').html(obj[2]);
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
        $.post("Order/SellList", {func:"GetData", GameId:JSGameId,val:"SellListTop"}, data =>{
            let obj = JSON.parse(data);
            $('a[id="TopGameType"]').html(obj[1]);
            $('span[id="TopGameName"]').html(obj[0]);
        });
        $.post("Order/SellList", "func=GetData&GameId="+JSGameId,  data =>{
            // alert(JSGameId);
            // console.log(GameType);
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
        
        $("input[id='GameListSearch']").on('click', function (Event) { //gamelist上方搜尋
            $.post("Order/SellList", {func:"GetData", GameId:JSGameId,GamePlatform:$("select[id='GamePlatform']").val(), TypeId:$("select[id='TypeId']").val()}, data =>{
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
            // alert(123);
            // let GamePlatformval = $("#GamePlatform").change();
            // console.log(GamePlatformval);
            // $.post("Index/GameList", { val: val}, data =>{
            // 	// console.log(data);
            // 	$('div[id="GameList"]').html(data);
            // });
        });
        $("input[id='GameListOrder']").on('click', function (Event) { //gamelist上方搜尋
            $.post("Order/SellList", {func:"GetData", GameId:JSGameId,GameListOrder:$("select[id='GameListOrder']").val()}, data =>{
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
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "GameInfo"){
        let ProductNumber = sessionStorage.getItem('session_ProductNumber');
        $.post("Order/GameInfo", {func:"GameInfoTop", ProductNumber:ProductNumber}, data =>{
            let obj = JSON.parse(data);
            $('a[id="TopGameType"]').html(obj[1]);
            $('a[id="TopGameName"]').html(obj[0]);
        });
        $.post("Order/GameInfo", {fun: "SellMember", val:ProductNumber}, data =>{
            console.log(data);
            var obj = JSON.parse(data);
            // console.log(obj[3]);
            $('span[id="SellMemberAccount"]').html(obj[0]);
            $('span[id="SellLastLoginDate"]').html(obj[1]);
            $('span[name="SellEvalu"]').html(obj[4]+"%");
            $('span[name="SellCountOrder"]').html(obj[5]);
            if(obj[3] == 0){
                $('span[id="SellOffline_state"]').html(obj[2]);
                $('.online_state').removeClass();
            }else{
                $('span[id="SellOnline_state"]').html(obj[2]);
                $('.offline_state').removeClass();
            }
        });
        
        $.post("Order/GameInfo", {fun:"EvaluList", ProductNumber:ProductNumber}, data =>{
            $('tbody[id="EvaluList"]').html(data);
        });
        
        $("input[name='as_tabsel']").on('click',  function(Event) {
            $.post("Order/GameInfo", {fun:"EvaluList", ProductNumber:ProductNumber, OrderBy:$("input[name='as_tabsel']:checked").map(function() { return $(this).val(); }).get()}, data =>{
                $('tbody[id="EvaluList"]').html(data);
            });
            // $("input[name='as_tabsel']:checked").map(function() { return $(this).val(); }).get();
        });

        $("input[id='QuesConfirm']").on('click',  function(Event) {
            $.post("Order/GameInfo", {fun:"InsertQues", ProductNumber:ProductNumber, QuesInfo:$("textarea[id='QuesInfo']").val()}, data =>{
                eval(data);
            });
        });

        $.post("Order/GameInfo", {fun:"ProductQAList", ProductNumber:ProductNumber}, data =>{
            console.log(data);console.log(123);
            $('div[id="ProductQAList"]').html(data);

            $("input[id='AnsConfirm']").on('click',  function(Event) {
                let RowIdAns = $(this).data('value');
                $.post("Order/GameInfo", {fun:"UpdateAns", ProductNumber:ProductNumber, AnsInfo:$("textarea[id='AnsInfo']").val(), RowIdAns:RowIdAns}, data =>{
                    eval(data);
                });
            });
        });
    }
    
    // console.log(location.href.substring(location.href.lastIndexOf('/') + 1));
    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Center" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_sell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_buy" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Members_edit" || location.href.substring(location.href.lastIndexOf('/') + 1) == "OrderBuyInfo" || location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentSell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentBuy" || location.href.substring(location.href.lastIndexOf('/') + 1) == "QASell" || location.href.substring(location.href.lastIndexOf('/') + 1) == "QABuy" || location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMembers" || location.href.substring(location.href.lastIndexOf('/') + 1) == "Complain"){
    // if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Center" || "Order_sell" || "Order_buy" || "Wallet" || "Members_edit" || "OrderBuyInfo" || "CommentSell" || "CommentBuy" || "QASell" || "QABuy" || "MailMembers" || "Complain"){
        // console.log(sessionStorage.getItem('session_MemberAccount'));
        $.post("Order/GameInfo", {fun: "MemberOnline", val:sessionStorage.getItem('session_MemberAccount')}, data =>{
            // console.log(898989);
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
        $.post("Member/Center", {fun: "MemberMiddle"}, data =>{console.log(data);
            // console.log(session_ProductNumber);
            var obj = JSON.parse(data);
            console.log(obj[2]);
            $('span[name="Evalu"]').html(obj[2]+"%");
            $('span[name="CountOrder"]').html(obj[4]);
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
        var SellMemberAccount = $('span[name="SellMemberAccount"]').text();
        $.post("Order/GameInfo", {fun:"CheckBuy",ProductNumber:ProductNumber,Quantity:Quantity,SumPrice:SumPrice,GameName:GameName,GameServer:GameServer,SellMemberAccount:SellMemberAccount}, data =>{
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
                // console.log(data);
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

    // if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Wallet") {
    //     $.post("Member/Wallet",{fun: "Wallet_top", Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
    //         // console.log(sessionStorage.getItem('session_MemberAccount'));
    //         var obj = JSON.parse(data);
    //         // console.log(data);
    //         $('input[name="Withdraw"]').attr("data-value",obj[0]);
    //         $('span[name="GamePoints"]').html(obj[1]);
    //         $('span[name="Finaltake"]').html(obj[2]);
    //         $('span[name="Goingtake"]').html(obj[3]);
    //         $('span[name="BankName"]').html(obj[4]);
    //         $('span[name="BankAccount"]').html(obj[5]);
    //         $('input[id="AddBank"]').attr("style",obj[6]);
    //         $('input[id="DelBank"]').attr("style",obj[7]);

    //         $('span[name="BankNameInfo"]').html(obj[4]);
    //         $('span[name="BankAccountInfo"]').html(obj[5]);
    //         $('span[name="BankAreaInfo"]').html(obj[8]);
    //         $('span[name="BranchNameInfo"]').html(obj[9]);
    //         $('span[name="BankIdInfo"]').html(obj[10]);
    //     });
    //     $('input[id="Withdraw"]').on('click', function (Event) {
    //         let Withdraw = $(this).data("value");
    //         var today = new Date();
    //         let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
    //         let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
    //         let Datee2 = (today.setDate(today.getDate()+3) < 10) ? ('0'+today.setDate(today.getDate()+3)) : today.getDate();
    //         let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
    //         let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
    //         let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
    //         var ApplyDate = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
    //         var PredictDate = today.getFullYear()+"-"+Month+"-"+Datee2+" "+Hours+":"+Minutes+":"+Seconds;
    //         console.log(Withdraw);
    //         $.post("Member/Wallet",{fun: "Wallet_top", Withdraw:Withdraw}, data =>{
    //             var obj = JSON.parse(data);
    //             $('span[name="ApplyDate"]').html(ApplyDate);
    //             $('span[name="WithdrawNumber"]').html(obj[11]);
    //             $('span[name="GamePoints"]').html(obj[1]);
    //             $('span[name="PredictDate"]').html(PredictDate);
    //             $('span[name="BankName"]').html(obj[4]);
    //             $('span[name="BankAccount"]').html(obj[5]);
    //         });
    //     });
    //     $('input[id="ConfirmWithdraw"]').on('click', function (Event) {
    //         // console.log(Withdraw);
    //         ApplyDate = $('span[id="ApplyDate"]').html();
    //         WithdrawNumber = $('span[id="WithdrawNumber"]').html();
    //         TakeMoney = $('input[id="TakeMoney"]').val();
    //         PredictDate = $('span[id="PredictDate"]').html();
    //         BankName = $('span[id="BankName"]').html();
    //         BankAccount = $('span[id="BankAccount"]').html();
    //         console.log(ApplyDate);console.log(WithdrawNumber);console.log(TakeMoney);
    //         console.log(PredictDate);console.log(BankName);console.log(BankAccount);
    //         $.post("Member/WithdrawApply",{ApplyDate:ApplyDate,WithdrawNumber:WithdrawNumber,TakeMoney:TakeMoney,PredictDate:PredictDate,BankName:BankName,BankAccount:BankAccount}, data =>{
    //             eval(data);
    //             // console.log(data);
    //         });
    //     });
    //     // $.post("Member/Wallet",{fun: "Wallet_Bank", Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
    //     //     // console.log(sessionStorage.getItem('session_MemberAccount'));
    //     //     var obj = JSON.parse(data);
    //     //     console.log(data);
    //     //     $('span[name="BankNameInfo"]').html(obj[0]);
    //     //     $('span[name="BankAccountInfo"]').html(obj[1]);
    //     //     $('span[name="BankAreaInfo"]').html(obj[2]);
    //     //     $('span[name="BranchNameInfo"]').html(obj[3]);
    //     //     $('span[name="BankIdInfo"]').html(obj[4]);
    //     // });
    // }

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

    // $('input[id="AddBankInfo"]').on('click', function (Event) {
    //     // $('span[name="BankNameInfo"]').html(sessionStorage.getItem('session_BankName'));
    //     // $('span[name="BankAccountInfo"]').html(sessionStorage.getItem('session_BankAccount'));
    //     // $('span[name="BankAreaInfo"]').html(sessionStorage.getItem('session_BankArea'));
    //     // $('span[name="BranchNameInfo"]').html(sessionStorage.getItem('session_BranchName'));
    //     // $('span[name="BankIdInfo"]').html(sessionStorage.getItem('session_BankId'));
    //     $.post("Member/Wallet",{fun: "Wallet_Bank", Account:sessionStorage.getItem('session_MemberAccount')}, data =>{
    //         // console.log(sessionStorage.getItem('session_MemberAccount'));
    //         console.log(data);
    //         var obj = JSON.parse(data);
    //         $('span[name="BankNameInfo"]').html(obj[0]);
    //         $('span[name="BankAccountInfo"]').html(obj[1]);
    //         $('span[name="BankAreaInfo"]').html(obj[2]);
    //         $('span[name="BranchNameInfo"]').html(obj[3]);
    //         $('span[name="BankIdInfo"]').html(obj[4]);
    //     });
	// });

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

        $('input[id="CommentSellOrderBy"]').on('click', function (Event) {
// console.log($('select[id="EvaluSell"]').val());
            $.post("Order/OrderBuyInfo",{fun: "CommentSell", OrderBy:$('select[id="EvaluSell"]').val()}, data =>{
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
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "CommentBuy") {
        $.post("Order/OrderSellInfo",{fun: "CommentBuy_top"}, data =>{
            // console.log(data);
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
        $('input[id="CommentBuyOrderBy"]').on('click', function (Event) {
            // console.log($('select[id="EvaluBuy"]').val());
            $.post("Order/OrderSellInfo",{fun: "CommentBuy", OrderBy:$('select[id="EvaluBuy"]').val()}, data =>{
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
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Complain") {
        $.post("Order/Complain", data =>{
            // console.log(data);
            if(data == ""){
                $('div[name="Appeal"]').attr("style","display: none;");
                $('div[name="NoAppeal"]').attr("style","display: block;");
            }else{
                $('div[name="NoAppeal"]').attr("style","display: none;");
                $('tbody[id="AppealList"]').html(data);
            }
            $('input[id="AppealCheck"]').on('click', function (Event) {
                let RowId = $(this).data('value');
                Event.preventDefault();
                document.getElementById("popup_CheckComplain").style.display = "block";
                console.log(RowId);
                $.post("Order/Complain", {RowId:RowId}, data =>{
                    var obj = JSON.parse(data);
                    // console.log(obj[5]);
                    $('select[id="AppealName"]').val(obj[0]);
                    $('input[id="OrderNumber"]').val(obj[1]);
                    $('input[id="AppealObject"]').val(obj[2]);
                    $('input[id="AppealTitle"]').val(obj[3]);
                    $('textarea[id="AppealContent"]').html(obj[4]);
                    $('a[id="Appealfile"]').html(obj[5]);
                    $('img[id="AppealfileImg"]').attr("src","../快易購FrontSide(前台20211025)/appealImg/"+obj[5]);
                    // $('div[id="AppealfileImg"]').html(obj[5]);
                });
            });
            $('input[id="AppealCancel"]').on('click', function (Event) {
                let RowId = $(this).data('value');
                sessionStorage.setItem('session_RowId',RowId);
                Event.preventDefault();
                document.getElementById("popup_AppealCancle").style.display = "block";
                // console.log(RowId);
            });
            $('input[id="AppealCancleConfirm"]').on('click', function (Event) {
                // console.log(sessionStorage.getItem('session_RowId'));
                $.post("Order/Complain", {val:"AppealCancel",RowId:sessionStorage.getItem('session_RowId')}, data =>{
                    eval(data);
                });
            });
        });

        $('form[id="AppealForm"]').submit(function(e) {
            // var id = $(this).data('id');
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            formData.append('data',"AppealForm");
            $.ajax({          
                url: "Order/Complain",
                type: 'POST',
                data:  formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
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

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMembers") {
        $.post("Order/MailMembers", {val:"SystemMail"}, data =>{
            // console.log(data);
            $('tbody[id="SystemMailList"]').html(data);

            $("input[id='SystemMailChk']").on('click',  function(Event) {
                let AnnouncementRowId = $(this).data("value");
                sessionStorage.setItem('session_MailSystemRowId', $(this).data("value"));
                console.log(AnnouncementRowId);
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Order/MailSystemEdit";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "AnnouncementRowId";
                mapInput.value = AnnouncementRowId;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });

            $('input[id="SystemMailDel"]').on('click', function (Event) {
                let AnnouncementRowId = $(this).data('value');
                sessionStorage.setItem('session_MailSystemRowId',AnnouncementRowId);
                // console.log(sessionStorage.getItem('session_MailSystemRowId'));
                Event.preventDefault();
                document.getElementById("popup_DeleteInfo").style.display = "block";
                // console.log(RowId);
                $('input[id="MailDelete"]').on('click', function (Event) {
                // console.log(sessionStorage.getItem('session_RowId'));
                    $.post("Order/MailMembers", {val:"SystemMailDelete",AnnouncementRowId:sessionStorage.getItem('session_MailSystemRowId')}, data =>{
                        eval(data);
                    });
                });
            });
        });

        $.post("Order/MailMembers", {val:"MemberMail"}, data =>{
            // console.log(data);
            $('tbody[id="MemberMailList"]').html(data);

            $("input[id='MemberMailChk']").on('click',  function(Event) {
                let RowId = $(this).data("value");
                sessionStorage.setItem('session_MailMembersRowId', RowId);
                // console.log(sessionStorage.getItem('session_MailMembersRowId'));
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Order/MailMembersEdit";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "RowId";
                mapInput.value = RowId;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });
            $('input[id="MemberMailDel"]').on('click', function (Event) {
                let RowId = $(this).data('value');
                sessionStorage.setItem('session_MailMembersRowId',RowId);
                Event.preventDefault();
                document.getElementById("popup_DeleteInfo").style.display = "block";
                // console.log(RowId);
                $('input[id="MailDelete"]').on('click', function (Event) {
                // console.log(sessionStorage.getItem('session_RowId'));
                    $.post("Order/MailMembers", {val:"MemberMailDelete",RowId:sessionStorage.getItem('session_MailMembersRowId')}, data =>{
                        eval(data);
                    });
                });
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailSystemEdit") {
        console.log(sessionStorage.getItem('session_MailSystemRowId'));
        $.post("Order/MailSystemEdit", {val:"MailSystemEdit",AnnouncementRowId:sessionStorage.getItem('session_MailSystemRowId')}, data =>{
            console.log(data);
            var obj = JSON.parse(data);
            $('span[name="MailCreateDate"]').html(obj[0]);
            $('span[name="CreatePersonnel"]').html(obj[1]);
            $('span[name="AnnouncementTitle"]').html(obj[2]);
            $('span[name="AnnouncementInfo"]').html(obj[3]);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMembersEdit") {
        // console.log(sessionStorage.getItem('session_MailMembersRowId'));
        $.post("Order/MailMembersEdit", {val:"MailMembersEdit",RowId:sessionStorage.getItem('session_MailMembersRowId')}, data =>{
            // console.log(data);
            var obj = JSON.parse(data);
            $('span[name="MailCreateDate"]').html(obj[0]);
            $('span[name="CreatePersonnel"]').html(obj[1]);
            $('span[name="MemberAccount"]').html(obj[2]);
            $('span[name="AnnouncementTitle"]').html(obj[3]);
            $('span[name="AnnouncementInfo"]').html(obj[4]);
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