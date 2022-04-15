
$(function() { 



    $('input[id="item_add"]').on('click', function (Event) { 
        Event.preventDefault();
        document.getElementById('popup_itemadd').style.display='block';
        var today = new Date();
        // var today = '2021-9-4 9:7:5';
        let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
        let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
        let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
        let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
        let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
        // console.log(Month);console.log(Datee);console.log(Hours);console.log(Minutes);console.log(Seconds);
        var Day = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
		$('span[id="ReleaseTime"]').html(Day);
        // console.log(Day);
	});
    $('input[id="add_confirm"]').on('click', function (Event) { 
        Event.preventDefault();
        document.getElementById('popup_confirmAdd').style.display='block';
        $('input[id="confirmAdd"]').on('click', function (Event) { 
            var ReleaseTime = $('span[id="ReleaseTime"]').html();
            var AddPersonnel = $('input[id="AddPersonnel"]').val();
            var Detail = $('textarea[id="Detail"]').val();
            console.log(ReleaseTime);
            console.log(AddPersonnel);
            console.log(Detail);
            $.post("Message/News", {fun: "AddNews", ReleaseTime:ReleaseTime,AddPersonnel:AddPersonnel,Detail:Detail}, data =>{
                eval(data);
            });
        });
    });

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "News"){
        $.post("Message/News", {fun: "NewsList"}, data =>{
            $('tbody[id="NewsList"]').html(data);

            $('#NewsTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 2] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[name='item_edit']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_itemedit').style.display='block';
                let val = $(this).data('value');
                sessionStorage.setItem('session_Newsrow', val);
                // console.log(val);
                $.post("Message/News", {val:val}, data =>{
                    let obj = JSON.parse(data);
                    if(obj[1] == 1 ){
                        $('input[name="onoffswitch1"]').attr('checked', true);
                        $('input[name="onoffswitch1"]').attr('value', 1);
                    }else{
                        $('input[name="onoffswitch1"]').attr('checked', false);
                        $('input[name="onoffswitch1"]').attr('value', 0);
                    }
                    $('span[name="ReleaseTimeedit"]').html(obj[2]);
                    $('span[name="Personneledit"]').html(obj[3]);
                    $('textarea[name="Detailedit"]').html(obj[4]);
                });
            });   
            $("input[id='edit_confirm']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_confirmEdit').style.display='block';        
                $('input[id="confirmedit"]').on('click', function (Event) { 
                    var ReleaseTime = $('span[id="ReleaseTimeedit"]').html();
                    var Personnel = $('span[id="Personneledit"]').html();
                    var Detail = $('textarea[id="Detailedit"]').val();
                    var Open_or_Close = $('input[name="onoffswitch1"]').val();
                    console.log(sessionStorage.getItem('session_Newsrow'));
                    console.log(ReleaseTime);
                    console.log(Personnel);
                    console.log(Detail);
                    console.log(Open_or_Close);
                    $.post("Message/News", {fun: "EditNews", ReleaseTime:ReleaseTime,Personnel:Personnel,Detail:Detail,Open_or_Close:Open_or_Close,Row:sessionStorage.getItem('session_Newsrow')}, data =>{
                        eval(data);
                    });
                });
            });
            $("input[name='item_del']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                let val_del = $(this).data('value');
                sessionStorage.setItem('session_Newsrow_del', val_del);
            });
            $('input[id="confirmdel"]').on('click', function (Event) {
                console.log(sessionStorage.getItem('session_Newsrow_del'));
                $.post("Message/News", {fun: "DelNews", Row:sessionStorage.getItem('session_Newsrow_del')}, data =>{
                    eval(data);
                });
            });
        });

        $("input[id='NewsSearch']").on('click',  function(Event) {
            dttable = $('#NewsTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Message/News", {fun: "NewsList",
                Personnel:$('input[id="Personnel"]').val(),
                Open_or_Close:$('select[id="Open_or_Close"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                $('tbody[id="NewsList"]').html(data);

                $('#NewsTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 2] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });

                $("input[name='item_edit']").on('click',  function(Event) {
                    Event.preventDefault();
                    document.getElementById('popup_itemedit').style.display='block';
                    let val = $(this).data('value');
                    sessionStorage.setItem('session_Newsrow', val);
                    // console.log(val);
                    $.post("Message/News", {val:val}, data =>{
                        let obj = JSON.parse(data);
                        if(obj[1] == 1 ){
                            $('input[name="onoffswitch1"]').attr('checked', true);
                            $('input[name="onoffswitch1"]').attr('value', 1);
                        }else{
                            $('input[name="onoffswitch1"]').attr('checked', false);
                            $('input[name="onoffswitch1"]').attr('value', 0);
                        }
                        $('span[name="ReleaseTimeedit"]').html(obj[2]);
                        $('span[name="Personneledit"]').html(obj[3]);
                        $('textarea[name="Detailedit"]').html(obj[4]);
                    });
                });   
                $("input[id='edit_confirm']").on('click',  function(Event) {
                    Event.preventDefault();
                    document.getElementById('popup_confirmEdit').style.display='block';        
                    $('input[id="confirmedit"]').on('click', function (Event) { 
                        var ReleaseTime = $('span[id="ReleaseTimeedit"]').html();
                        var Personnel = $('span[id="Personneledit"]').html();
                        var Detail = $('textarea[id="Detailedit"]').val();
                        var Open_or_Close = $('input[name="onoffswitch1"]').val();
                        console.log(sessionStorage.getItem('session_Newsrow'));
                        console.log(ReleaseTime);
                        console.log(Personnel);
                        console.log(Detail);
                        console.log(Open_or_Close);
                        $.post("Message/News", {fun: "EditNews", ReleaseTime:ReleaseTime,Personnel:Personnel,Detail:Detail,Open_or_Close:Open_or_Close,Row:sessionStorage.getItem('session_Newsrow')}, data =>{
                            eval(data);
                        });
                    });
                });
                $("input[name='item_del']").on('click',  function(Event) {
                    Event.preventDefault();
                    document.getElementById('popup_del').style.display='block';
                    let val_del = $(this).data('value');
                    sessionStorage.setItem('session_Newsrow_del', val_del);
                });
                $('input[id="confirmdel"]').on('click', function (Event) {
                    console.log(sessionStorage.getItem('session_Newsrow_del'));
                    $.post("Message/News", {fun: "DelNews", Row:sessionStorage.getItem('session_Newsrow_del')}, data =>{
                        eval(data);
                    });
                });
            });
        });

        $("input[id='NewsReset']").on('click',  function(Event) {
            $('input[id="Personnel"]').val("");
            $('select[id="Open_or_Close"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "QA"){
        $.post("Message/QA", {fun: "QAFormList"}, data =>{
            $('tbody[id="QAFormList"]').html(data);
            
            $('#QATable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 3, 4, 5] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });
            
            $("input[id='QAListBtn']").on('click',  function(Event) {
                let QAProductNumber = $(this).data('value');
                sessionStorage.setItem('session_QAProductNumber', QAProductNumber);
                // console.log(QAProductNumber);
                
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Message/QAList";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "QAProductNumber";
                mapInput.value = QAProductNumber;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });
                       
        });
        $("input[id='QASearch']").on('click',  function(Event) {
            dttable = $('#QATable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            // console.log($('input[id="ProductNumber"]').val());
            $.post("Message/QA", {fun: "QAFormList",
                ProductNumber:$('input[id="ProductNumber"]').val(),
                BuyMemberId:$('input[id="BuyMemberId"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="QAFormList"]').html(data);

                $('#QATable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 3, 4, 5] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });

                $("input[id='QAListBtn']").on('click',  function(Event) {
                    let QAProductNumber = $(this).data('value');
                    sessionStorage.setItem('session_QAProductNumber', QAProductNumber);
                    console.log(QAProductNumber);
                    
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Message/QAList";
    
                    // Create an input
                    var mapInput = document.createElement("input");
                    mapInput.type = "hidden";
                    mapInput.name = "QAProductNumber";
                    mapInput.value = QAProductNumber;
    
                    // Add the input to the form
                    mapForm.appendChild(mapInput);
    
                    // Add the form to dom
                    document.body.appendChild(mapForm);
    
                    // Just submit
                    mapForm.submit();
                });
            });
        });

        $("input[id='QAReset']").on('click',  function(Event) {
            $('input[id="ProductNumber"]').val("");
            $('input[id="BuyMemberId"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "QAList"){
        $.post("Message/QAList", {fun: "TopQAList", QAProductNumber:sessionStorage.getItem('session_QAProductNumber')}, data =>{
            let obj = JSON.parse(data);
            // console.log(data);
            $('span[name="MemberAccount"]').html(obj[0]);
            $('span[name="ProductNumber"]').html(obj[1]);
            $('span[name="ProductTitle"]').html(obj[2]);
            $('span[name="QAStatus"]').html(obj[3]);                  
        });

        $.post("Message/QAList", {fun: "QAList", QAProductNumber:sessionStorage.getItem('session_QAProductNumber')}, data =>{
            dttable = $('#QAListTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $('tbody[id="QAList"]').html(data);  

            $('#QAListTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 2, 4] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='QAListDetail']").on('click',  function(Event) {
                let QARowId = $(this).data('value');
                sessionStorage.setItem('session_QARowId', QARowId);
                console.log(QARowId);
                
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Message/QADetail";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "QARowId";
                mapInput.value = QARowId;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });

            $("input[id='item_del']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                let RowId = $(this).data('value');
                console.log(RowId);
                $("input[id='QAConfirmDel']").on('click',  function(Event) {
                    $.post("Message/QAList", {fun: "TopQAListDel", RowId:RowId}, data =>{
                        eval(data);                       
                    });
                });
            });                      
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "QADetail"){
        $.post("Message/QADetail", {fun:"TopQADetail",QARowId:sessionStorage.getItem('session_QARowId')}, data =>{
            let obj = JSON.parse(data);
            console.log(data);
            $('span[name="ProductNumber"]').html(obj[0]);
            $('span[name="ProductTitle"]').html(obj[1]);
            $('span[name="TypeName"]').html(obj[2]);
            $('span[name="MemberId"]').html(obj[3]); 
            $('span[name="CreateDate"]').html(obj[4]);  
        });

        $.post("Message/QADetail", {fun:"QADetail",QARowId:sessionStorage.getItem('session_QARowId')}, data =>{
            console.log(data);
            $('tbody[id="QADetailList"]').html(data);  
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailSystem"){
        $.post("Message/MailSystem", data =>{
            $('tbody[id="MailSystem"]').html(data);

            $('#MailSystemTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 3, 4, 5] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='MailSystemChk']").on('click',  function(Event) {
                let RowId = $(this).data("value");
                sessionStorage.setItem('session_MailSystemRowId', RowId);
                // console.log(sessionStorage.getItem('session_MailMembersRowId'));
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Message/MailSystemEdit";

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

            $("input[id='MailSystemDel']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                let RowId = $(this).data('value');
                console.log(RowId);
                $("input[id='MailSystemDelConfirm']").on('click',  function(Event) {
                    $.post("Message/MailSystemEdit", {fun: "MailSystemDel", RowId:RowId}, data =>{
                        eval(data);                       
                    });
                });
            });                  
        });

        $("input[id='SystemSearch']").on('click',  function(Event) {
            dttable = $('#MailSystemTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Message/MailSystem", {
                SystemKeyWordSearch:$('input[id="SystemKeyWordSearch"]').val(),
                SystemPersonnelSearch:$('input[id="SystemPersonnelSearch"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="MailSystem"]').html(data);

                $('#MailSystemTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 3, 4, 5] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });

                $("input[id='MailSystemChk']").on('click',  function(Event) {
                    let RowId = $(this).data("value");
                    sessionStorage.setItem('session_MailSystemRowId', RowId);
                    // console.log(sessionStorage.getItem('session_MailMembersRowId'));
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Message/MailSystemEdit";
    
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
            });
        });

        $("input[id='SystemReset']").on('click',  function(Event) {
            $('input[id="SystemKeyWordSearch"]').val("");
            $('input[id="SystemPersonnelSearch"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailSystemEdit") {
        // console.log(sessionStorage.getItem('session_MailSystemRowId'));
        $.post("Message/MailSystemEdit", {fun:"MailSystemEdit",RowId:sessionStorage.getItem('session_MailSystemRowId')}, data =>{
            // console.log(data);
            var obj = JSON.parse(data);
            $('span[name="MailCreateDate"]').html(obj[0]);
            $('span[name="CreatePersonnel"]').html(obj[1]);
            $('span[name="AnnouncementTitle"]').html(obj[2]);
            $('span[name="AnnouncementInfo"]').html(obj[3]);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailSystemAdd") {
        var today = new Date();
        let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
        let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
        let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
        let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
        let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
        var Day = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
		$('span[name="MailSystemTime"]').html(Day);

        $.post("Message/MailSystemAdd", {fun:"MailSystemCreatePersonnel"}, data =>{
            var obj = JSON.parse(data);
            $('span[name="MailSystemPersonnel"]').html(obj[0]);
        });

        console.log(Day);
        $("input[id='MailSystemCHK']").on('click',  function(Event) {
            Event.preventDefault();
            document.getElementById('popup_sendConfirm').style.display='block';
        });
        $("input[id='MailSystemConfirm']").on('click',  function(Event) {
            let MailSystemTime = $('span[id="MailSystemTime"]').html();
            let MailSystemPersonnel = $('span[id="MailSystemPersonnel"]').html();
            let MailSystemTitle = $('input[id="MailSystemTitle"]').val();
            let MailSystemInfo = $('textarea[id="MailSystemInfo"]').val();
            console.log(MailSystemTime);console.log(MailSystemPersonnel); console.log(MailSystemTitle); console.log(MailSystemInfo);
            $.post("Message/MailSystemAdd", {MailSystemTime:MailSystemTime,MailSystemPersonnel:MailSystemPersonnel,MailSystemTitle:MailSystemTitle,MailSystemInfo:MailSystemInfo}, data =>{
                // console.log(data);
                eval(data);
            });
        });
        $("input[id='MailSystemReset']").on('click',  function(Event) {
            $('input[id="MailSystemTitle"]').val("");
            $('textarea[id="MailSystemInfo"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMember"){
        $.post("Message/MailMember", data =>{
            $('tbody[id="MailMember"]').html(data); 

            $('#MailMemberTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 3, 4, 5, 6, 7] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='MailMemberChk']").on('click',  function(Event) {
                let RowId = $(this).data("value");
                sessionStorage.setItem('session_MailMemberRowId', RowId);
                // console.log(sessionStorage.getItem('session_MailMembersRowId'));
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Message/MailMemberEdit";

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
            $("input[id='MailMemberDel']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                let RowId = $(this).data('value');
                console.log(RowId);
                $("input[id='MailMemberDelConfirm']").on('click',  function(Event) {
                    $.post("Message/MailMemberEdit", {fun: "MailMemberDel", RowId:RowId}, data =>{
                        eval(data);                       
                    });
                });
            });                      
        });

        $("input[id='MemberSearch']").on('click',  function(Event) {
            dttable = $('#MailMemberTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Message/MailMember", {
                MemberKeyWordSearch:$('input[id="MemberKeyWordSearch"]').val(),
                MemberAccountSearch:$('input[id="MemberAccountSearch"]').val(),
                MemberPersonnelSearch:$('input[id="MemberPersonnelSearch"]').val(),
                AnnouncementState:$('select[id="AnnouncementState"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="MailMember"]').html(data);

                $('#MailMemberTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 3, 4, 5, 6, 7] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });

                $("input[id='MailMemberChk']").on('click',  function(Event) {
                    let RowId = $(this).data("value");
                    sessionStorage.setItem('session_MailMemberRowId', RowId);
                    // console.log(sessionStorage.getItem('session_MailMembersRowId'));
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Message/MailMemberEdit";
    
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
            });
        });

        $("input[id='MemberReset']").on('click',  function(Event) {
            $('input[id="MemberKeyWordSearch"]').val("");
            $('input[id="MemberAccountSearch"]').val("");
            $('input[id="MemberPersonnelSearch"]').val("");
            $('select[id="AnnouncementState"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMemberEdit") {
        console.log(sessionStorage.getItem('session_MailMemberRowId'));
        $.post("Message/MailMemberEdit", {fun:"MailMemberEdit",RowId:sessionStorage.getItem('session_MailMemberRowId')}, data =>{
            // console.log(data);
            var obj = JSON.parse(data);
            $('span[name="MailCreateDate"]').html(obj[0]);
            $('span[name="MemberAccount"]').html(obj[1]);
            $('span[name="CreatePersonnel"]').html(obj[2]);
            $('span[name="AnnouncementTitle"]').html(obj[3]);
            $('span[name="AnnouncementInfo"]').html(obj[4]);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "MailMemberAdd") {
        var today = new Date();
        let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
        let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
        let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
        let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
        let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
        var Day = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
		$('span[name="MailMemberTime"]').html(Day);

        $.post("Message/MailMemberAdd", {fun:"MailMemberCreatePersonnel"}, data =>{
            var obj = JSON.parse(data);
            $('span[name="MailMemberPersonnel"]').html(obj[0]);
        });

        console.log(Day);
        $("input[id='MailMemberCHK']").on('click',  function(Event) {
            Event.preventDefault();
            document.getElementById('popup_sendConfirm').style.display='block';
        });
        $("input[id='MailMemberConfirm']").on('click',  function(Event) {
            let MailMemberTime = $('span[id="MailMemberTime"]').html();
            let MailMemberMemberAccount = $('textarea[id="MailMemberMemberAccount"]').val();
            let MailMemberPersonnel = $('span[id="MailMemberPersonnel"]').html();
            let MailMemberTitle = $('input[id="MailMemberTitle"]').val();
            let MailMemberInfo = $('textarea[id="MailMemberInfo"]').val();
            console.log(MailMemberTime);console.log(MailMemberMemberAccount);console.log(MailMemberPersonnel); console.log(MailMemberTitle); console.log(MailMemberInfo);
            $.post("Message/MailMemberAdd", {MailMemberTime:MailMemberTime,MailMemberMemberAccount:MailMemberMemberAccount,MailMemberPersonnel:MailMemberPersonnel,MailMemberTitle:MailMemberTitle,MailMemberInfo:MailMemberInfo}, data =>{
                // console.log(data);
                eval(data);
            });
        });
        $("input[id='MailMemberReset']").on('click',  function(Event) {
            $('textarea[id="MailMemberMemberAccount"]').val("");
            $('input[id="MailMemberTitle"]').val("");
            $('textarea[id="MailMemberInfo"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Complain") {
        $.post("Message/Complain", data =>{
            $('tbody[id="ComplainList"]').html(data); 

            $('#ComplainTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 3, 4, 6, 7] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='ComplainChk']").on('click',  function(Event) {
                let RowId = $(this).data("value");
                sessionStorage.setItem('session_ComplainRowId', RowId);
                console.log(sessionStorage.getItem('session_ComplainRowId'));
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Message/ComplainReply";

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
        });


        $("input[id='ComplainSearch']").on('click',  function(Event) {
            dttable = $('#ComplainTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Message/Complain", {
                AppealEvent:$('select[id="AppealEvent"]').val(),
                AppealTitle:$('input[id="AppealTitle"]').val(),
                AppealMemberAccount:$('input[id="AppealMemberAccount"]').val(),
                AppealPersonnel:$('input[id="AppealPersonnel"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="ComplainList"]').html(data);

                $('#ComplainTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 3, 4, 6, 7] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });

                $("input[id='ComplainChk']").on('click',  function(Event) {
                    let RowId = $(this).data("value");
                    sessionStorage.setItem('session_ComplainRowId', RowId);
                    // console.log(sessionStorage.getItem('session_ComplainRowId'));
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Message/ComplainReply";
    
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
            });
        });

        $("input[id='ComplainReset']").on('click',  function(Event) {
            $('select[id="AppealEvent"]').val("");
            $('input[id="AppealTitle"]').val("");
            $('input[id="AppealMemberAccount"]').val("");
            $('input[id="AppealPersonnel"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "ComplainReply") {
        var today = new Date();
        let Month = ((today.getMonth()+1) < 10) ? ('0'+(today.getMonth()+1)) : (today.getMonth()+1);
        let Datee = (today.getDate() < 10) ? ('0'+today.getDate()) : today.getDate();
        let Hours = (today.getHours() < 10) ? ('0'+today.getHours()): today.getHours();
        let Minutes = (today.getMinutes() < 10) ? ('0'+today.getMinutes()) : today.getMinutes();
        let Seconds = (today.getSeconds() < 10) ? ('0'+today.getSeconds()) : today.getSeconds();
        var Day = today.getFullYear()+"-"+Month+"-"+Datee+" "+Hours+":"+Minutes+":"+Seconds;
		$('span[name="ComplainTime"]').html(Day);

        $.post("Message/ComplainReply", {fun:"ComplainReplyList",RowId:sessionStorage.getItem('session_ComplainRowId')}, data =>{
            var obj = JSON.parse(data);
            $('span[name="MailCreateDate"]').html(obj[0]);
            $('span[name="AppealDate"]').html(obj[1]);
            $('span[name="AppealName"]').html(obj[2]);
            $('span[name="MemberAccount"]').html(obj[3]);
            $('span[name="ProductNumber"]').html(obj[4]);
            $('span[name="OrderNumber"]').html(obj[5]);
            $('span[name="AppealObject"]').html(obj[6]);
            $('span[name="AppealTitle"]').html(obj[7]);
            $('textarea[name="AppealContent"]').html(obj[8]);
            $('span[name="Appealfile"]').html(obj[9]);
            $('img[name="Appealfile"]').attr("src","../../快易購FrontSide(前台20211025)/appealImg/"+obj[9]);
            $('span[name="ReplyDate"]').html(obj[10]);
            $('input[name="ReplyTitle"]').val(obj[11]);
            $('textarea[name="ReplyContent"]').val(obj[12]);
            $('span[name="AppealPersonnel"]').html(obj[13]);
        });

        $("input[id='ComplainSend']").on('click',  function(Event) {
            Event.preventDefault();
            document.getElementById('popup_sendConfirm').style.display='block';
        });
        $("input[id='ComplainConfirm']").on('click',  function(Event) {
            let ComplainTime = $('span[id="ComplainTime"]').html();
            let AppealPersonnel = $('span[id="AppealPersonnel"]').html();
            let ReplyTitle = $('input[id="ReplyTitle"]').val();
            let ReplyContent = $('textarea[id="ReplyContent"]').val();
            console.log(ComplainTime);console.log(AppealPersonnel);console.log(ReplyTitle); console.log(ReplyContent);
            $.post("Message/ComplainReply", {fun:"ComplainReply",RowId:sessionStorage.getItem('session_ComplainRowId'),ComplainTime:ComplainTime,AppealPersonnel:AppealPersonnel,ReplyTitle:ReplyTitle,ReplyContent:ReplyContent}, data =>{
                // console.log(data);
                eval(data);
            });
        });

        $("input[id='ComplainClean']").on('click',  function(Event) {
            $('input[id="ReplyTitle"]').val("");
            $('textarea[id="ReplyContent"]').val("");
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