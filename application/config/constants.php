<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
 */
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
 */
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
 */
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// define('GoogleMapKey','AIzaSyAsn6KGL3R5IaZBQnVr5LowBTG9s19cRrc');
define('GoogleMapKey','AIzaSyBH7pmiDU016Cg76ffpkYQWcFQ4NaAC2VI');
define('RootAppId','5df095d632834faf9445b944d04121a2');
define('BingMAp','AtzFrQsruznA-qMUoFg0sjmusWARhhxX3FwYb3Q4ho0vsbSjnmLsET6gjcVPaKeM');
define('DarkApiKey','13d9f0513ccb7401868029c05ea94b31');
define('openweathermap','9914c8e12b1d6a30eb4a6207a13ac4dd');
define('helpEmailTo','support@spraye.io');
define('OPENSSL_KEY','42264528482B4D6251655468576D5A7134743777217A25432A462D4A404E6352');
define('AES_256_CBC', 'AES-256-CBC');
define('SEND_G', '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');

// define('public_api_key', 	'pk_test_LHU9mz6SUmXDHxWqRaEUx0Mk00Pz0UAAzz');
// define('secret_key', 'sk_test_HoAkqjSMF1LhzltzesAejpk400M5DgjObX');

// define('public_api_key',     'pk_test_LHU9mz6SUmXDHxWqRaEUx0Mk00Pz0UAAzz');
// define('secret_key', 'sk_test_HoAkqjSMF1LhzltzesAejpk400M5DgjObX');

define('public_api_key', 'pk_live_JsLv3ZNM9L3jpL9I6zjnAlL900mAp8MPnN');
define('secret_key', 'sk_live_J2BOnEuxsIlF5y9lwGoY9jEW003DnkP5Fn');

//
define('monthly_sub_id', 'que123qwea');
define('yearly_sub_id', 'dgeb5454se');
define('non_paid_sub_id', 'ag3bk4g4hq');
define('free_plan_id', 'plan_GUoJ4IFhyDrdUG');
define('EMAIL_ADDRESS', 'support@spraye.io');
// define('EMAIL_ADDRESS', 'hemant.rajak@canopusinfosystems.in');
if(isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
  define('CARDCONNECT_URL', 'https://fts.cardconnect.com/cardconnect/rest/');
  define('CARDCONNECT_TOKEN_URL', 'https://fts.cardconnect.com/itoke/ajax-tokenizer.html');
  define('CARDCONNECT_CSURL', 'https://fts.cardconnect.com/cardsecure/api/v1/ccn/tokenize');
  define('CAPTURE_URL', 'https://fts.cardconnect.com/cardconnect/rest/capture');
  define('INQUIRE_URL', 'https://fts.cardconnect.com/cardconnect/rest/inquire/');
  define('PROFILE_URL', 'https://fts.cardconnect.com/cardconnect/rest/profile/');
  define('BASYS_URL', 'https://app.basysiqpro.com/');
  define('S3_BUCKET_NAME','spraye-production');
  define('CLOUDFRONT_URL','https://assets-dashboard.spraye.io/');
  define('SIGNWELL_TEST_MODE','false');	
  define('GLOBAL_EMAIL_ON','true');	
} else {
  define('CARDCONNECT_URL', 'https://fts-uat.cardconnect.com/cardconnect/rest/');
  define('CARDCONNECT_TOKEN_URL', 'https://fts-uat.cardconnect.com/itoke/ajax-tokenizer.html');
  define('CARDCONNECT_CSURL', 'https://fts-uat.cardconnect.com/cardsecure/api/v1/ccn/tokenize');
  define('CAPTURE_URL', 'https://fts-uat.cardconnect.com/cardconnect/rest/capture');
  define('INQUIRE_URL', 'https://fts-uat.cardconnect.com/cardconnect/rest/inquire/');
  define('PROFILE_URL', 'https://fts-uat.cardconnect.com/cardconnect/rest/profile/');
  define('BASYS_URL', 'https://sandbox.basysiqpro.com/');
  define('S3_BUCKET_NAME','spraye-staging');
  define('CLOUDFRONT_URL','https://assets-dashboard-staging.spraye.io/');
  define('SIGNWELL_TEST_MODE','true');
  define('GLOBAL_EMAIL_ON','false');	
}