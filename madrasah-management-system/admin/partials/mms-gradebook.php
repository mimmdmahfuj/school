<?php
if (!defined('ABSPATH')) {
    exit;
}

$gradebook = new MMS_Gradebook();
$teacher = new MMS_Teacher();
$class_id = isset($_GET['class']) ? intval($_GET['class']) : 0;
$subject_id = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
$term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : 'Term 1';

$teacher_data = $teacher->get_teacher_by_user_id(get_current_user_id());
$classes = $teacher->get_teacher_classes($teacher_data->id);
$subjects = $teacher->get_assigned_subjects($teacher_data->id);

if ($class_id && $subject_id) {
    $students = (new MMS_Student())->get_students_by_class($class_id);
    $grades = $gradebook->get_class_grades($class_id, $subject_id, $term);
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="mms-gradebook-filters">
        <form method="get" action="">
            <input type="hidden" name="page" value="mms-gradebook">
            
            <select name="class" id="class-select">
                <option value="">Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo esc_attr($class->id); ?>" <?php selected($class_id, $class->id); ?>>
                        <?php echo esc_html($class->name . ' - ' . $class->section); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="subject" id="subject-select">
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?php echo esc_attr($subject->subject_id); ?>" <?php selected($subject_id, $subject->subject_id); ?>>
                        <?php echo esc_html($subject->subject_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="term">
                <option value="Term 1" <?php selected($term, 'Term 1'); ?>>Term 1</option>
                <option value="Term 2" <?php selected($term, 'Term 2'); ?>>Term 2</option>
                <option value="Term 3" <?php selected($term, 'Term 3'); ?>>Term 3</option>
                <option value="Final" <?php selected($term, 'Final'); ?>>Final</option>
            </select>

            <button type="submit" class="button">Filter</button>
        </form>
    </div>

    <?php if ($class_id && $subject_id && $students): ?>
        <form method="post" action="">
            <?php wp_nonce_field('save_grades', 'gradebook_nonce'); ?>
            <input type="hidden" name="class_id" value="<?php echo esc_attr($class_id); ?>">
            <input type="hidden" name="subject_id" value="<?php echo esc_attr($subject_id); ?>">
            <input type="hidden" name="term" value="<?php echo esc_attr($term); ?>">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): 
                        $grade_record = array_filter($grades, function($grade) use ($student) {
                            return $grade->student_id == $student->id;
                        });
                        $grade_record = reset($grade_record);
                    ?>
                        <tr>
                            <td><?php echo esc_html($student->first_name . ' ' . $student->last_name); ?></td>
                            <td>
                                <select name="grades[<?php echo esc_attr($student->id); ?>][grade]">
                                    <option value="">Select Grade</option>
                                    <option value="A+" <?php selected($grade_record ? $grade_record->grade : '', 'A+'); ?>>A+</option>
                                    <option value="A" <?php selected($grade_record ? $grade_record->grade : '', 'A'); ?>>A</option>
                                    <option value="A-" <?php selected($grade_record ? $grade_record->grade : '', 'A-'); ?>>A-</option>
                                    <option value="B+" <?php selected($grade_record ? $grade_record->grade : '', 'B+'); ?>>B+</option>
                                    <option value="B" <?php selected($grade_record ? $grade_record->grade : '', 'B'); ?>>B</option>
                                    <option value="B-" <?php selected($grade_record ? $grade_record->grade : '', 'B-'); ?>>B-</option>
                                    <option value="C+" <?php selected($grade_record ? $grade_record->grade : '', 'C+'); ?>>C+</option>
                                    <option value="C" <?php selected($grade_record ? $grade_record->grade : '', 'C'); ?>>C</option>
                                    <option value="C-" <?php selected($grade_record ? $grade_record->grade : '', 'C-'); ?>>C-</option>
                                    <option value="D+" <?php selected($grade_record ? $grade_record->grade : '', 'D+'); ?>>D+</option>
                                    <option value="D" <?php selected($grade_record ? $grade_record->grade : '', 'D'); ?>>D</option>
                                    <option value="F" <?php selected($grade_record ? $grade_record->grade : '', 'F'); ?>>F</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="grades[<?php echo esc_attr($student->id); ?>][remarks]" 
                                    value="<?php echo esc_attr($grade_record ? $grade_record->remarks : ''); ?>"
                                    class="regular-text">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="submit">
                <button type="submit" name="save_grades" class="button button-primary">Save Grades</button>
            </p>
        </form>
    <?php elseif ($class_id && $subject_id): ?>
        <p>No students found in this class.</p>
    <?php else: ?>
        <p>Please select a class and subject to manage grades.</p>
    <?php endif; ?>
</div>