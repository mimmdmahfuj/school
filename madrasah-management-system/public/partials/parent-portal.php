<?php
if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
$parent = new MMS_Parent();
$parent_data = $parent->get_parent_by_user_id($user->ID);
$children = $parent->get_children($parent_data->id);
$attendance = $parent->get_children_attendance($parent_data->id);
$grades = $parent->get_children_grades($parent_data->id);
$homework = $parent->get_children_homework($parent_data->id);
?>

<div class="mms-parent-portal">
    <div class="mms-profile-section">
        <h2>Parent Profile</h2>
        <div class="mms-profile-info">
            <p><strong>Name:</strong> <?php echo esc_html($parent_data->first_name . ' ' . $parent_data->last_name); ?></p>
            <p><strong>Email:</strong> <?php echo esc_html($parent_data->email); ?></p>
            <p><strong>Phone:</strong> <?php echo esc_html($parent_data->phone); ?></p>
            <p><strong>Emergency Contact:</strong> <?php echo esc_html($parent_data->emergency_contact); ?></p>
        </div>
    </div>

    <div class="mms-children-section">
        <h2>My Children</h2>
        <?php foreach ($children as $child): ?>
            <div class="mms-child-card">
                <h3><?php echo esc_html($child->first_name . ' ' . $child->last_name); ?></h3>
                <p><strong>Class:</strong> <?php echo esc_html($child->class_name); ?></p>
                <p><strong>Admission Number:</strong> <?php echo esc_html($child->admission_number); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mms-attendance-section">
        <h2>Recent Attendance</h2>
        <table class="mms-attendance-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?php echo esc_html(date('Y-m-d', strtotime($record->date))); ?></td>
                        <td><?php echo esc_html($record->first_name . ' ' . $record->last_name); ?></td>
                        <td><?php echo esc_html($record->status); ?></td>
                        <td><?php echo esc_html($record->remarks); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mms-grades-section">
        <h2>Recent Grades</h2>
        <table class="mms-grades-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Term</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo esc_html($grade->first_name . ' ' . $grade->last_name); ?></td>
                        <td><?php echo esc_html($grade->subject_name); ?></td>
                        <td><?php echo esc_html($grade->term); ?></td>
                        <td><?php echo esc_html($grade->grade); ?></td>
                        <td><?php echo esc_html($grade->remarks); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mms-homework-section">
        <h2>Homework</h2>
        <table class="mms-homework-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($homework as $assignment): ?>
                    <tr>
                        <td><?php echo esc_html($assignment->subject_name); ?></td>
                        <td><?php echo esc_html($assignment->title); ?></td>
                        <td><?php echo esc_html(date('Y-m-d', strtotime($assignment->due_date))); ?></td>
                        <td><?php echo esc_html($assignment->status); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>