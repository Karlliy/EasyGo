
$(function() { 

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Dashboard"){
        $.post("Home/Dashboard", data =>{
            console.log(data);
            let obj = JSON.parse(data);
            $('div[name="NewDayMember"]').html(obj[0]);
            $('div[name="NewWeekMember"]').html(obj[1]);
            $('div[name="NewMounthMember"]').html(obj[2]);
            $('div[name="NewDayOrder"]').html(obj[3]);
            $('div[name="NewWeekOrder"]').html(obj[4]);
            $('div[name="NewMounthOrder"]').html(obj[5]);
            $('span[name="NewDayMember"]').html(obj[0]);
            $('span[name="SumPrice"]').html(obj[6]);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Banner"){
        $.post("Home/Banner", data =>{
            // console.log(data);
            $('tbody[id="BannerListForm"]').html(data);

            $("input[id='item_edit']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_itemedit').style.display='block';
                let val = $(this).data('value');
                sessionStorage.setItem('session_Bannerrow', val);
                // console.log(val);
                $.post("Home/Banner", {fun:"EditBannerList", val:val}, data =>{
                    // console.log(data);
                    let obj = JSON.parse(data);
                    $('input[name="EditId"]').val(obj[1]);
                    $('input[name="EditBannerInfo"]').val(obj[2]);
                    // $('textarea[name="Detailedit"]').html(obj[4]);
                    
                });
                $('form[id="EditBannerForm"]').submit(function(e) {
                    // alert(sessionStorage.getItem('session_Bannerrow'));
                    // var id = $(this).data('id');
                    // var formObj = $(this);
                    // var formURL = formObj.attr("action");
                    var formData = new FormData(this);
                    formData.append('RowId',sessionStorage.getItem('session_Bannerrow'));
                    formData.append('data',"EditBanner");
                    $.ajax({
                        //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
                        url: "Home/Banner",
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
            });
            $("input[id='item_del']").on('click',  function(Event) {
                Event.preventDefault();
                document.getElementById('popup_del').style.display='block';
                let val = $(this).data('value');
                console.log(val);
                $("input[id='confirmDel']").on('click',  function(Event) {confirmDel
                    $.post("Home/Banner", {fun:"DelBanner", val:val}, data =>{
                        eval(data);
                    });
                });
            });
        });

        
        $('form[id="BannerForm"]').submit(function(e) {
            // alert('123');
            // var id = $(this).data('id');
            // var formObj = $(this);
            // var formURL = formObj.attr("action");
            var formData = new FormData(this);
            // formData['id'] = id;
            formData.append('data',"CreateBanner");
            $.ajax({
                //url: window.location.href.substr(window.location.href.lastIndexOf("/")+1),            
                url: "Home/Banner",
                type: 'POST',
                data:  formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
                    // $('div[id="popup_confirmAdd"]').css('display','inline');
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
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Permission"){
        $.post("Home/Permission", data =>{
            // console.log(data);
            $('tbody[id="PermissionList"]').html(data);
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Change_pw"){
        $("input[id='AdminPWBtn']").on('click',  function(Event) {
            console.log($('input[id="AdminPWOld"]').val());
            console.log($('input[id="AdminPW"]').val());
            console.log($('input[id="AdminPWCheck"]').val());
            $.post("Home/Change_pw", {AdminPWOld:$('input[id="AdminPWOld"]').val(), AdminPW:$('input[id="AdminPW"]').val(), AdminPWCheck:$('input[id="AdminPWCheck"]').val()}, data =>{
                // console.log(data);
                alert("密碼更變成功!!!");
                eval(data);
            });
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Bank"){
        $.post("Home/Bank", data =>{
            // console.log(data);
            $('tbody[id="BankList"]').html(data);

            $('#BankTable').DataTable({
                language: {
                        url: "js/Chinese-traditional.json"
                },
                pagingType: "full_numbers",
                lengthMenu: [5, 10, 20, 50],
                pageLength: 5,
                searching: false,
                destroy: true,
                columnDefs: [
                    { orderable: false, targets: [0, 2, 5, 6, 8, 9] }
                ],
                order: [0, "desc"],
                "dom": `<<t>>
                        <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
            });

            $("input[id='ReCalculate']").on('click',  function(Event) {//重新結算
                let WithdrawNumber = $(this).data('value');
                console.log(WithdrawNumber);
                $.post("Home/Bank", {fun:"ReCalculate",WithdrawNumber:WithdrawNumber}, data =>{
                    // console.log(data);
                    alert("更變成功!!!");
                    eval(data);
                });
            });
            $("input[id='Pass']").on('click',  function(Event) {//通過
                let WithdrawNumber = $(this).data('value');
                console.log(WithdrawNumber);
                $.post("Home/Bank", {fun:"Pass",WithdrawNumber:WithdrawNumber}, data =>{
                    // console.log(data);
                    alert("更變成功!!!");
                    eval(data);
                });
            });
            $("input[id='Reject']").on('click',  function(Event) {//拒絕
                let WithdrawNumber = $(this).data('value');
                console.log(WithdrawNumber);
                $.post("Home/Bank", {fun:"Reject",WithdrawNumber:WithdrawNumber}, data =>{
                    // console.log(data);
                    alert("更變成功!!!");
                    eval(data);
                });
            });
        });

        $("input[id='BankSearch']").on('click',  function(Event) {
            
            dttable = $('#BankTable').dataTable();
            dttable.fnClearTable(); //清空一下table
            dttable.fnDestroy();

            $.post("Home/Bank", {
                WithdrawNumber:$('input[id="WithdrawNumber"]').val(),
                MemberAccount:$('input[id="MemberAccount"]').val(),
                WithdrawState:$('select[id="WithdrawState"]').val(),
                datepick1:$('input[id="datepick1"]').val(),
                datepick2:$('input[id="datepick2"]').val()
            },data =>{
                console.log(data);
                $('tbody[id="BankList"]').html(data);

                $('#BankTable').DataTable({
                    language: {
                        url: "js/Chinese-traditional.json"
                    },
                    pagingType: "full_numbers",
                    lengthMenu: [5, 10, 20, 50],
                    pageLength: 5,
                    searching: false,
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 2, 5, 6, 8, 9] }
                    ],
                    order: [0, "desc"],
                    "dom": `<<t>>
                            <'col50_left'<'col_inline'i><'col_inline'l>><'col50_right'p>`
                });
    
                $("input[id='ReCalculate']").on('click',  function(Event) {//重新結算
                    let WithdrawNumber = $(this).data('value');
                    console.log(WithdrawNumber);
                    $.post("Home/Bank", {fun:"ReCalculate",WithdrawNumber:WithdrawNumber}, data =>{
                        // console.log(data);
                        alert("更變成功!!!");
                        eval(data);
                    });
                });
                $("input[id='Pass']").on('click',  function(Event) {//通過
                    let WithdrawNumber = $(this).data('value');
                    console.log(WithdrawNumber);
                    $.post("Home/Bank", {fun:"Pass",WithdrawNumber:WithdrawNumber}, data =>{
                        // console.log(data);
                        alert("更變成功!!!");
                        eval(data);
                    });
                });
                $("input[id='Reject']").on('click',  function(Event) {//拒絕
                    let WithdrawNumber = $(this).data('value');
                    console.log(WithdrawNumber);
                    $.post("Home/Bank", {fun:"Reject",WithdrawNumber:WithdrawNumber}, data =>{
                        // console.log(data);
                        alert("更變成功!!!");
                        eval(data);
                    });
                });
            });

        });

        $("input[id='BankReset']").on('click',  function(Event) {
            $('input[id="WithdrawNumber"]').val("");
            $('input[id="MemberAccount"]').val("");
            $('select[id="WithdrawState"]').val("");
            $('input[id="datepick1"]').val("");
            $('input[id="datepick2"]').val("");
        });
    }

    if (location.href.substring(location.href.lastIndexOf('/') + 1) == "Permission_add"){

        
        $("input[id='add_check']").on('click',  function(Event) {
            // Event.preventDefault();
            // document.getElementById('popup_confirmAdd').style.display='block';
            // $("input[id='add_confirm']").on('click',  function(Event) {
                var Permission= $("input[name='Permission[]']:checked").map(function() { return $(this).val(); }).get();
                console.log(Permission);
                console.log($("input[id='AdminAccount']").val());
                console.log($("input[id='NickName']").val());
                console.log($("select[id='MemberLevel']").val());
                // $.post("Home/Permission_add", {
                //     fun:"Permission_add",
                //     AdminAccount:$("input[id='AdminAccount']").val(),
                //     AdminPW:$("input[id='AdminPW']").val(),
                //     Permission:Permission,
                //     NickName:$("input[id='NickName']").val(),
                //     MemberLevel:$("select[id='MemberLevel']").val()
                // }, data =>{
                //     console.log(data);
                // //     // alert("密碼更變成功!!!");
                //     eval(data);
                // });
            // });
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