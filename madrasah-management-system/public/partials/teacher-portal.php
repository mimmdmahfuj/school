<?php
if (!defined('ABSPATH')) {
    exit;
}

$user = wp_get_current_user();
$teacher = new MMS_Teacher();
$teacher_data = $teacher->get_teacher_by_user_id($user->ID);
$schedule = $teacher->get_teacher_schedule($teacher_data->id);
$classes = $teacher->get_teacher_classes($teacher_data->id);
?>

<div class="mms-teacher-portal">
    <div class="mms-profile-section">
        <h2>Teacher Profile</h2>
        <div class="mms-profile-info">
            <p><strong>Name:</strong> <?php echo esc_html($teacher_data->first_name . ' ' . $teacher_data->last_name); ?></p>
            <p><strong>Employee ID:</strong> <?php echo esc_html($teacher_data->employee_id); ?></p>
            <p><strong>Qualification:</strong> <?php echo esc_html($teacher_data->qualification); ?></p>
            <p><strong>Email:</strong> <?php echo esc_html($teacher_data->email); ?></p>
            <p><strong>Phone:</strong> <?php echo esc_html($teacher_data->phone); ?></p>
        </div>
    </div>

    <div class="mms-schedule-section">
        <h2>Teaching Schedule</h2>
        <table class="mms-schedule-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $period): ?>
                    <tr>
                        <td><?php echo esc_html($period->time_slot); ?></td>
                        <td><?php echo esc_html($period->monday_class); ?></td>
                        <td><?php echo esc_html($period->tuesday_class); ?></td>
                        <td><?php echo esc_html($period->wednesday_class); ?></td>
                        <td><?php echo esc_html($period->thursday_class); ?></td>
                        <td><?php echo esc_html($period->friday_class); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mms-classes-section">
        <h2>My Classes</h2>
        <?php foreach ($classes as $class): ?>
            <div class="mms-class-card">
                <h3><?php echo esc_html($class->name . ' - ' . $class->section); ?></h3>
                <div class="mms-class-actions">
                    <a href="?page=attendance&class=<?php echo esc_attr($class->id); ?>" class="button">Take Attendance</a>
                    <a href="?page=gradebook&class=<?php echo esc_attr($class->id); ?>" class="button">Gradebook</a>
                    <a href="?page=homework&class=<?php echo esc_attr($class->id); ?>" class="button">Homework</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>