<div class="container">
    <h1>API Testing</h1>

    <table cellspacing="10">
        <tr>
            <td>Login URL:</td>
            <td><input id="loginLink" type="text" value="http://boostapi.localhost.com/api/login" size="90"></td>
        </tr>

        <tr>
            <td>Request URL:</td>
            <td><input id="requestLink" type="text" value="http://boostapi.localhost.com/api/" size="90"></td>
        </tr>

        <tr>
            <td>Method:</td>
            <td>
                <select id="method">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="SEND">SEND</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Token:</td>
            <td><input id="apiToken" type="text" value="" size="50"></td>
        </tr>

        <tr>
            <td>Email:</td>
            <td><input id="email" type="text" value="brad@sointeractive.co.za" size="50"></td>
        </tr>

        <tr>
            <td>Password:</td>
            <td><input id="password" type="text" value="test74" size="50"></td>
        </tr>

        <tr>
            <td>Account/Subdomain:</td>
            <td><input id="subdomain" type="text" value="boost" size="50"></td>
        </tr>

        <tr>
            <td>Company Name:</td>
            <td><input id="companyName" type="text" size="50"></td>
        </tr>
        <tr>
            <td>data objects:</td>
            <td><textarea id="data_objects" cols="60" rows="5">
dataArray = {

}
            </textarea></td>
        </tr>

        <tr>
            <td><input type="button" onclick="login()" value="Login"></td>
        </tr>

        <tr>
            <td><input type="button" onclick="register()" value="Register"></td>
        </tr>

        <tr>
            <td><input type="button" onclick="ajax_call()" value="API Call"></td>
        </tr>
    </table>

    <div id="div1">&nbsp;</div>

</div>

<script type="text/javascript" language="JavaScript">
    $(function(){
        //ajax_call();
        //console.log(localStorage.getItem("token"));
    });

    function login() {

        var login = {
            email: $('#email').val(),
            password: $('#password').val(),
            account_name: $('#subdomain').val()
        };

        var dataArr = {
            login: btoa(JSON.stringify(login))
        };

        var api = $('#loginLink').val();

        var dataString = JSON.stringify(dataArr);
        var method = $('#method').val();
        console.log($('#subdomain').val());

        $.ajax({
            url: api,
            type: method,
            data: dataString,
            headers: {
                "Authorization": localStorage.getItem("token"),
                "Auth": localStorage.getItem("token")
            },
            dataType: "json",
            success: function (result) {

                console.log(result);

                localStorage.token = result.token;
                localStorage.session_id = result.session_id;
                $('#apiToken').val(result.token);
            },
            error: function (result) {

                $("#div1").html(result.responseText);
            }
        });
    }

    function register() {
        var dataArr4 = {
            company_name: $('#companyName').val(),
            email: $('#email').val()
        };

        var api = $('#requestLink').val();
        var method = $('#method').val();
        var dataString = JSON.stringify(dataArr4);

        if ($('#apiToken').val() != '') {
            apiToken = $('#apiToken').val();
        }

        $.ajax({
            url: api,
            type: method,
            data: dataString,
            dataType: "json",
            success: function (result) {

            },
            error: function (result) {
                $("#div1").html(result.responseText);
            }
        });
    }

    function ajax_call()
    {
        var dataArr4 = {
           /* piggyback: {
                0: 'contacts/organisation'
            },
            filters: {
                start_date: '2016-02-15',
                end_date: '2016-02-16'
            },
            password: 'test01',
            confirm_password: 'test01',
            contact_email: 'pride@sointeractive.co.za',
            email: 'pride@sointeractive.co.za',
            subject: 'BA - SUbject',
            message_body: 'The message body',*/
            /*tables : {
                invoices: {
                    search: {0: 'invoice_number', 1: 'reference'},
                    return: {0: 'id', 1: 'invoice_number'}
                }
            }
			'piggyback':{
				0:'messages/statements/16',
				1:'organizations',
				2:'email_settings'
			}*/
			/*'tables':{
				'items':{
					'search':{0:'item_name'},
					'return':{0:'id',1:'item_name',2:'description',3:'quantity',4:'tax',5:'rate'}
				}
			}
			*/
			/*'tables':{
				'invoices':{
					'search':{0:'invoice_number'},
					'return':{0:'id',1:'invoice_number',2:'contact_id'}
				}
			}*/
			
        };

        var api = $('#requestLink').val();
        var method = $('#method').val();
        //var dataString = JSON.stringify(dataArr4);
		//console.log(eval($('#data_objects').val()))
		eval($('#data_objects').val());
	    var dataString = JSON.stringify(dataArray);
		
        var apiToken = localStorage.getItem("token");

        if ($('#apiToken').val() != '') {
            apiToken = $('#apiToken').val();
        }

        $.ajax({
            url: api,
            type: method,
            data: dataString,
            headers: {
                "Auth": apiToken,
                "Session": localStorage.getItem("session_id"),
                "Custom": 'I am custom',
                "Account-Name": $('#subdomain').val()
            },
            dataType: "json",
            success: function (result) {

                console.log(result);

                if ('download' in result) {
                    //window.location.assign(result.download);
                }
            },
            error: function (result) {
                $("#div1").html(result.responseText);
            }
        });
    }

</script>