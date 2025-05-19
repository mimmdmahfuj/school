<?php
/**
 * Plugin Name: Madrasah Management System
 * Plugin URI: https://your-domain.com/madrasah-management-system
 * Description: A comprehensive school management system for Madrasahs with student, teacher, and parent portals
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://your-domain.com
 * Text Domain: madrasah-management-system
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MMS_VERSION', '1.0.0');
define('MMS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MMS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once MMS_PLUGIN_DIR . 'includes/class-mms-activator.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-core.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-student.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-teacher.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-parent.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-attendance.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-gradebook.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-schedule.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-admissions.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-fees.php';
require_once MMS_PLUGIN_DIR . 'includes/class-mms-messaging.php';

// Activation Hook
register_activation_hook(__FILE__, array('MMS_Activator', 'activate'));

// Initialize the plugin
function run_madrasah_management_system() {
    $plugin = new MMS_Core();
    $plugin->run();
}

run_madrasah_management_system();