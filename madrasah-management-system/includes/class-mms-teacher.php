<?php
class MMS_Teacher {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'mms_teachers';
    }

    public function add_teacher($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'user_id' => $data['user_id'],
                'employee_id' => $data['employee_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'qualification' => $data['qualification'],
                'subjects' => maybe_serialize($data['subjects']),
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address']
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );

        return $result;
    }

    public function get_teacher($id) {
        $teacher = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );

        if ($teacher) {
            $teacher->subjects = maybe_unserialize($teacher->subjects);
        }

        return $teacher;
    }

    public function get_teacher_by_user_id($user_id) {
        $teacher = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE user_id = %d",
                $user_id
            )
        );

        if ($teacher) {
            $teacher->subjects = maybe_unserialize($teacher->subjects);
        }

        return $teacher;
    }

    public function update_teacher($id, $data) {
        if (isset($data['subjects'])) {
            $data['subjects'] = maybe_serialize($data['subjects']);
        }

        return $this->wpdb->update(
            $this->table_name,
            $data,
            array('id' => $id)
        );
    }

    public function delete_teacher($id) {
        return $this->wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );
    }

    public function get_all_teachers() {
        $teachers = $this->wpdb->get_results("SELECT * FROM {$this->table_name}");
        
        foreach ($teachers as $teacher) {
            $teacher->subjects = maybe_unserialize($teacher->subjects);
        }

        return $teachers;
    }

    public function get_teacher_schedule($teacher_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT s.*, c.name as class_name, c.section 
                FROM {$this->wpdb->prefix}mms_schedules s 
                JOIN {$this->wpdb->prefix}mms_classes c ON s.class_id = c.id 
                WHERE s.teacher_id = %d",
                $teacher_id
            )
        );
    }

    public function get_teacher_classes($teacher_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}mms_classes 
                WHERE teacher_id = %d",
                $teacher_id
            )
        );
    }

    public function get_teaching_load($teacher_id) {
        return $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}mms_schedules 
                WHERE teacher_id = %d",
                $teacher_id
            )
        );
    }

    public function assign_subject($teacher_id, $subject_id, $class_id) {
        return $this->wpdb->insert(
            $this->wpdb->prefix . 'mms_teacher_subjects',
            array(
                'teacher_id' => $teacher_id,
                'subject_id' => $subject_id,
                'class_id' => $class_id
            ),
            array('%d', '%d', '%d')
        );
    }

    public function get_assigned_subjects($teacher_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT ts.*, s.name as subject_name, c.name as class_name 
                FROM {$this->wpdb->prefix}mms_teacher_subjects ts
                JOIN {$this->wpdb->prefix}mms_subjects s ON ts.subject_id = s.id
                JOIN {$this->wpdb->prefix}mms_classes c ON ts.class_id = c.id
                WHERE ts.teacher_id = %d",
                $teacher_id
            )
        );
    }
}