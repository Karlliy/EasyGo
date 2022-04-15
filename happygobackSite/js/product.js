
$(function() { 

    $.post("Product/Games_info", { fun: "ProductId" }, data => {
        // console.log(data);
        // alert(123);
        let obj = JSON.parse(data);
        // console.log(obj);
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

    $.post("Product/Games_info", { fun: "TypeId" }, data => {
        // console.log(data);
        // alert(123);
        let obj = JSON.parse(data);
        // console.log(obj);
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

    // $.post("Product/Games_info", { fun: "MemberLevel" }, data => {
    //     // console.log(data);
    //     // alert(123);
    //     let obj = JSON.parse(data);
    //     // console.log(obj);
    //     if (obj.ErrorCode) {
    //         alert(obj.ErrorText);
    //     } else {
    //         for(let i=0;i<obj.length;i++){
    //             $("select[id='MemberLevel']").append(
    //             `<option value="${obj[i].RowId}">${obj[i].LevelName}</option>`
    //             );
    //         }
    //     }
    // });

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Games_info"){
        
        // $.post("Order/AddOrder", { fun: "OrderId", val: "2"}, data =>{
        //     // console.log(data);
        //     let obj = JSON.parse(data);
        //     // console.log(obj);
        //     if (obj.ErrorCode) {
        //         alert(obj.ErrorText);
        //     } else {
        //         $('span[id="ProductNumber"]').html(obj);
        //         $('input[name="ProductNumber"]').val(obj.join(''));
        //     }
        // });

    
        $.post("Product/Games_info", data =>{
            // console.log(data);
            $('tbody[id="ProductListForm"]').html(data);

            $("input[id='EditGame']").on('click',  function(Event) {
                let GameId = $(this).data("value");
                console.log(GameId);
                Event.preventDefault();
                document.getElementById('popup_itemedit').style.display='block';

                $.post("Product/Games_info", {fun:"ProductIdEdut",GameId:GameId}, data =>{
                    var obj = JSON.parse(data);
                    console.log(obj[0]);
                    // $('select[name="ProductId"]').val(obj[0]);
                    $('select[id="ProductId"]').val(obj[0]).attr("selected","selected");
                    
                });
                $("input[id='GameInfoEdit']").on('click',  function(Event) {
                    let ProductId = $('select[name="ProductId1"]').val();
                    // console.log($('select[name="ProductId1"]').val());
                    Event.preventDefault();
                    document.getElementById('popup_confirmEdit').style.display='block';

                    $("input[id='ConfirmEdit']").on('click',  function(Event) {
                        $.post("Product/Games_info", {fun:"GamesEdut", ProductId:ProductId, GameId:GameId}, data =>{
                            console.log(data);
                            eval(data);
                        });
                    });
                });
            });

            $("input[id='DelGame']").on('click',  function(Event) {
                let GameId = $(this).data("value");
                console.log(GameId);
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';

                $("input[id='ConfirmDel']").on('click',  function(Event) {
                    $.post("Product/Games_info", {fun:"GamesDel", GameId:GameId}, data =>{
                        console.log(data);
                        eval(data);
                    });
                });
            });
        });
    
        $('form[id="GameForm"]').submit(function(e) {
            // alert('123');
            var id = $(this).data('id');
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            formData['id'] = id;
            formData.append('data',"CreatGame");
            $.ajax({
                //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
                url: "Product/Games_info",
                type: 'POST',
                data:  formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
                    // $('div[id="popup_confirmAdd"]').css('display','inline');
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

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Product"){

        $.post("Product/Product", data =>{
            $('tbody[id="ProductForm"]').html(data);

            $('#ProductTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 2, 9] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });
            
            $("input[id='item_edit']").on('click',  function(Event) {
                let ProductNumber = $(this).data("value");
                sessionStorage.setItem('session_ProductNumber', ProductNumber);
                console.log(ProductNumber);
                var mapForm = document.createElement("form");
                // mapForm.target = "_blank";    
                mapForm.method = "POST";
                mapForm.action = "Product/Product_edit";

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

        $("input[id='ProductSearch']").on('click',  function(Event) {
            dttable = $('#ProductTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();

            $.post("Product/Product", {
                ProductNumber:$('input[id="ProductNumber"]').val(),
                ProductId:$('select[id="ProductId"]').val(),
                TypeId:$('select[id="TypeId"]').val(),
                MemberAccount:$('input[id="MemberAccount"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="ProductForm"]').html(data);

                $('#ProductTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 2, 9] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });
                $("input[id='item_edit']").on('click',  function(Event) {
                    let ProductNumber = $(this).data("value");
                    sessionStorage.setItem('session_ProductNumber', ProductNumber);
                    console.log(ProductNumber);
                    var mapForm = document.createElement("form");
                    // mapForm.target = "_blank";    
                    mapForm.method = "POST";
                    mapForm.action = "Product/Product_edit";
    
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

        $("input[id='ProductReset']").on('click',  function(Event) {
            $('input[id="ProductNumber"]').val("");
            $('select[id="ProductId"]').val("");
            $('select[id="TypeId"]').val("");
            $('input[id="MemberAccount"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Product_edit"){
        console.log(sessionStorage.getItem('session_ProductNumber'));
        $.post("Product/Product_edit", {fun:"ProductList" ,ProductNumber:sessionStorage.getItem('session_ProductNumber')}, data =>{
            var obj = JSON.parse(data);
            console.log(obj[5]);
            if(obj[0] == 1 ){
                $('input[name="ShelfState"]').attr('checked', true);
                $('input[name="ShelfState"]').attr('value', 1);
            }else{
                $('input[name="ShelfState"]').attr('checked', false);
                $('input[name="ShelfState"]').attr('value', 0);
            }
            $('span[name="ProductNumber"]').html(obj[1]);
            $('input[name="ProductNumber"]').val(obj[1]);
            $('span[name="CreateDate"]').html(obj[2]);
            $('select[name="MemberLevel"]').val(obj[3]);
            $('span[name="MemberAccount"]').html(obj[4]);
            $('input[name="MemberAccount"]').val(obj[4]);
            $('input[name="FileName"]').html(obj[5]);
            $('img[name="FileName"]').attr("src", "../../快易購FrontSide(前台20211025)/picturedata/"+obj[5]);
            $('select[name="ProductId"]').val(obj[6]);
            $('select[name="TypeId"]').val(obj[7]);
            $('input[name="PointCardKind"]').html(obj[8]);
            $('textarea[name="ProductTitle"]').html(obj[9]);
            $('input[name="GameName"]').val(obj[10]);
            $('select[name="GamePlatform"]').val(obj[11]);
            $('input[name="GameServer"]').val(obj[12]);
            $('input[name="OrderQuantity"]').val(obj[13]);
            $('input[name="CurrencyValue"]').val(obj[14]);
            $('select[name="Currency"]').val(obj[15]);
            $('input[name="Price"]').val(obj[16]);
            $('input[name="KuTsuenQuantity"]').val(obj[17]);
            $('textarea[name="ProductInfo"]').html(obj[18]);
            $('input[name="FileInfo1"]').html(obj[19]);
            $('img[name="FileInfo1"]').attr("src", "../../快易購FrontSide(前台20211025)/Infoimg1/"+obj[19]);
            $('input[name="FileInfo2"]').html(obj[20]);
            $('img[name="FileInfo2"]').attr("src", "../../快易購FrontSide(前台20211025)/Infoimg2/"+obj[20]);
            $('textarea[name="Remark"]').html(obj[21]);
            $('span[name="MemberCreate"]').html(obj[22]);
            $('span[name="RegisterIp"]').html(obj[23]);
            $('span[name="LoginNum"]').html(obj[24]);
            $('span[name="LastLoginDate"]').html(obj[25]);
            $('span[name="LastLoginIp"]').html(obj[26]);
        });
    
        $('form[id="ProductFormEdit"]').submit(function(e) {
            // var id = $(this).data('id');
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            // formData['id'] = id;
            formData.append('data',"ProductFormEdit");
            $.ajax({
                //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
                url: "Product/Product_edit",
                type: 'POST',
                data:  formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
                    // $('div[id="popup_confirmEdit"]').css('display','inline');
                    // document.getElementById('popup_confirmEdit').style.display='block';
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

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Games_type"){
        $.post("Product/Games_type", {fun:"GamesTypeList"}, data =>{

            $('tbody[id="Games_type"]').html(data);

            $("input[id='item_edit']").on('click',  function(Event) {
                let ProductId = $(this).data("value");
                console.log(ProductId);
                Event.preventDefault();
                document.getElementById('popup_itemedit').style.display='block';

                $.post("Product/Games_type", {val:ProductId}, data =>{
                    var obj = JSON.parse(data);
                    $('input[name="ProductNameEdit"]').val(obj[0]);

                });
                $("input[id='edit_confirm']").on('click',  function(Event) {
                    let ProductNameEdit = $('input[id="ProductNameEdit"]').val();
                    console.log(ProductNameEdit);
                    Event.preventDefault();
                    document.getElementById('popup_confirmEdit').style.display='block';

                    $("input[id='ConfirmEdit']").on('click',  function(Event) {
                        $.post("Product/Games_type", {fun:"GamesTypeEdit", ProductId:ProductId, ProductName:ProductNameEdit}, data =>{
                            console.log(data);
                            eval(data);
                        });
                    });
                });
            });

            $("input[id='item_del']").on('click',  function(Event) {
                let ProductId = $(this).data("value");
                console.log(ProductId);
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';

                $("input[id='ConfirmDel']").on('click',  function(Event) {
                    $.post("Product/Games_type", {fun:"GamesTypeDel", ProductId:ProductId}, data =>{
                        console.log(data);
                        eval(data);
                    });
                });
            });
            
            
        });

        $("input[id='add_confirm']").on('click',  function(Event) {
            let ProductName = $('input[id="ProductName"]').val();
            // sessionStorage.setItem('session_ProductName', ProductName);
            console.log(ProductName);
            Event.preventDefault();
            document.getElementById('popup_confirmAdd').style.display='block';
            
            $("input[id='ConfirmAdd']").on('click',  function(Event) {
                console.log(ProductName);
                // console.log($('input[name="members"]:checked').val());
                // console.log($('textarea[id="OrderBuyEvaluInfo"]').val());
                $.post("Product/Games_type", {fun:"GamesTypeInsert", ProductName:ProductName}, data =>{
                    console.log(data);
                    eval(data);
                });
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Product_type"){
        $.post("Product/Product_type", {fun:"ProductTypeList"}, data =>{

            $('tbody[id="Product_type"]').html(data);

            $("input[id='item_edit']").on('click',  function(Event) {
                let TypeId = $(this).data("value");
                console.log(TypeId);
                Event.preventDefault();
                document.getElementById('popup_itemedit').style.display='block';

                $.post("Product/Product_type", {val:TypeId}, data =>{
                    var obj = JSON.parse(data);
                    $('input[name="TypeNameEdit"]').val(obj[0]);

                });
                $("input[id='edit_confirm']").on('click',  function(Event) {
                    let TypeNameEdit = $('input[id="TypeNameEdit"]').val();
                    console.log(TypeNameEdit);
                    Event.preventDefault();
                    document.getElementById('popup_confirmEdit').style.display='block';

                    $("input[id='ConfirmEdit']").on('click',  function(Event) {
                        $.post("Product/Product_type", {fun:"ProductTypeEdit", TypeId:TypeId, TypeName:TypeNameEdit}, data =>{
                            console.log(data);
                            eval(data);
                        });
                    });
                });
            });

            $("input[id='item_del']").on('click',  function(Event) {
                let TypeId = $(this).data("value");
                console.log(TypeId);
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';

                $("input[id='ConfirmDel']").on('click',  function(Event) {
                    $.post("Product/Product_type", {fun:"ProductTypeDel", TypeId:TypeId}, data =>{
                        console.log(data);
                        eval(data);
                    });
                });
            });
            
            
        });

        $("input[id='add_confirm']").on('click',  function(Event) {
            let TypeName = $('input[id="TypeName"]').val();
            // sessionStorage.setItem('session_ProductName', ProductName);
            console.log(TypeName);
            Event.preventDefault();
            document.getElementById('popup_confirmAdd').style.display='block';
            
            $("input[id='ConfirmAdd']").on('click',  function(Event) {
                console.log(TypeName);
                // console.log($('input[name="members"]:checked').val());
                // console.log($('textarea[id="OrderBuyEvaluInfo"]').val());
                $.post("Product/Product_type", {fun:"ProductTypeInsert", TypeName:TypeName}, data =>{
                    console.log(data);
                    eval(data);
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