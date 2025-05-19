<?php
class MMS_Gradebook {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'mms_grades';
    }

    public function add_grade($data) {
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'student_id' => $data['student_id'],
                'subject_id' => $data['subject_id'],
                'term' => $data['term'],
                'grade' => $data['grade'],
                'remarks' => $data['remarks']
            ),
            array('%d', '%d', '%s', '%s', '%s')
        );

        return $result;
    }

    public function update_grade($id, $data) {
        return $this->wpdb->update(
            $this->table_name,
            array(
                'grade' => $data['grade'],
                'remarks' => $data['remarks']
            ),
            array('id' => $id),
            array('%s', '%s'),
            array('%d')
        );
    }

    public function get_grade($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );
    }

    public function get_student_grades($student_id, $term = null) {
        $query = "SELECT g.*, s.name as subject_name 
                 FROM {$this->table_name} g
                 JOIN {$this->wpdb->prefix}mms_subjects s ON g.subject_id = s.id
                 WHERE g.student_id = %d";
        $params = array($student_id);

        if ($term) {
            $query .= " AND g.term = %s";
            $params[] = $term;
        }

        $query .= " ORDER BY g.created_at DESC";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($query, $params)
        );
    }

    public function get_class_grades($class_id, $subject_id, $term) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT g.*, s.first_name, s.last_name 
                FROM {$this->table_name} g
                JOIN {$this->wpdb->prefix}mms_students s ON g.student_id = s.id
                WHERE s.class_id = %d AND g.subject_id = %d AND g.term = %s
                ORDER BY s.last_name, s.first_name",
                $class_id,
                $subject_id,
                $term
            )
        );
    }

    public function get_grade_summary($student_id, $term = null) {
        $query = "SELECT 
            s.name as subject_name,
            AVG(CAST(g.grade AS DECIMAL(5,2))) as average_grade,
            MIN(g.grade) as lowest_grade,
            MAX(g.grade) as highest_grade
            FROM {$this->table_name} g
            JOIN {$this->wpdb->prefix}mms_subjects s ON g.subject_id = s.id
            WHERE g.student_id = %d";
        
        $params = array($student_id);

        if ($term) {
            $query .= " AND g.term = %s";
            $params[] = $term;
        }

        $query .= " GROUP BY s.id, s.name";

        return $this->wpdb->get_results(
            $this->wpdb->prepare($query, $params)
        );
    }

    public function delete_grade($id) {
        return $this->wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );
    }

    public function calculate_gpa($student_id, $term = null) {
        $grades = $this->get_student_grades($student_id, $term);
        $total_points = 0;
        $total_subjects = count($grades);

        foreach ($grades as $grade) {
            $total_points += $this->convert_grade_to_points($grade->grade);
        }

        return $total_subjects > 0 ? $total_points / $total_subjects : 0;
    }

    private function convert_grade_to_points($grade) {
        $grade_points = array(
            'A+' => 4.0,
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'F' => 0.0
        );

        return isset($grade_points[$grade]) ? $grade_points[$grade] : 0;
    }
}