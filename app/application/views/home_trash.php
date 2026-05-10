<header>

</header>


<div class="container">
    <h1>Home</h1>

    <!--    <p><input type="button" onclick="ajax_call()" value="API Call"></p>-->
    <p>URL: <input id="requestLink" type="text" value="http://192.168.0.151/boost_dev/api/api/" size="90"></p>

    <p>Login URL: <input id="loginLink" type="text" value="http://192.168.0.151/boost_dev/api/api/login" size="90"></p>

    <p>Method:
        <select id="method">
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="SEND">SEND</option>
            <option value="DELETE">DELETE</option>
        </select>
    </p>

    <p>Token: <input id="apiToken" type="text" value="" size="50"></p>

    <p>Email: <input id="email" type="text" value="pride@sointeractive.co.za" size="50"></p>

    <p>Password: <input id="password" type="text" value="test" size="50"></p>

    <p>Subdomain: <input id="subdomain" type="text" value="boost" size="50"></p>

    <p>Company Name: <input id="companyName" type="text" size="50"></p>

    <p><input type="button" onclick="login()" value="Login"></p>

    <p><input type="button" onclick="ajax_call()" value="API Call"></p>

    <!--<p><input type="button" onclick="logout()" value="Logout"></p>-->

    <?php
    $url = 'http://username:password@hostname:9090/path?arg=value#anchor';

    $hmac = hash_hmac("sha256", "secret", "abc");

    //var_dump($hmac);

    /*var_dump(parse_url($url));
    var_dump(parse_url($url, PHP_URL_SCHEME));
    var_dump(parse_url($url, PHP_URL_USER));
    var_dump(parse_url($url, PHP_URL_PASS));
    var_dump(parse_url($url, PHP_URL_HOST));
    var_dump(parse_url($url, PHP_URL_PORT));
    var_dump(parse_url($url, PHP_URL_PATH));
    var_dump(parse_url($url, PHP_URL_QUERY));
    var_dump(parse_url($url, PHP_URL_FRAGMENT));*/

    //var_dump(base64_encode('pride@sointeractive.co.za'));

    var_dump(base64_encode('hello'));

    ?>

    <div id="div1"></div>

</div>

<?php echo script_tag('resources/js/jssha256.js'); ?>

