<?php
class MMS_Parent {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'mms_parents';
    }

    public function add_parent($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'user_id' => $data['user_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'relationship' => $data['relationship'],
                'occupation' => $data['occupation'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'emergency_contact' => $data['emergency_contact']
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );

        return $result;
    }

    public function get_parent($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );
    }

    public function get_parent_by_user_id($user_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE user_id = %d",
                $user_id
            )
        );
    }

    public function update_parent($id, $data) {
        return $this->wpdb->update(
            $this->table_name,
            $data,
            array('id' => $id)
        );
    }

    public function delete_parent($id) {
        return $this->wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );
    }

    public function get_all_parents() {
        return $this->wpdb->get_results("SELECT * FROM {$this->table_name}");
    }

    public function get_children($parent_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT s.* FROM {$this->wpdb->prefix}mms_students s 
                WHERE s.parent_id = %d",
                $parent_id
            )
        );
    }

    public function get_children_attendance($parent_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT a.*, s.first_name, s.last_name 
                FROM {$this->wpdb->prefix}mms_attendance a
                JOIN {$this->wpdb->prefix}mms_students s ON a.student_id = s.id
                WHERE s.parent_id = %d
                ORDER BY a.date DESC",
                $parent_id
            )
        );
    }

    public function get_children_grades($parent_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT g.*, s.first_name, s.last_name, sub.name as subject_name
                FROM {$this->wpdb->prefix}mms_grades g
                JOIN {$this->wpdb->prefix}mms_students s ON g.student_id = s.id
                JOIN {$this->wpdb->prefix}mms_subjects sub ON g.subject_id = sub.id
                WHERE s.parent_id = %d
                ORDER BY g.created_at DESC",
                $parent_id
            )
        );
    }

    public function get_children_homework($parent_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT h.*, s.first_name, s.last_name, sub.name as subject_name
                FROM {$this->wpdb->prefix}mms_homework h
                JOIN {$this->wpdb->prefix}mms_students s ON h.class_id = s.class_id
                JOIN {$this->wpdb->prefix}mms_subjects sub ON h.subject_id = sub.id
                WHERE s.parent_id = %d
                ORDER BY h.due_date DESC",
                $parent_id
            )
        );
    }
}