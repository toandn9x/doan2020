$().ready(function() {
	$("#login").validate({
		onfocusout: false,
		onkeyup: true,
		onclick: true,
		rules: {
			"email": {
				required: true,
				//maxlength: 10
			},
			"password": {
				required: true,
				minlength: 6
			},
			// "re-password": {
			// 	equalTo: "#password",
			// 	minlength: 8
				
			// }
		},
		messages: {
			"email": {
				required: "Bắt buộc nhập email",
				//maxlength: "Hãy nhập tối đa 10 ký tự",
				email: "Email không đúng định dạng"
			},
			"password": {
				required: "Bắt buộc nhập password",
				minlength: "Hãy nhập ít nhất 6 ký tự"
			},
			// "re-password": {
			// 	equalTo: "Hai password phải giống nhau",
			// 	minlength: "Hãy nhập ít nhất 8 ký tự"
			// }
		}
	});
	$("#register").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: true,
		rules: {
			"fullname": {
				required: true,
				//maxlength: 10
			},
			"email": {
				required: true,
				//maxlength: 10
			},
			"password": {
				required: true,
				minlength: 6
			},
			"repass": {
				equalTo: "#password",
				minlength: 6
				
			},
		},
		messages: {
			"fullname": {
				required: "Bắt buộc nhập tên",
				//maxlength: "Hãy nhập tối đa 10 ký tự",
			},
			"email": {
				required: "Bắt buộc nhập email",
				//maxlength: "Hãy nhập tối đa 10 ký tự",
				email: "Email không đúng định dạng"
			},
			"password": {
				required: "Bắt buộc nhập password",
				minlength: "Hãy nhập ít nhất 6 ký tự"
			},
			"repass": {
				equalTo: "Hai password phải giống nhau",
				minlength: "Hãy nhập ít nhất 6 ký tự"
			},
			
		}
	});
	$("#reset").validate({
		onfocusout: false,
		onkeyup: true,
		onclick: true,
		rules: {
			"email": {
				required: true,
				//maxlength: 10
			},
		},
		messages: {
			"email": {
				required: "Bắt buộc nhập email",
				//maxlength: "Hãy nhập tối đa 10 ký tự",
				email: "Email không đúng định dạng"
			},
		}
	});
	$("#recover").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: true,
		rules: {
			"password": {
				required: true,
				minlength: 6
			},
			"repass": {
				equalTo: "#password",
				minlength: 6
				
			},
		},
		messages: {
			"password": {
				required: "Bắt buộc nhập password",
				minlength: "Hãy nhập ít nhất 6 ký tự"
			},
			"repass": {
				equalTo: "Hai password phải giống nhau",
				minlength: "Hãy nhập ít nhất 6 ký tự"
			},
			
		}
	});
});