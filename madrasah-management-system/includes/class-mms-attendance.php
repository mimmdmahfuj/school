<?php
class MMS_Attendance {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'mms_attendance';
    }

    public function mark_attendance($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'student_id' => $data['student_id'],
                'class_id' => $data['class_id'],
                'date' => $data['date'],
                'status' => $data['status'],
                'remarks' => $data['remarks']
            ),
            array('%d', '%d', '%s', '%s', '%s')
        );

        return $result;
    }

    public function update_attendance($id, $data) {
        return $this->wpdb->update(
            $this->table_name,
            array(
                'status' => $data['status'],
                'remarks' => $data['remarks']
            ),
            array('id' => $id),
            array('%s', '%s'),
            array('%d')
        );
    }

    public function get_attendance($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );
    }

    public function get_class_attendance($class_id, $date) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT a.*, s.first_name, s.last_name 
                FROM {$this->table_name} a
                JOIN {$this->wpdb->prefix}mms_students s ON a.student_id = s.id
                WHERE a.class_id = %d AND a.date = %s",
                $class_id,
                $date
            )
        );
    }

    public function get_student_attendance($student_id, $start_date = null, $end_date = null) {
        $query = "SELECT * FROM {$this->table_name} WHERE student_id = %d";
        $params = array($student_id);

        if ($start_date && $end_date) {
            $query .= " AND date BETWEEN %s AND %s";
            $params[] = $start_date;
            $params[] = $end_date;
        }

        $query .= " ORDER BY date DESC";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($query, $params)
        );
    }

    public function get_attendance_summary($student_id, $term = null) {
        $query = "SELECT 
            COUNT(*) as total_days,
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
            SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
            SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_days
            FROM {$this->table_name}
            WHERE student_id = %d";
        
        $params = array($student_id);

        if ($term) {
            $query .= " AND date BETWEEN %s AND %s";
            $params[] = $term['start_date'];
            $params[] = $term['end_date'];
        }

        return $this->wpdb->get_row(
            $this->wpdb->prepare($query, $params)
        );
    }

    public function delete_attendance($id) {
        return $this->wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );
    }
}