$(function() {

    $(document).on('click', 'div[id="Login"]', function (Event) {
		$('base').attr('href','');
		// alert($('form[id="sign_in"]').serialize());
		//$('#divLoading').show();
		$.post("Login/Login",$('form[id="sign_in"]').serialize(),function (data){
			console.log(data);
            // alert(123);
			eval(data);
			//$('#LoginVerify').attr('src','img.php');
		}).fail(function(xhr, status, error) {
            //alert( "error"+xhr );
            console.log(xhr);
            alert(xhr.responseText)
           });
	});

    // $.post("Login/Checklogin", function (data) {
    //     // console.log(data);
    //     // console.log(123);
    //     eval(data);
    //     // var obj = JSON.parse(data);
	// 	// sessionStorage.setItem('session_MemberAccount', obj[1]);
    //     // var hd_login = document.getElementById("hd_login");
    //     // var hd_member = document.getElementById("hd_member");
    //     // if (obj[0] == "1") {    
    //     //     $('div[id="hd_login"]').hide();
    //     //     $('div[id="hd_member"]').show();
    //     //     // $('div[id="popup_LoginPublish"]').hide();
    //     //     /*hd_login.style.display="none"; 
    //     //     hd_member.style.display="block"; */
    //     // }else if (obj[0] == "0") {
    //     //     $('div[id="hd_login"]').show();
    //     //     $('div[id="hd_member"]').hide();
    //     //     /*hd_login.style.display="block"; 
    //     //     hd_member.style.display="none"; */
    //     // }
    
    // }).fail(function(xhr, status, error) {
    //     //alert( "error"+xhr );
    //     console.log(xhr);
    //     alert(xhr.responseText)
    //    });
    
});