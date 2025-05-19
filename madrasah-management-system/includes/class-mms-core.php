<?php
class MMS_Core {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'madrasah-management-system';
        $this->version = MMS_VERSION;
        
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once MMS_PLUGIN_DIR . 'admin/class-mms-admin.php';
        require_once MMS_PLUGIN_DIR . 'public/class-mms-public.php';
    }

    private function define_admin_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    private function define_public_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
        add_action('init', array($this, 'register_shortcodes'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Madrasah Management',
            'Madrasah Management',
            'manage_options',
            'madrasah-management',
            array($this, 'display_admin_dashboard'),
            'dashicons-welcome-learn-more',
            30
        );

        add_submenu_page(
            'madrasah-management',
            'Students',
            'Students',
            'manage_options',
            'mms-students',
            array($this, 'display_students_page')
        );

        add_submenu_page(
            'madrasah-management',
            'Teachers',
            'Teachers',
            'manage_options',
            'mms-teachers',
            array($this, 'display_teachers_page')
        );

        // Add more submenu pages for other features
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style(
            $this->plugin_name . '-admin',
            MMS_PLUGIN_URL . 'admin/css/mms-admin.css',
            array(),
            $this->version
        );
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script(
            $this->plugin_name . '-admin',
            MMS_PLUGIN_URL . 'admin/js/mms-admin.js',
            array('jquery'),
            $this->version,
            true
        );
    }

    public function enqueue_public_styles() {
        wp_enqueue_style(
            $this->plugin_name . '-public',
            MMS_PLUGIN_URL . 'public/css/mms-public.css',
            array(),
            $this->version
        );
    }

    public function enqueue_public_scripts() {
        wp_enqueue_script(
            $this->plugin_name . '-public',
            MMS_PLUGIN_URL . 'public/js/mms-public.js',
            array('jquery'),
            $this->version,
            true
        );
    }

    public function register_shortcodes() {
        add_shortcode('mms_student_portal', array($this, 'render_student_portal'));
        add_shortcode('mms_teacher_portal', array($this, 'render_teacher_portal'));
        add_shortcode('mms_parent_portal', array($this, 'render_parent_portal'));
    }

    public function display_admin_dashboard() {
        require_once MMS_PLUGIN_DIR . 'admin/partials/mms-admin-dashboard.php';
    }

    public function display_students_page() {
        require_once MMS_PLUGIN_DIR . 'admin/partials/mms-students.php';
    }

    public function display_teachers_page() {
        require_once MMS_PLUGIN_DIR . 'admin/partials/mms-teachers.php';
    }

    public function render_student_portal($atts) {
        if (!is_user_logged_in()) {
            return 'Please log in to access the student portal.';
        }

        $user = wp_get_current_user();
        if (!in_array('mms_student', $user->roles)) {
            return 'Access denied. This portal is for students only.';
        }

        ob_start();
        require_once MMS_PLUGIN_DIR . 'public/partials/student-portal.php';
        return ob_get_clean();
    }

    public function render_teacher_portal($atts) {
        if (!is_user_logged_in()) {
            return 'Please log in to access the teacher portal.';
        }

        $user = wp_get_current_user();
        if (!in_array('mms_teacher', $user->roles)) {
            return 'Access denied. This portal is for teachers only.';
        }

        ob_start();
        require_once MMS_PLUGIN_DIR . 'public/partials/teacher-portal.php';
        return ob_get_clean();
    }

    public function render_parent_portal($atts) {
        if (!is_user_logged_in()) {
            return 'Please log in to access the parent portal.';
        }

        $user = wp_get_current_user();
        if (!in_array('mms_parent', $user->roles)) {
            return 'Access denied. This portal is for parents only.';
        }

        ob_start();
        require_once MMS_PLUGIN_DIR . 'public/partials/parent-portal.php';
        return ob_get_clean();
    }

    public function run() {
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
}