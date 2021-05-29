var Script = function () {

//    $.validator.setDefaults({
//        submitHandler: function() { alert("Enviado!"); }
//    });

    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

        // validate signup form on keyup and submit
        $("#signupForm").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2
                },
                agree: "required"
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 2 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                email: "Please enter a valid email address",
                agree: "Please accept our policy"
            }
        });

        // propose username by combining first- and lastname
        $("#username").focus(function() {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if(firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });

        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.click(function() {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });
		

		/////////////////////////////////////////////////////////////////////////////////////
		/// MediaPlus ERP ///////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
		
		// validate clientes form
		$("#clientesForm").validate({
            rules: {
                cnpj: "required",
                razao: "required",
				cep: "required",
				estado: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                cnpj: "Por favor, insira o CNPJ",
                razao: "Por favor, insira a Razão Social",
                cep: "Por favor, insira o CEP",
				estado: "Por favor, insira o Estado",
                email: "Por favor, coloque um e-mail válido"
            }
        });

        // validate veiculos form
		$("#veiculosForm").validate({
            rules: {
                cnpj: "required",
                razao: "required",
				cep: "required",
				estado: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                cnpj: "Por favor, insira o CNPJ",
                razao: "Por favor, insira a Razão Social",
                cep: "Por favor, insira o CEP",
				estado: "Por favor, insira o Estado",
                email: "Por favor, coloque um e-mail válido"
            }
        });

        // validate vendedores form
		$("#vendedoresForm").validate({
            rules: {
                cnpj: "required",
                razao: "required",
				cep: "required",
				estado: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                cnpj: "Por favor, insira o CNPJ",
                razao: "Por favor, insira a Razão Social",
                cep: "Por favor, insira o CEP",
				estado: "Por favor, insira o Estado",
                email: "Por favor, coloque um e-mail válido"
            }
        });
		
		
		// validate M999 form
        $("#M999").validate({
            rules: {
                codigo: "required",
                descricao: "required",
				versao: "required",
				data: "required"
            },
            messages: {
                codigo: "Por favor, insira um código",
                descricao: "Por favor, insira a descrição",
				versao: "Por favor, indique a versão do arquivo",
				data: "Por favor, insira a data que foi feito o arquivo"
            }
        });
		
		// validate relatorio_visita form
        $("#relatorio_visita").validate({
            rules: {
                data_ini: "required",
                data_fim: "required",
				zona: "required"
            },
            messages: {
                data_ini: "Por favor, insira a data inicial",
                data_fim: "Por favor, insira a data final",
				zona: "Por favor, selecione a zona"
            }
        });
		
		// validate relatorio_visita form
		$("#relatorio_monitora").validate({
			rules:{
				data_ini: "required",
				data_fim: "required"
            },
			messages:{
				data_ini: "Por favor, insira a data inicial",
				data_fim: "Por favor, insira a data final"
            }
        });
		
		// validate relatorio_visita form
        $("#inserir_visita").validate({
            rules: {
                tipo: "required",
                cliente: "required",
				data_visita: "required",
				hora_visita: "required"
            },
            messages: {
                tipo: "Por favor, selecione o tipo da visita",
                cliente: "Por favor, selecione o cliente",
				data_visita: "Por favor, insira a data da visita",
				hora_visita: "Por favor, selecione a hora da visita"
            }
        });
		
		$("#form_agenda").validate({
            rules: {
                contato: "required",
				data_visita: "required",
				hora_visita: "required",
				descricao: "required"
            },
            messages: {
                contato: "Por favor, insira o contato",
                data_visita: "Por favor, insira a data da visita",
				hora_visita: "Por favor, selecione a hora da visita",
				descricao: "Por favor, insira a descrição"
            }
        });
		
		// validate Consulta form
        $("#form_consulta").validate({
            rules: {
                cliente: "required",
                orcamento: {
                    number: true
                },
				pedido: {
                    number: true
                }
            },
            messages: {
                cliente: "Por favor, insira o cliente",
                orcamento: {
					number: "Somente números"
                },
				pedido: {
					number: "Somente números"
                }
            }
        });
		
    });


}();