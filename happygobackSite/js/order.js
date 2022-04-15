$(function() {

    $.post("Order/SelectList",{fun:"TypeId"}, data =>{
        let obj = JSON.parse(data);
        for(let i=0;i<obj.length;i++){
            $("select[id='TypeId']").append(
            `<option value="${obj[i].TypeId}">${obj[i].TypeName}</option>`
            );
        }
    });

    $.post("Order/SelectList",{fun:"ProductId"}, data =>{
        let obj = JSON.parse(data);
        for(let i=0;i<obj.length;i++){
            $("select[id='ProductId']").append(
            `<option value="${obj[i].ProductId}">${obj[i].ProductName}</option>`
            );
        }
    });


    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order") {
    
        $.post("Order/Order", data =>{
            // console.log(data);
            $('tbody[id="OrderForm"]').html(data);

            $('#OrderTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='viewbtn']").on('click',  function(Event) {
                let ProductNumber = $(this).data("value");
                sessionStorage.setItem('session_ProductNumber', $(this).data("value"));
                console.log(ProductNumber);
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Order/Order_list";

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

        $.post("Order/Order",{formlist:1}, data =>{
            $('tfoot[id="Orderfoot"]').html(data);
        });

        $.post("Order/Order",{formlist:2}, data =>{
            var obj = JSON.parse(data);
            $('span[name="Sumtotal"]').html(obj[0]);
            $('span[name="SumKuTsuenQuantity"]').html(obj[1]);
            $('span[name="SumChiuHsiaoQuantity"]').html(obj[2]);
            $('span[name="SumHsiaoShouQuantity"]').html(obj[3]);
            $('span[name="SumSumHandlingFee"]').html(obj[4]);
        });

        $("input[id='OrderSearch']").click(data =>{
            dttable = $('#OrderTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Order/Order", {
                ProductNumber: $("input[id=ProductNumber]").val(),
                ProductId:$('select[id="ProductId"]').val(), 
                TypeId:$('select[id="TypeId"]').val(),
                MemberAccount: $("input[id=MemberAccount]").val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            },data =>{
                $('tbody[id="OrderForm"]').html(data);

                $('#OrderTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });
    
                $("input[id='viewbtn']").on('click',  function(Event) {
                    let ProductNumber = $(this).data("value");
                    sessionStorage.setItem('session_ProductNumber', $(this).data("value"));
                    console.log(ProductNumber);
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Order/Order_list";
    
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
    
            $.post("Order/Order",{
                ProductNumber: $("input[id=ProductNumber]").val(),
                ProductId:$('select[id="ProductId"]').val(), 
                TypeId:$('select[id="TypeId"]').val(),
                MemberAccount: $("input[id=MemberAccount]").val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val(),
                formlist:1
            }, data =>{
                $('tfoot[id="Orderfoot"]').html(data);
            });
    
            $.post("Order/Order",{
                ProductNumber: $("input[id=ProductNumber]").val(),
                ProductId:$('select[id="ProductId"]').val(), 
                TypeId:$('select[id="TypeId"]').val(),
                MemberAccount: $("input[id=MemberAccount]").val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val(),
                formlist:2
            }, data =>{
                var obj = JSON.parse(data);
                $('span[name="Sumtotal"]').html(obj[0]);
                $('span[name="SumKuTsuenQuantity"]').html(obj[1]);
                $('span[name="SumChiuHsiaoQuantity"]').html(obj[2]);
                $('span[name="SumHsiaoShouQuantity"]').html(obj[3]);
                $('span[name="SumSumHandlingFee"]').html(obj[4]);
            });
        });

        $("input[id='OrderReset']").on('click',  function(Event) {
            $('input[id="ProductNumber"]').val("");
            $('select[id="ProductId"]').val("");
            $('select[id="TypeId"]').val("");
            $('input[id="MemberAccount"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_list") {
        console.log(location.href.substring(location.href.lastIndexOf('/') + 1));
        $.post("Order/Order_list",{fun:"Order_list_top",ProductNumber:JSProductNumber}, data =>{
            // console.log(data);
            var obj = JSON.parse(data);
            sessionStorage.setItem('session_Sellmember', obj[13]);
            sessionStorage.setItem('session_ProductNumber', obj[1]);
            // console.log(sessionStorage.getItem('session_ProductNumber'));
            $('div[name="ProductTitle"]').html(obj[0]);
            $('span[name="ProductNumber"]').html(obj[1]);
            $('span[name="ProductName"]').html(obj[2]);
            $('span[name="GamePlatform"]').html(obj[3]);
            $('span[name="GameCoinQuantity"]').html(obj[4]);
            $('span[name="CreateDate"]').html(obj[5]);
            $('span[name="TypeName"]').html(obj[6]);
            $('span[name="GameServer"]').html(obj[7]);
            $('span[name="Price"]').html(obj[8]);
            $('span[name="LevelName"]').html(obj[9]);
            $('span[name="PointCardKind"]').html(obj[10]);
            $('span[name="Currency"]').html(obj[11]);
            $('span[name="KuTsuenQuantity"]').html(obj[12]);
            $('span[name="Sellmember"]').html(obj[13]);
            $('span[name="GameName"]').html(obj[15]);
            $('span[name="CurrencyValue"]').html(obj[16]);
        });

        $.post("Order/Order_list",{fun:"Order_list_foot",Sellmember:sessionStorage.getItem('session_Sellmember'),ProductNumber:sessionStorage.getItem('session_ProductNumber')}, data =>{
            
            $('tbody[id="Order_list_foot"]').html(data);

            $('#OrderListTable').DataTable({
                language: {
                        url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 7, 8, 9] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='DetailBtn']").on('click',  function(Event) {
                let OrderNumber = $(this).data("value");
                sessionStorage.setItem('session_OrderNumber', $(this).data("value"));
                console.log(OrderNumber);
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Order/Order_detail";

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

        $.post("Order/Order_list",{fun:"Order_list_foot",Sellmember:sessionStorage.getItem('session_Sellmember'),ProductNumber:sessionStorage.getItem('session_ProductNumber'),formlist:1}, data =>{
            // console.log(data);
            $('tfoot[id="Order_list_Sum"]').html(data);
        });

        $.post("Order/Order_list",{fun:"Order_list_foot",Sellmember:sessionStorage.getItem('session_Sellmember'),ProductNumber:sessionStorage.getItem('session_ProductNumber'),formlist:2}, data =>{
            var obj = JSON.parse(data);
            console.log(obj[0]);
            $('span[name="row"]').html(obj[0]);
            $('span[name="CountStatusF"]').html(obj[1]);
            $('span[name="SumSumPrice"]').html(obj[2]);
            $('span[name="CountStatusC"]').html(obj[3]);
            $('span[name="CountStatusR"]').html(obj[4]);
            $('span[name="SumHandlingFee"]').html(obj[5]);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Order_detail") {
        console.log(123);
        console.log(location.href.substring(location.href.lastIndexOf('/') + 1));
        console.log(sessionStorage.getItem('session_OrderNumber'));
        $.post("Order/Order_detail",{fun:"Order_detail",OrderNumber:sessionStorage.getItem('session_OrderNumber')}, data =>{
            console.log(data);
            var obj = JSON.parse(data);
            console.log(obj[21]);
            $('span[name="OrderNumber"]').html(obj[0]);
            $('span[name="ProductNumber"]').html(obj[1]);
            $('span[name="ProductTitle"]').html(obj[2]);
            $('span[name="LevelName"]').html(obj[3]);
            $('a[name="SellMember"]').html(obj[4]);
            $('span[name="KuTsuenQuantity"]').html(obj[5]);
            $('span[name="Price"]').html(obj[6]);
            $('span[name="CreateDate"]').html(obj[7]);
            $('span[name="MemberId"]').html(obj[8]);
            $('a[name="BuyMember"]').html(obj[9]);
            $('span[name="Quantity"]').html(obj[10]);
            $('span[name="SumPrice"]').html(obj[11]);
            $('span[name="RefundQuantity"]').html(obj[12]);
            $('span[name="RefundAmount"]').html(obj[13]);
            $('span[name="HandlingFee"]').html(obj[14]);
            $('span[name="PaymentMethod"]').html(obj[15]);
            $('span[id="PayCode"]').html(obj[16]);
            $('span[name="PayDate"]').html(obj[17]);
            $('span[name="PaymentStatus"]').html(obj[18]);
            $('span[name="Status"]').html(obj[19]);
            $('span[name="ShenHeuState"]').html(obj[20]);
            $('a[name="ShenHeuPerson"]').html(obj[21]);
            $('textarea[name="Remark"]').html(obj[22]);
            $('div[id="PaymentStatus"]').attr('class',obj[23]);
            $('div[id="Status"]').attr('class',obj[24]);
            $('div[id="ShenHeuState"]').attr('class',obj[25]);

        });
    }

    

});