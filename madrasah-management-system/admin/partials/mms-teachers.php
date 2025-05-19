<?php
if (!defined('ABSPATH')) {
    exit;
}

$teacher = new MMS_Teacher();
$teachers = $teacher->get_all_teachers();
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="tablenav top">
        <div class="alignleft actions">
            <a href="<?php echo admin_url('admin.php?page=mms-teachers&action=add'); ?>" class="button button-primary">Add New Teacher</a>
        </div>
        <br class="clear">
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Qualification</th>
                <th>Subjects</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teachers as $teacher_item): ?>
                <tr>
                    <td><?php echo esc_html($teacher_item->employee_id); ?></td>
                    <td><?php echo esc_html($teacher_item->first_name . ' ' . $teacher_item->last_name); ?></td>
                    <td><?php echo esc_html($teacher_item->qualification); ?></td>
                    <td><?php echo is_array($teacher_item->subjects) ? esc_html(implode(', ', $teacher_item->subjects)) : esc_html($teacher_item->subjects); ?></td>
                    <td>
                        <?php echo esc_html($teacher_item->email); ?><br>
                        <?php echo esc_html($teacher_item->phone); ?>
                    </td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=mms-teachers&action=edit&id=' . $teacher_item->id); ?>" class="button button-small">Edit</a>
                        <a href="<?php echo admin_url('admin.php?page=mms-teachers&action=view&id=' . $teacher_item->id); ?>" class="button button-small">View</a>
                        <a href="<?php echo admin_url('admin.php?page=mms-teachers&action=delete&id=' . $teacher_item->id); ?>" class="button button-small button-link-delete" onclick="return confirm('Are you sure you want to delete this teacher?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>