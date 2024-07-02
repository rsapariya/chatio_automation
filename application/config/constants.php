<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


$root = 'https://official.thebrandingmonk.com/';
if (php_sapi_name() != "cli") {
    $root = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];
    $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
}
defined('BASE_URL') OR define('BASE_URL', $root); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_JS_PATH') OR define('DEFAULT_ADMIN_JS_PATH', BASE_URL . 'assets/js/'); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_CSS_PATH') OR define('DEFAULT_ADMIN_CSS_PATH', BASE_URL . 'assets/css/'); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_IMAGE_PATH') OR define('DEFAULT_ADMIN_IMAGE_PATH', BASE_URL . 'assets/img/'); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_ASSET_PATH') OR define('DEFAULT_ADMIN_ASSET_PATH', BASE_URL . 'assets/'); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_UPLOAD_PATH') OR define('DEFAULT_ADMIN_UPLOAD_PATH', 'upload/excel_import/'); // highest automatically-assigned error code
defined('DEFAULT_ADMIN_INQUIRY_UPLOAD_PATH') OR define('DEFAULT_ADMIN_INQUIRY_UPLOAD_PATH', 'upload/excel_import_inquiries/'); // highest automatically-assigned error code
defined('DEFAULT_IMAGE_UPLOAD_PATH') OR define('DEFAULT_IMAGE_UPLOAD_PATH', 'upload/automation_image/'); // highest automatically-assigned error code
defined('ATTACHMENT_IMAGE_UPLOAD_PATH') OR define('ATTACHMENT_IMAGE_UPLOAD_PATH', 'upload/message_attach_image/'); // highest automatically-assigned error code
defined('API_IMAGE_UPLOAD_PATH') OR define('API_IMAGE_UPLOAD_PATH', 'upload/api_image/'); // highest automatically-assigned error code
define('MB', 1048576);
define('IMAGE_MAX_UPLOAD_SIZE', 5);
define('VIDEO_MAX_UPLOAD_SIZE', 100);
defined('allowed_image_upload_size') OR define('allowed_image_upload_size', 5);
defined('allowed_video_upload_size') OR define('allowed_video_upload_size', 15);
defined('allowed_pdf_upload_size') OR define('allowed_pdf_upload_size', 100);

// Tables
defined('tbl_users') OR define('tbl_users', 'users');
defined('tbl_clients') OR define('tbl_clients', 'clients');
defined('tbl_user_settings') OR define('tbl_user_settings', 'user_settings');
defined('tbl_templates') OR define('tbl_templates', 'templates');
defined('tbl_user_templates') OR define('tbl_user_templates', 'user_default_templates');
defined('tbl_automations') OR define('tbl_automations', 'automations');
defined('tbl_inquiries') OR define('tbl_inquiries', 'inquiries');
defined('tbl_recurrings') OR define('tbl_recurrings', 'recurrings');
defined('tbl_inquiry_types') OR define('tbl_inquiry_types', 'inquiry_types');
defined('tbl_inquiry_logs') OR define('tbl_inquiry_logs', 'inquiry_logs');
defined('tbl_reply_messages') OR define('tbl_reply_messages', 'reply_messages');
defined('tbl_button_reply_logs') OR define('tbl_button_reply_logs', 'button_reply_logs');
defined('tbl_chat_logs') OR define('tbl_chat_logs', 'chat_logs');
defined('tbl_default_templates') OR define('tbl_default_templates', 'default_templates');



defined('tbl_indiamart_customer_leads') OR define('tbl_indiamart_customer_leads', 'indiamart_customer_leads');
defined('tbl_indiamart_inquiries') OR define('tbl_indiamart_inquiries', 'indiamart_inquiries');
defined('tbl_indiamart_inquiry_logs') OR define('tbl_indiamart_inquiry_logs', 'indiamart_inquiry_logs');
defined('tbl_indiamart_leads_message') OR define('tbl_indiamart_leads_message', 'indiamart_leads_message');
defined('tbl_lead_notify_log') OR define('tbl_lead_notify_log', 'lead_notify_log');
defined('tbl_queue_running') OR define('tbl_queue_running', 'queue_running');
defined('tbl_tags') OR define('tbl_tags', 'tags');
defined('tbl_country_time_zone') OR define('tbl_country_time_zone', 'country_time_zone');

defined('DEFAULT_QUEUE_TEMP_PATH') OR define('DEFAULT_QUEUE_TEMP_PATH', sys_get_temp_dir() . '/');

defined('crm_indiamart') OR define('crm_indiamart', 'indiamart');
defined('crm_tradeindia') OR define('crm_tradeindia', 'tradeindia');
defined('crm_exportersindia') OR define('crm_exportersindia', 'exportersindia');





