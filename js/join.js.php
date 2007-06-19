$(function() {
	$('#join_form').bind('submit',function() {
		return check_form(this);
	});

	$('#join_form')[0].elements[2].focus();

	$('#join_form textarea').bind('keyup',function() {
		myid=$(this).attr('id');
		remaining=ta_len-$(this).val().length;
		if (remaining<0) {
			$(this).val($(this).val().substr(0,$(this).val().length+remaining));
			remaining=0;
		}
		$('#'+myid+'_chars').html(remaining.toString());
	}).bind('blur',function() {
		myid=$(this).attr('id');
		remaining=ta_len-$(this).val().length;
		if (remaining<0) {
			$(this).val($(this).val().substr(0,$(this).val().length+remaining));
			remaining=0;
		}
		$('#'+myid+'_chars').html(remaining.toString());
	});
});

function req_update_location(str_field,val) {
	$.post('ajax/location.php',
			{'field':str_field,'val':val},
			function(data) {
				if (data!=null && data!='') {
					allopts=data.split("\n");
					str_field=allopts[0];
					toshow=allopts[1].split('|');
					$('#row_'+str_field+'state').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'city').addClass('invisible').removeClass('visible');
					$('#row_'+str_field+'zip').addClass('invisible').removeClass('visible');
					for (i=0;i<toshow.length;i++) {
						$('#row_'+toshow[i]).addClass('visible').removeClass('invisible');
					}
					if (allopts.length>3) {
						to_update=$('#'+str_field+allopts[2])[0];
						to_update.options.length=0;
						for (i=3;i<allopts.length;i++) {
							oneopt=allopts[i].split('|');
							opt=new Option(oneopt[1],oneopt[0]);
							to_update.options.add(opt,to_update.length);
						}
					}
				}
			}
	);
}

function req_check_username() {
	user=$('#user').val();
	if (validate_user(user)) {
		$.post('ajax/user_exists.php',
				{'user':user},
				function(data) {
					if (data!=null && data!='') {
						alert('You cannot use "'+user+'" as your username because it is already taken.');
					} else {
						alert('You can use "'+user+'" as your username.');
					}
				}
		);
	} else {
		alert('Please use only letters and digits for your username. 4-20 chars');
	}
}

function check_form(theform) {
<?php
if (empty($_GET['page']) || $_GET['page']==1) {
?>
	if (!validate_user(theform.user.value)) {
		alert('Please use only letters and digits for your username. 4-20 chars');
		theform.user.focus();
		return false;
	}
	if (theform.email.value!=theform.email2.value) {
		alert('Emails don\'t match');
		theform.email.focus();
		return false;
	}
	if (!validate_email(theform.email.value)) {
		alert('Invalid email entered');
		theform.email.focus();
		return false;
	}
	if (theform.captcha.value=='') {
		alert('Please enter the code you see in the image');
		theform.captcha.focus();
		return false;
	}
	if (!theform.agree.checked) {
		alert('You must agree with our terms and conditions before joining the site');
		theform.agree.focus();
		return false;
	}
<?php
}
?>
	var is_error=false;
	for (i=0;i<dbfields.length;i++) {
		error=field_empty(dbfields[i],fieldtypes[i],'join_form');
		if (error) {
			is_error=true;
			$('#row_'+dbfields[i]).addClass('red_border');
//			$('#'+dbfields[i])[0].focus();
		}
	}
	if (is_error) {
		alert('The fields outlined below must not be empty');
	}
	return !is_error;
}

function field_empty(dbfield,field_type,form_id) {
	myreturn=false;
	if (field_type==2) {	// FIELD_TEXTFIELD
		if ($('#'+dbfield)[0].value=='') {
			myreturn=true;
		}
	} else if (field_type==3) {	// FIELD_SELECT
		if ($('#'+dbfield)[0].value=='' || $('#'+dbfield)[0].value==0) {
			myreturn=true;
		}
	} else if (field_type==4) {	// FIELD_TEXTAREA
		if ($('#'+dbfield)[0].value=='') {
			myreturn=true;
		}
	} else if (field_type==9 || field_type==10) {	// FIELD_CHECKBOX, FIELD_CHECKBOX_LARGE
		is_empty=true;
		$('input[@id^='+dbfield+']').each(function() {
			if (this.checked) {
				is_empty=false;
			}
		});
		if (is_empty) {
			myreturn=true;
		}
	} else if (field_type==103) {	// FIELD_DATE
		if ($('#'+dbfield+'_day')[0].value=='' || $('#'+dbfield+'_day')[0].value==0) {
			myreturn=true;
		}
		if ($('#'+dbfield+'_month')[0].value=='' || $('#'+dbfield+'_month')[0].value==0) {
			myreturn=true;
		}
		if ($('#'+dbfield+'_year')[0].value=='' || $('#'+dbfield+'_year')[0].value==0) {
			myreturn=true;
		}
	} else if (field_type==104 || field_type==105) {	// FIELD_INT,FIELD_FLOAT
		if ($('#'+dbfield)[0].value==0) {
			myreturn=true;
		}
	}
	return myreturn;
}