<script type="text/javascript" language="JavaScript">
    $(function () {
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

        //console.log(login);

        //var api = 'http://api.boostaccounting.com/api/login';
        //var api = 'http://localhost/boost_dev/api/api/login';
        var api = $('#loginLink').val();

        var dataString = JSON.stringify(dataArr);
        var method = $('#method').val();
        console.log($('#subdomain').val());

        $.ajax({
            url: api,
            type: method,
            data: dataString,
            headers: {
                //"Authorization": btoa(JSON.stringify(login)),
                "Authorization": localStorage.getItem("token"),
                "Auth": localStorage.getItem("token"),
                "Custom": 'I am custom',
                //Subdomain: $('#subdomain').val()
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

    function ajax_call() {
        //var api = 'http://localhost/boost/api/api/login';
        var api = 'http://localhost/boost_dev/api/map/';
        var dataArr = {

            contact_id: 1,
            currency_id: 1,
            invoice_number: 1063,
            date: '1 Jun 2015',
            due_date: '05 Jul 2015',
            discount_percentage: 0,
            reference: 'lalala',
            items: {
                0: {
                    item_name: 'Coffee table',
                    description: 'sdsadadas',
                    quantity: 1,
                    tax: 0,
                    rate: 1000
                },
                1: {
                    item_name: 'the second item',
                    description: 'the second item\'s description',
                    quantity: 1,
                    tax: 0,
                    rate: 1000
                },
                2: {
                    item_name: 'the third item',
                    description: 'the third item\'s description',
                    quantity: 1,
                    tax: 0,
                    rate: 1000
                }
            }/*,
             piggyback: {
             0: 'clients',
             1: 'currencies',
             2: 'items',
             3: 'invoices/num_rows',
             4: 'invoices/last_id',
             5: 'invoices/next_reference'
             }*/
        };

        var dataArr2 = {

            contact_id: 14,
            currency_id: 1,
            credit_note_number: 'CN-046479',
            invoice_id: 1211,
            date: '1 Jun 2015',
            due_date: '05 Jul 2015',
            discount_percentage: 0,
            reference: "INV-1000088",
            items: {
                0: {
                    item_name: 'Table',
                    description: 'Accomodates 4 seats!',
                    quantity: 1,
                    tax: 0,
                    rate: 1400
                }
            }
        };

        var dataArr3 = {
            password: 'test',
            confirm_password: 'test',
            reset_link: 'http://localhost/boost_dev/site'
        };

        var dataArr4 = {
            company_name: $('#companyName').val(),
            email: $('#email').val()
        };

        /*var dataArr = {
         item_name: 'Bed',
         description: 'Queen size',
         amount: 12000
         };*/

        /*var dataArr = {
         fields: {
         0:'organisation',
         1:'invoice_number',
         2:'last_name'
         },
         tables :{
         invoices: {
         search: {0: 'invoice_number', 1: 'reference'},
         return: {0: 'id', 1: 'invoice_number'}
         },
         contacts: {
         search: {0: 'organisation'},
         return: {0: 'id', 1: 'organisation'}
         }
         },
         piggyback: {
         0: 'currencies'
         }
         };*/

        /* var dataArr = {
         piggyback: {
         0: 'contacts',
         1: 'currencies',
         2: 'taxes',
         3: 'payments'
         }
         };*/

        /*var dataArr = {
         invoice_id:1085,
         payment_amount:1000,
         payment_method_id:1,
         use_credit:'yes',
         reference:'Part payment '
         };*/

        //var dataArr = ['1001', '1002', '1003', '1004'];

        /* var dataArr = {
         data: {
         0: {id:1130},
         1: {id:1129},
         2: {id:1128},
         3: {id:1127}
         }
         };*/

        /*var dataArr = {
         invoice_id: "1156",
         notifiction: "on",
         payment_amount: "ab",
         payment_date: "11 Sep 2015",
         payment_method_id: "1",
         reference: "INV-1000033",
         use_credit: "no"
         };*/

        /*var dataArr = {
         organisation_id: "1156",
         first_name: "on",
         last_name: "ab",
         email: "pridesointeractive.co.za",
         //user_role_id: "1",
         password: "12345",
         piggyback: {
         0: 'contacts',
         1: 'taxes',
         2: 'invoices/next_reference'
         }
         };*/

        /*var dataArr = {
         piggyback: {
         0: 'messages/invoices/1208',
         1: 'organizations',
         2: 'payment_methods'
         }
         };*/

        /*var dataArr = {
         contact_type_id: "",
         organisation: "",
         vat_number: "",
         industry_id: "",
         company_size_id: "",
         first_name: "",
         last_name: "",
         email: "",
         land_line: "",
         mobile: "",
         address: ""
         };*/

        var login = {
            email: 'pride@sointeractive.co.za',
            password: '12345'
        };

        //api = 'http://localhost/boost_dev/api/api/items';
//        api = 'http://localhost/boost_dev/api/api/payments/';
        //api = 'http://localhost/boost_dev/api/api/invoices/1001/reminder/10';
        //api = 'http://localhost/boost_dev/api/api/payments';
        //api = 'http://localhost/boost_dev/api/api/invoices';

        //api = 'http://localhost/boost_dev/api/api/search/' + encodeURIComponent('i');
        //api = 'http://localhost/boost_dev/api/api/invoices/1062/invoice_number/INV-000053'; //+ encodeURIComponent('me @ g mail.com');

        //api = 'http://localhost/boost_dev/api/api/bulk/invoices/status/sent';
        //api = 'http://localhost/boost_dev/api/api/invoices/1070/content_status/archive';

        //api = 'http://localhost/boost_dev/api/api/bulk/invoices/';
        //api = 'http://localhost/boost_dev/api/api/invoices/status/desc/10/0';
        //api = 'http://boostapi.soitesting.co.za/api/estimates';

        api = $('#requestLink').val();
        var method = $('#method').val();

        var dataString = JSON.stringify(dataArr4);

        var apiToken = localStorage.getItem("token");

        if ($('#apiToken').val() != '') {
            apiToken = $('#apiToken').val();
        }

        console.log($('#subdomain').val());

        $.ajax({
            url: api,
            type: method,
            data: dataString,
            headers: {
                //"Authorization": btoa(JSON.stringify(login))
                //"Authorization": localStorage.getItem("token"),
                "Auth": apiToken,//localStorage.getItem("token"),
                //"Auth": '225899a61911111df95c76133aed07b',
                "Session": localStorage.getItem("session_id"),
                "Custom": 'I am custom',
                "Account-Name": $('#subdomain').val()
            },
            dataType: "json",
            success: function (result) {

                console.log(result);
                //console.log(result.data[1097].contact.account);

                if ('download' in result) {
                    //window.location.assign(result.download);
                }

                //$("#div1").html('<img src="'+result.data[0].image_string+'" title="'+result.data[0].logo_name+'">');

            },
            error: function (result) {
                $("#div1").html(result.responseText);
            }
        });
    }

    function createCORSRequest(method, url) {
        var xhr = new XMLHttpRequest();
        if ("withCredentials" in xhr) {

            // Check if the XMLHttpRequest object has a "withCredentials" property.
            // "withCredentials" only exists on XMLHTTPRequest2 objects.
            xhr.open(method, url, true);

        } else if (typeof XDomainRequest != "undefined") {

            // Otherwise, check if XDomainRequest.
            // XDomainRequest only exists in IE, and is IE's way of making CORS requests.
            xhr = new XDomainRequest();
            xhr.open(method, url);

        } else {

            // Otherwise, CORS is not supported by the browser.
            xhr = null;
        }
        return xhr;
    }

</script>