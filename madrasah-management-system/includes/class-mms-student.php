<?php
class MMS_Student {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'mms_students';
    }

    public function add_student($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'user_id' => $data['user_id'],
                'admission_number' => $data['admission_number'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'class_id' => $data['class_id'],
                'parent_id' => $data['parent_id']
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d')
        );

        return $result;
    }

    public function get_student($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );
    }

    public function get_student_by_user_id($user_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE user_id = %d",
                $user_id
            )
        );
    }

    public function update_student($id, $data) {
        return $this->wpdb->update(
            $this->table_name,
            $data,
            array('id' => $id)
        );
    }

    public function get_student_grades($student_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}mms_grades WHERE student_id = %d",
                $student_id
            )
        );
    }

    public function get_student_attendance($student_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}mms_attendance WHERE student_id = %d",
                $student_id
            )
        );
    }

    public function get_student_schedule($student_id) {
        $class_id = $this->get_student($student_id)->class_id;
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}mms_schedules WHERE class_id = %d",
                $class_id
            )
        );
    }

    public function get_all_students() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name}");
    }

    public function get_students_by_class($class_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE class_id = %d",
                $class_id
            )
        );
    }
}