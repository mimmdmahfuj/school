<?php
if (!defined('ABSPATH')) {
    exit;
}

$attendance = new MMS_Attendance();
$teacher = new MMS_Teacher();
$class_id = isset($_GET['class']) ? intval($_GET['class']) : 0;
$date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : date('Y-m-d');

$teacher_data = $teacher->get_teacher_by_user_id(get_current_user_id());
$classes = $teacher->get_teacher_classes($teacher_data->id);

if ($class_id) {
    $students = (new MMS_Student())->get_students_by_class($class_id);
    $attendance_records = $attendance->get_class_attendance($class_id, $date);
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="mms-attendance-filters">
        <form method="get" action="">
            <input type="hidden" name="page" value="mms-attendance">
            
            <select name="class" id="class-select">
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo esc_attr($class->id); ?>" <?php selected($class_id, $class->id); ?>>
                        <?php echo esc_html($class->name . ' - ' . $class->section); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="date" name="date" value="<?php echo esc_attr($date); ?>">
            <button type="submit" class="button">Filter</button>
        </form>
    </div>

    <?php if ($class_id && $students): ?>
        <form method="post" action="">
            <?php wp_nonce_field('mark_attendance', 'attendance_nonce'); ?>
            <input type="hidden" name="class_id" value="<?php echo esc_attr($class_id); ?>">
            <input type="hidden" name="date" value="<?php echo esc_attr($date); ?>">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): 
                        $attendance_record = array_filter($attendance_records, function($record) use ($student) {
                            return $record->student_id == $student->id;
                        });
                        $attendance_record = reset($attendance_record);
                    ?>
                        <tr>
                            <td><?php echo esc_html($student->first_name . ' ' . $student->last_name); ?></td>
                            <td>
                                <select name="attendance[<?php echo esc_attr($student->id); ?>][status]">
                                    <option value="present" <?php selected($attendance_record ? $attendance_record->status : '', 'present'); ?>>Present</option>
                                    <option value="absent" <?php selected($attendance_record ? $attendance_record->status : '', 'absent'); ?>>Absent</option>
                                    <option value="late" <?php selected($attendance_record ? $attendance_record->status : '', 'late'); ?>>Late</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="attendance[<?php echo esc_attr($student->id); ?>][remarks]" 
                                    value="<?php echo esc_attr($attendance_record ? $attendance_record->remarks : ''); ?>"
                                    class="regular-text">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="submit">
                <button type="submit" name="mark_attendance" class="button button-primary">Save Attendance</button>
            </p>
        </form>
    <?php elseif ($class_id): ?>
        <p>No students found in this class.</p>
    <?php else: ?>
        <p>Please select a class to mark attendance.</p>
    <?php endif; ?>
</div>