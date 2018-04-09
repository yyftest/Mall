function loginUser() {
	var fm = document.login;
	
	if (fm.user.value == '') {
		alert('用户名不得为空！');
		fm.user.focus();
		return false;
	}
	if (fm.pass.value == '') {
		alert('密码不得为空！');
		fm.pass.focus();
		return false;
	}	
	if (fm.ajaxlogin.value != '') {
		alert('用户名或密码不正确！');
		fm.pass.focus();
		return false;
	}
	return true;
}


function checkLogin(){
    var user = document.getElementById("user");
    var pass = document.getElementById("pass");
    var ajaxlogin = document.getElementById("ajaxlogin");
    var ajax = new AjaxObj();
    ajax.swRequest({
        method:"POST",
        sync:false,
        url:'?a=member&m=ajaxLogin',
        data:"user="+user.value+"&pass="+pass.value,
        success: function(msg) {
            if(msg==1){
                ajaxlogin.value = 'true';
            } else {
				ajaxlogin.value = '';
			}
        },
        failure: function(a) {
            alert(a);
        },
        soap:this
    });
}
