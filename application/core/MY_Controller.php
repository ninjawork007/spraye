<?php

class MY_Controller extends MX_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->module('layout');
        //$this->load->module('admin');
        $this->load->helper('Treecategory');
        $this->load->library('form_validation');
        date_default_timezone_set('America/Chicago');
    }
    
    const SIGNUP="User signed up successfully. Please login.";
    const PASSWORD_RESET_LINK="Password reset link has been sent to your email address";
    const WENTWRONG="Something went wrong";
    const LOGINFAILD="Invalid username or password";
    const NOT_REGISTER="Email address does not exist";
    const LOGIN="Login successful";
    const LOGOUT="Logout successful";
    const NOTFOUND="No record found";
    const AUTHFAIL="Your session has expired. Please log in again.";
    const HELPCENTER="Your message has been sent to the admin.";
    const PREFERENCE="Your preference has been added.";
    const PREFERENCE_UPDATE="Your preference has been updated.";
    const PAYMENT_INFO="Your payment info save successfully.";
    const PAYMENT_INFO_UPDATE="Your payment info update successfully.";
    const INVALID_MOBILE="Invalid mobile no..";
    const MOBILE_VERIFICATION="Verification code has been sent to your register mobile no.";
    const MOBILE_ALREADY="Mobile no already exists.";
    const MOBILE_UPDATE="Your mobile no has been updated.";
    const INVALID_VERIFICATION="Invalid verification code.";
    const VALID_VERIFICATION="Verification code matched succesfully.";
    const VERIFY_IDENTIFY="User identity has been added.";
    const UPDATE_IDENTIFY="User identity has been updated.";
    const SESSION_DETAIL="Session detail save succesfully.";
    const UODATE_USER_PROFILE="User details has been updated.";
    
    
}