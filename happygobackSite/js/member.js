$(function() {

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Members"){
        $.post("Member/Members", data =>{
            // console.log(data);
            $('tbody[id="MemberFormList"]').html(data);

            $('#MemberTable').DataTable({
                language: {
                    url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 5, 11] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });
            
            $("input[id='Member_edit']").on('click',  function(Event) {
                let MemberId = $(this).data("value");
                sessionStorage.setItem('session_MemberId', $(this).data("value"));
                console.log(MemberId);
                var mapForm = document.createElement("form");
                mapForm.method = "POST";
                mapForm.action = "Member/Members_edit";

                // Create an input
                var mapInput = document.createElement("input");
                mapInput.type = "hidden";
                mapInput.name = "MemberId";
                mapInput.value = MemberId;

                // Add the input to the form
                mapForm.appendChild(mapInput);

                // Add the form to dom
                document.body.appendChild(mapForm);

                // Just submit
                mapForm.submit();
            });
            
            $("input[id='Member_del']").on('click',  function(Event) {
                let MemberId = $(this).data("value");
                // console.log(MemberId);
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                $('span[name="Del_memberId"]').html(MemberId);
                $("input[id='ConfirmDel']").on('click',  function(Event) {
                    console.log(MemberId);
                    $.post("Member/Members_edit", {fun:"MembersDel", MemberId:MemberId}, data =>{
                        console.log(data);
                        eval(data);
                    });
                });
            });
        });

        $("input[id='MemberSearch']").on('click',  function(Event) {
            dttable = $('#MemberTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();
            $.post("Member/Members", {
                MemberId:$('input[id="MemberId"]').val(),
                MemberAccount:$('input[id="MemberAccount"]').val(),
                Isonline:$('select[id="Isonline"]').val(),
                MemberState:$('select[id="MemberState"]').val(),
                MemberName:$('input[id="MemberName"]').val(),
                Cellphone:$('input[id="Cellphone"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            }, data =>{
                // console.log(data);
                $('tbody[id="MemberFormList"]').html(data);

                $('#MemberTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 5, 11] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });
                $("input[id='Member_edit']").on('click',  function(Event) {
                    let MemberId = $(this).data("value");
                    // sessionStorage.setItem('session_MemberId', $(this).data("value"));
                    console.log(MemberId);
                    var mapForm = document.createElement("form");
                    mapForm.method = "POST";
                    mapForm.action = "Member/Members_edit";
    
                    // Create an input
                    var mapInput = document.createElement("input");
                    mapInput.type = "hidden";
                    mapInput.name = "MemberId";
                    mapInput.value = MemberId;
    
                    // Add the input to the form
                    mapForm.appendChild(mapInput);
    
                    // Add the form to dom
                    document.body.appendChild(mapForm);
    
                    // Just submit
                    mapForm.submit();
                });
                $("input[id='Member_del']").on('click',  function(Event) {
                    let MemberId = $(this).data("value");
                    // console.log(MemberId);
                    Event.preventDefault();
                    document.getElementById('popup_del').style.display='block';
                    $('span[name="Del_memberId"]').html(MemberId);
                    $("input[id='ConfirmDel']").on('click',  function(Event) {
                        console.log(MemberId);
                        $.post("Member/Members_edit", {fun:"MembersDel", MemberId:MemberId}, data =>{
                            console.log(data);
                            eval(data);
                        });
                    });
                });
            });
        });
        $("input[id='MemberReset']").on('click',  function(Event) {
            $('input[id="MemberId"]').val("");
            $('input[id="MemberAccount"]').val("");
            $('select[id="Isonline"]').val("");
            $('select[id="MemberState"]').val("");
            $('input[id="MemberName"]').val("");
            $('input[id="Cellphone"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Member_Add"){
        $("input[id='Add_confirm']").on('click',  function(Event) {
            $.post("Member/Member_Add", $('form[id="MemberAddList"]').serialize(),function (data){
                // console.log(data);
                eval(data);
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Members_edit"){
        console.log(sessionStorage.getItem('session_MemberId'));
        $.post("Member/Members_edit", {fun:"MembersEditList", MemberId:sessionStorage.getItem('session_MemberId')}, data =>{
            console.log(data);
            var obj = JSON.parse(data);
            console.log(obj[26]);
            $('input[name="MemberId"]').val(obj[0]);
            $('input[name="MemberAccount"]').val(obj[1]);
            $('input[name="RealName"]').val(obj[2]);
            $('select[name="Sex"]').val(obj[3]);
            $('input[name="CellPhone"]').val(obj[4]);
            $('input[name="IdNumber"]').val(obj[5]);
            $('input[name="IDIssuanceDateY"]').val(obj[6]);
            $('input[name="IDIssuanceDateM"]').val(obj[7]);
            $('input[name="IDIssuanceDateD"]').val(obj[8]);
            $('select[name="IDIssuanceplace"]').val(obj[9]);
            $('select[name="IDIssuance"]').val(obj[10]);
            $('input[name="Email"]').val(obj[11]);
            $('input[name="Address"]').val(obj[12]);
            $('select[name="State"]').val(obj[13]);
            $('select[name="PhoneVerify"]').val(obj[14]);
            $('select[name="IDVerify"]').val(obj[15]);
            $('span[name="CreateDate"]').html(obj[16]);
            $('span[name="RegisterIp"]').html(obj[17]);
            $('span[name="LastLoginDate"]').html(obj[18]);
            $('span[name="LastLoginIp"]').html(obj[19]);
            $('span[name="GamePoints"]').html(obj[20]);
            $('span[name="Transfer"]').html(obj[21]);
            $('span[name="TransferMoney"]').html(obj[22]);
            $('span[name="Tiquan"]').html(obj[23]);
            $('span[name="TiquanMoney"]').html(obj[24]);
            $('a[name="IDPicUrl"]').html(obj[26]);
            $('img[name="IDPicUrlImg"]').attr("src", "../../快易購FrontSide(前台20211025)/IDimage/"+obj[26]);
            
            // eval(data);
        });

        $('form[id="MemberEditList"]').submit(function(e) {
            // var MemberId = sessionStorage.getItem('session_MemberId');
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            formData.append('MemberId',$('input[name="MemberId"]').val());
            formData.append('MemberAccount',$('input[name="MemberAccount"]').val());
            formData.append('fun',"MembersEdit");
            $.ajax({
                //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
                url: "Member/Members_edit",
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
        // $("input[id='edit_confirm']").on('click',  function(Event) {
        //     // console.log(sessionStorage.getItem('session_MemberId'));
        //     $.post("Member/Members_edit",{
        //         fun:"MembersEdit",
        //         MemberId:$('input[name="MemberId"]').val(),
        //         MemberAccount:$('input[name="MemberAccount"]').val(),
        //         RealName:$('input[id="RealName"]').val(),
        //         Sex:$('select[id="Sex"]').val(),
        //         CellPhone:$('input[id="CellPhone"]').val(),
        //         IdNumber:$('input[id="IdNumber"]').val(),
        //         IDIssuanceDateY:$('input[id="IDIssuanceDateY"]').val(),
        //         IDIssuanceDateM:$('input[id="IDIssuanceDateM"]').val(),
        //         IDIssuanceDateD:$('input[id="IDIssuanceDateD"]').val(),
        //         IDIssuanceplace:$('select[id="IDIssuanceplace"]').val(),
        //         IDIssuance:$('select[id="IDIssuance"]').val(),
        //         Email:$('input[id="Email"]').val(),
        //         Address:$('input[id="Address"]').val(),
        //         State:$('select[id="State"]').val(),
        //         PhoneVerify:$('select[id="PhoneVerify"]').val(),
        //         IDVerify:$('select[id="IDVerify"]').val()
        //     }, data =>{
        //         console.log(data);
        //         eval(data);
        //     });
        // });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Members_ip"){
        $.post("Member/Members_ip", data =>{
            // console.log(data);
            $('tbody[id="IPFormList"]').html(data);

            $("input[id='item_search']").on('click',  function(Event) {
                $.post("Member/Members_ip", {
                    MemberId:$('input[id="MemberId"]').val(),
                    MemberIP:$('input[id="MemberIP"]').val(),
                    datepick1:$('input[id="datepick1"]').val(),
                    datepick2:$('input[id="datepick2"]').val()
                }, data =>{
                    // console.log(data);
                    $('tbody[id="IPFormList"]').html(data);
                });
            });

        });

    }




    
});