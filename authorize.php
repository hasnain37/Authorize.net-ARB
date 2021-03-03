<?php
/*
Plugin name: Recurring payment by Authorize.net 
Plugin URI: http://glowlogix.com
Description:  Recurring payment by Authorize.net
Author: Glowlogix
Version: 1.0
*/
defined('ABSPATH') || exit;

define('PLUGIN_NAME_VERSION', '1.0.0');
if (!defined('SUB_PATH')) {
    define('SUB_PATH', plugin_dir_path(__FILE__));
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
// function activate_plugin_name() {
// 	require_once plugin_dir_path( __FILE__ ) . 'includes/my-plugin-activator.php';
// 	Plugin_Name_Activator::activate();
// }

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */

add_action('admin_menu', 'subdomain_menu');

function subdomain_menu()
{

    //create custom top-level menu
    add_menu_page(
        'Authorize',
        'Authorize',
        'manage_options',
        'auth-payment-form',
        'ShowShortcodes',
        'dashicons-update',
        70
    );
}
function ShowShortcodes()
{

    echo "<h3>Shortcode for 'Reggie's Virtual Bootcamp' Subscription form </h3> [form1] \n";
    echo "<h3>Shortcode for 'Fit and Fierce with Grace' Subscription form </h3> [form2]\n";
    echo "<h3>Shortcode for Cancel Subscription form </h3> [cancel] \n";
}

function auth_payment_form()
{
    // echo  plugins_url(__FILE__);
    // exit; 
?>
    <div class="wrap">
        <style>
            .fail {
                color: white;
                background-color: #ff4d4d;
                padding: 10px;
                border-radius: 12px;
                margin-bottom: 38px;
                text-align: center;
            }

            .success {
                color: white;
                background-color: #65a965;
                padding: 10px;
                border-radius: 12px;
                margin-bottom: 38px;
                text-align: center;
            }
        </style>

        <h3>Subscribe to Reggie's Virtual Bootcamp <small>($25 /week)</small></h3>
        <?php
        if (isset($_POST['submit'])) {
            $cardnum = $_POST['cardnum'];
            $cardcode = $_POST['cardcode'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $expdate = $_POST['expdate'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $zip = $_POST['zip'];
            $state = $_POST['state'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];


            require_once plugin_dir_path(__FILE__)  . 'inc/create_arb_api.php';
            createSubscription1($fname, $lname, $cardnum, $cardcode, $expdate, $address, $city, $country, $state, $zip, $email, $phone);
        }
        ?><form action="<?php __FILE__ ?>" method="post">
            <div class="fw-col-sm-6"><label>First Name</label><br><input type="text" class="inp" name="fname" /><br><br></div>
            <div class="fw-col-sm-6"><label>Last Name</label><br><input type="text" class="inp" name="lname" /><br><br></div>
            <div class="fw-col-sm-6"><label>Email</label><br><input type="email" class="inp" name="email" /><br><br></div>
            <div class="fw-col-sm-6"><label>Phone</label><br><input type="text" class="inp" name="phone" /><br><br></div>
            <div class="fw-col-sm-6"><label>Address</label><br><input type="text" class="inp" name="address" /><br><br></div>
            <div class="fw-col-sm-6"><label>Country</label><br><select name="country" class="countries form-control" id="countryId">
                    <option value="">Select Country</option>
                </select><br><br></div>
            <div class="fw-col-sm-6"><label>State</label><br><select name="state" class="states form-control" id="stateId">
                    <option value="">Select State</option>
                </select><br><br></div>
            <div class="fw-col-sm-6"><label>City</label><br><input type="text" class="inp" name="city" /><br><br></div>
            <div class="fw-col-sm-6"><label>Zip Code</label><br><input type="text" class="inp" name="zip" /><br><br></div>
            <div class="fw-col-sm-6"><label>Card Number</label><br><input type="text" class="inp" name="cardnum" /><br><br></div>
            <div class="fw-col-sm-6"><label>CSV</label><br><input type="password" name="cardcode" class="inp" /><br><br></div>
            <div class="fw-col-sm-6"><label>Expiration Date (YYYY-MM)</label><br><input type="text" class="inp" name="expdate" /><br><br></div><input type="submit" name="submit" class="sub-btn" />
        </form>
        <script>
            function ajaxCall() {
                this.send = function(data, url, method, success, type) {
                    type = type || 'json';

                    var successRes = function(data) {
                        success(data);
                    }

                    var errorRes = function(e) {
                        console.log(e);
                    }

                    jQuery.ajax({
                            url: url,
                            type: method,
                            data: data,
                            success: successRes,
                            error: errorRes,
                            dataType: type,
                            timeout: 60000
                        }

                    );

                }

            }

            function locationInfo() {
                var rootUrl = "https://geodata.solutions/api/api.php";
                //now check for set values
                var addParams = '';

                if (jQuery("#gds_appid").length > 0) {
                    addParams += '&appid=' + jQuery("#gds_appid").val();
                }

                if (jQuery("#gds_hash").length > 0) {
                    addParams += '&hash=' + jQuery("#gds_hash").val();
                }

                var call = new ajaxCall();

                this.confCity = function(id) {
                    var url = rootUrl + '?type=confCity&countryId=' + jQuery('#countryId option:selected').attr('countryid') + '&stateId=' + jQuery('#stateId option:selected').attr('stateid') + '&cityId=' + id;
                    var method = "post";

                    var data = {}

                    ;

                    call.send(data, url, method, function(data) {}

                    );
                }

                ;


                this.getCities = function(id) {
                    jQuery(".cities option:gt(0)").remove();
                    var stateClasses = jQuery('#cityId').attr('class');

                    var cC = stateClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var url = rootUrl + '?type=getCities&countryId=' + jQuery('#countryId option:selected').attr('countryid') + '&stateId=' + id + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.cities').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.cities').find("option:eq(0)").html("Select City");

                            if (data.tp == 1) {
                                var listlen = Object.keys(data['result']).length;

                                if (listlen > 0) {
                                    jQuery.each(data['result'], function(key, val) {

                                            var option = jQuery('<option />');
                                            option.attr('value', val).text(val);
                                            jQuery('.cities').append(option);
                                        }

                                    );
                                } else {
                                    var usestate = jQuery('#stateId option:selected').val();
                                    var option = jQuery('<option />');
                                    option.attr('value', usestate).text(usestate);
                                    option.attr('selected', 'selected');
                                    jQuery('.cities').append(option);
                                }

                                jQuery(".cities").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

                this.getStates = function(id) {
                    jQuery(".states option:gt(0)").remove();
                    jQuery(".cities option:gt(0)").remove();
                    //get additional fields
                    var stateClasses = jQuery('#stateId').attr('class');

                    var cC = stateClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var url = rootUrl + '?type=getStates&countryId=' + id + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.states').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.states').find("option:eq(0)").html("Select State");

                            if (data.tp == 1) {
                                jQuery.each(data['result'], function(key, val) {
                                        var option = jQuery('<option />');
                                        option.attr('value', val).text(val);
                                        option.attr('stateid', key);
                                        jQuery('.states').append(option);
                                    }

                                );
                                jQuery(".states").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

                this.getCountries = function() {
                    //get additional fields
                    var countryClasses = jQuery('#countryId').attr('class');

                    var cC = countryClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var presel = false;
                    var iip = 'N';

                    jQuery.each(cC, function(index, value) {
                            if (value.match("^presel-")) {
                                presel = value.substring(7);

                            }

                            if (value.match("^presel-byi")) {
                                var iip = 'Y';
                            }
                        }

                    );


                    var url = rootUrl + '?type=getCountries' + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.countries').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.countries').find("option:eq(0)").html("Select Country");

                            if (data.tp == 1) {
                                if (presel == 'byip') {
                                    presel = data['presel'];
                                    console.log('2 presel is set as ' + presel);
                                }


                                if (jQuery.inArray("group-continents", cC) > -1) {
                                    var $select = jQuery('.countries');
                                    console.log(data['result']);

                                    jQuery.each(data['result'], function(i, optgroups) {
                                            var $optgroup = jQuery("<optgroup>", {
                                                    label: i
                                                }

                                            );

                                            if (optgroups.length > 0) {
                                                $optgroup.appendTo($select);
                                            }

                                            jQuery.each(optgroups, function(groupName, options) {
                                                    var coption = jQuery('<option />');
                                                    coption.attr('value', options.name).text(options.name);
                                                    coption.attr('countryid', options.id);

                                                    if (presel) {
                                                        if (presel.toUpperCase() == options.id) {
                                                            coption.attr('selected', 'selected');
                                                        }
                                                    }

                                                    coption.appendTo($optgroup);
                                                }

                                            );
                                        }

                                    );
                                } else {
                                    jQuery.each(data['result'], function(key, val) {
                                            var option = jQuery('<option />');
                                            option.attr('value', val).text(val);
                                            option.attr('countryid', key);

                                            if (presel) {
                                                if (presel.toUpperCase() == key) {
                                                    option.attr('selected', 'selected');
                                                }
                                            }

                                            jQuery('.countries').append(option);
                                        }

                                    );
                                }

                                if (presel) {
                                    jQuery('.countries').trigger('change');
                                }

                                jQuery(".countries").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

            }

            jQuery(function() {
                    var loc = new locationInfo();
                    loc.getCountries();

                    jQuery(".countries").on("change", function(ev) {
                            var countryId = jQuery("option:selected", this).attr('countryid');

                            if (countryId != '') {
                                loc.getStates(countryId);
                            } else {
                                jQuery(".states option:gt(0)").remove();
                            }
                        }

                    );

                    jQuery(".states").on("change", function(ev) {
                            var stateId = jQuery("option:selected", this).attr('stateid');

                            if (stateId != '') {
                                loc.getCities(stateId);
                            } else {
                                jQuery(".cities option:gt(0)").remove();
                            }
                        }

                    );

                    jQuery(".cities").on("change", function(ev) {
                            var cityId = jQuery("option:selected", this).val();

                            if (cityId != '') {
                                loc.confCity(cityId);
                            }
                        }

                    );
                }

            );
        </script>
    </div><?php

        }
        register_activation_hook(__FILE__, function () {
            require_once plugin_dir_path(__FILE__)  . 'inc/authorize_activator.php';
            Activation::activate();
        });
        register_deactivation_hook(__FILE__, function () {
            require_once plugin_dir_path(__FILE__)  . 'inc/authorize_deactivator.php';
            Deactivation::deactivate();
        });

        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        function auth_payment_form2()
        {

            ?><div class="wrap">
        <style>
            .fail {
                color: white;
                background-color: #ff4d4d;
                padding: 10px;
                border-radius: 12px;
                margin-bottom: 38px;
                text-align: center;
            }

            .success {
                color: white;
                background-color: #65a965;
                padding: 10px;
                border-radius: 12px;
                margin-bottom: 38px;
                text-align: center;
            }
        </style>
        <h3>Subscribe to Fit and Fierce with Grace <small>($20 /month)</small></h3>
        <?php
            if (isset($_POST['submit'])) {
                $cardnum = $_POST['cardnum'];
                $cardcode = $_POST['cardcode'];
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $expdate = $_POST['expdate'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $country = $_POST['country'];
                $zip = $_POST['zip'];
                $state = $_POST['state'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];


                require_once plugin_dir_path(__FILE__)  . 'inc/create_arb_api.php';
                createSubscription2($fname, $lname, $cardnum, $cardcode, $expdate, $address, $city, $country, $state, $zip, $email, $phone);
            } ?>
        <form action="<?php __FILE__ ?>" method="post">
            <div class="fw-col-sm-6"><label>First Name</label><br><input type="text" class="inp" name="fname" /><br><br></div>
            <div class="fw-col-sm-6"><label>Last Name</label><br><input type="text" class="inp" name="lname" /><br><br></div>
            <div class="fw-col-sm-6"><label>Email</label><br><input type="email" class="inp" name="email" /><br><br></div>
            <div class="fw-col-sm-6"><label>Phone</label><br><input type="text" class="inp" name="phone" /><br><br></div>
            <div class="fw-col-sm-6"><label>Address</label><br><input type="text" class="inp" name="address" /><br><br></div>
            <div class="fw-col-sm-6"><label>Country</label><br><select name="country" class="countries form-control" id="countryId">
                    <option value="">Select Country</option>
                </select><br><br></div>
            <div class="fw-col-sm-6"><label>State</label><br><select name="state" class="states form-control" id="stateId">
                    <option value="">Select State</option>
                </select><br><br></div>
            <div class="fw-col-sm-6"><label>City</label><br><input type="text" class="inp" name="city" /><br><br></div>
            <div class="fw-col-sm-6"><label>Zip Code</label><br><input type="text" class="inp" name="zip" /><br><br></div>
            <div class="fw-col-sm-6"><label>Card Number</label><br><input type="text" class="inp" name="cardnum" /><br><br></div>
            <div class="fw-col-sm-6"><label>CSV</label><br><input type="password" name="cardcode" class="inp" /><br><br></div>
            <div class="fw-col-sm-6"><label>Expiration Date (YYYY-MM)</label><br><input type="text" class="inp" name="expdate" /><br><br></div><input type="submit" name="submit" class="sub-btn" />
        </form>
        <script>
            function ajaxCall() {
                this.send = function(data, url, method, success, type) {
                    type = type || 'json';

                    var successRes = function(data) {
                        success(data);
                    }

                    var errorRes = function(e) {
                        console.log(e);
                    }

                    jQuery.ajax({
                            url: url,
                            type: method,
                            data: data,
                            success: successRes,
                            error: errorRes,
                            dataType: type,
                            timeout: 60000
                        }

                    );

                }

            }

            function locationInfo() {
                var rootUrl = "https://geodata.solutions/api/api.php";
                //now check for set values
                var addParams = '';

                if (jQuery("#gds_appid").length > 0) {
                    addParams += '&appid=' + jQuery("#gds_appid").val();
                }

                if (jQuery("#gds_hash").length > 0) {
                    addParams += '&hash=' + jQuery("#gds_hash").val();
                }

                var call = new ajaxCall();

                this.confCity = function(id) {
                    var url = rootUrl + '?type=confCity&countryId=' + jQuery('#countryId option:selected').attr('countryid') + '&stateId=' + jQuery('#stateId option:selected').attr('stateid') + '&cityId=' + id;
                    var method = "post";

                    var data = {}

                    ;

                    call.send(data, url, method, function(data) {}

                    );
                }

                ;


                this.getCities = function(id) {
                    jQuery(".cities option:gt(0)").remove();
                    var stateClasses = jQuery('#cityId').attr('class');

                    var cC = stateClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var url = rootUrl + '?type=getCities&countryId=' + jQuery('#countryId option:selected').attr('countryid') + '&stateId=' + id + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.cities').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.cities').find("option:eq(0)").html("Select City");

                            if (data.tp == 1) {
                                var listlen = Object.keys(data['result']).length;

                                if (listlen > 0) {
                                    jQuery.each(data['result'], function(key, val) {

                                            var option = jQuery('<option />');
                                            option.attr('value', val).text(val);
                                            jQuery('.cities').append(option);
                                        }

                                    );
                                } else {
                                    var usestate = jQuery('#stateId option:selected').val();
                                    var option = jQuery('<option />');
                                    option.attr('value', usestate).text(usestate);
                                    option.attr('selected', 'selected');
                                    jQuery('.cities').append(option);
                                }

                                jQuery(".cities").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

                this.getStates = function(id) {
                    jQuery(".states option:gt(0)").remove();
                    jQuery(".cities option:gt(0)").remove();
                    //get additional fields
                    var stateClasses = jQuery('#stateId').attr('class');

                    var cC = stateClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var url = rootUrl + '?type=getStates&countryId=' + id + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.states').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.states').find("option:eq(0)").html("Select State");

                            if (data.tp == 1) {
                                jQuery.each(data['result'], function(key, val) {
                                        var option = jQuery('<option />');
                                        option.attr('value', val).text(val);
                                        option.attr('stateid', key);
                                        jQuery('.states').append(option);
                                    }

                                );
                                jQuery(".states").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

                this.getCountries = function() {
                    //get additional fields
                    var countryClasses = jQuery('#countryId').attr('class');

                    var cC = countryClasses.split(" ");
                    cC.shift();
                    var addClasses = '';

                    if (cC.length > 0) {
                        acC = cC.join();
                        addClasses = '&addClasses=' + encodeURIComponent(acC);
                    }

                    var presel = false;
                    var iip = 'N';

                    jQuery.each(cC, function(index, value) {
                            if (value.match("^presel-")) {
                                presel = value.substring(7);

                            }

                            if (value.match("^presel-byi")) {
                                var iip = 'Y';
                            }
                        }

                    );


                    var url = rootUrl + '?type=getCountries' + addParams + addClasses;
                    var method = "post";

                    var data = {}

                    ;
                    jQuery('.countries').find("option:eq(0)").html("Please wait..");

                    call.send(data, url, method, function(data) {
                            jQuery('.countries').find("option:eq(0)").html("Select Country");

                            if (data.tp == 1) {
                                if (presel == 'byip') {
                                    presel = data['presel'];
                                    console.log('2 presel is set as ' + presel);
                                }


                                if (jQuery.inArray("group-continents", cC) > -1) {
                                    var $select = jQuery('.countries');
                                    console.log(data['result']);

                                    jQuery.each(data['result'], function(i, optgroups) {
                                            var $optgroup = jQuery("<optgroup>", {
                                                    label: i
                                                }

                                            );

                                            if (optgroups.length > 0) {
                                                $optgroup.appendTo($select);
                                            }

                                            jQuery.each(optgroups, function(groupName, options) {
                                                    var coption = jQuery('<option />');
                                                    coption.attr('value', options.name).text(options.name);
                                                    coption.attr('countryid', options.id);

                                                    if (presel) {
                                                        if (presel.toUpperCase() == options.id) {
                                                            coption.attr('selected', 'selected');
                                                        }
                                                    }

                                                    coption.appendTo($optgroup);
                                                }

                                            );
                                        }

                                    );
                                } else {
                                    jQuery.each(data['result'], function(key, val) {
                                            var option = jQuery('<option />');
                                            option.attr('value', val).text(val);
                                            option.attr('countryid', key);

                                            if (presel) {
                                                if (presel.toUpperCase() == key) {
                                                    option.attr('selected', 'selected');
                                                }
                                            }

                                            jQuery('.countries').append(option);
                                        }

                                    );
                                }

                                if (presel) {
                                    jQuery('.countries').trigger('change');
                                }

                                jQuery(".countries").prop("disabled", false);
                            } else {
                                alert(data.msg);
                            }
                        }

                    );
                }

                ;

            }

            jQuery(function() {
                    var loc = new locationInfo();
                    loc.getCountries();

                    jQuery(".countries").on("change", function(ev) {
                            var countryId = jQuery("option:selected", this).attr('countryid');

                            if (countryId != '') {
                                loc.getStates(countryId);
                            } else {
                                jQuery(".states option:gt(0)").remove();
                            }
                        }

                    );

                    jQuery(".states").on("change", function(ev) {
                            var stateId = jQuery("option:selected", this).attr('stateid');

                            if (stateId != '') {
                                loc.getCities(stateId);
                            } else {
                                jQuery(".cities option:gt(0)").remove();
                            }
                        }

                    );

                    jQuery(".cities").on("change", function(ev) {
                            var cityId = jQuery("option:selected", this).val();

                            if (cityId != '') {
                                loc.confCity(cityId);
                            }
                        }

                    );
                }

            );
        </script>
    </div><?php

        }
        function Arb_one()
        {
            ob_start();
            auth_payment_form();
            return ob_get_clean();
        }
        add_shortcode('form1', 'Arb_one');
        function Arb_two()
        {
            ob_start();
            auth_payment_form2();
            return ob_get_clean();
        }
        add_shortcode('form2', 'Arb_two');
        function cancel_subscription()
        {
            ob_start();

            if (isset($_POST['Cancel_Subscription'])) {
                $subscriptionId = $_POST['sub_id'];
                require_once plugin_dir_path(__FILE__)  . 'inc/create_arb_api.php';
                cancelSubscription($subscriptionId);
            } ?>
    <form action="<?php __FILE__ ?>" method="post">
        <label>Enter your Subscription Id</label><br>
        <input type="text" class="inp" name="sub_id" /><br>
        <button name="Cancel_Subscription" class="sub-btn">Cancel Subscription</button>
    </form><?php

            return ob_get_clean();
        }
        add_shortcode('cancel', 'cancel_subscription');
