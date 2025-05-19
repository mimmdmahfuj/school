<?php
class MMS_Activator {
    public static function activate() {
        self::create_tables();
        self::create_roles();
        self::create_pages();
    }

    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = array();

        // Students table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mms_students (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            admission_number varchar(50) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            date_of_birth date NOT NULL,
            gender varchar(10) NOT NULL,
            address text NOT NULL,
            phone varchar(20) NOT NULL,
            email varchar(100) NOT NULL,
            class_id bigint(20) NOT NULL,
            parent_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Teachers table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mms_teachers (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            employee_id varchar(50) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            qualification varchar(100) NOT NULL,
            subjects text NOT NULL,
            phone varchar(20) NOT NULL,
            email varchar(100) NOT NULL,
            address text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Classes table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mms_classes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            section varchar(50) NOT NULL,
            teacher_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Attendance table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mms_attendance (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            student_id bigint(20) NOT NULL,
            class_id bigint(20) NOT NULL,
            date date NOT NULL,
            status varchar(20) NOT NULL,
            remarks text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Grades table
        $sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mms_grades (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            student_id bigint(20) NOT NULL,
            subject_id bigint(20) NOT NULL,
            term varchar(20) NOT NULL,
            grade varchar(10) NOT NULL,
            remarks text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        foreach ($sql as $query) {
            dbDelta($query);
        }
    }

    private static function create_roles() {
        add_role('mms_student', 'Student', array(
            'read' => true,
            'view_grades' => true,
            'view_attendance' => true,
            'submit_homework' => true
        ));

        add_role('mms_teacher', 'Teacher', array(
            'read' => true,
            'manage_grades' => true,
            'manage_attendance' => true,
            'manage_homework' => true,
            'view_reports' => true
        ));

        add_role('mms_parent', 'Parent', array(
            'read' => true,
            'view_grades' => true,
            'view_attendance' => true,
            'view_homework' => true
        ));
    }

    private static function create_pages() {
        $pages = array(
            'student-portal' => array(
                'title' => 'Student Portal',
                'content' => '[mms_student_portal]'
            ),
            'teacher-portal' => array(
                'title' => 'Teacher Portal',
                'content' => '[mms_teacher_portal]'
            ),
            'parent-portal' => array(
                'title' => 'Parent Portal',
                'content' => '[mms_parent_portal]'
            )
        );

        foreach ($pages as $slug => $page) {
            if (get_page_by_path($slug) === null) {
                wp_insert_post(array(
                    'post_title' => $page['title'],
                    'post_content' => $page['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug
                ));
            }
        }
    }
}