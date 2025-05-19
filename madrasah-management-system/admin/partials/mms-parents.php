<?php
if (!defined('ABSPATH')) {
    exit;
}

$parent = new MMS_Parent();
$parents = $parent->get_all_parents();
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="tablenav top">
        <div class="alignleft actions">
            <a href="<?php echo admin_url('admin.php?page=mms-parents&action=add'); ?>" class="button button-primary">Add New Parent</a>
        </div>
        <br class="clear">
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Relationship</th>
                <th>Occupation</th>
                <th>Contact</th>
                <th>Children</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parents as $parent_item): 
                $children = $parent->get_children($parent_item->id);
                $children_names = array_map(function($child) {
                    return $child->first_name . ' ' . $child->last_name;
                }, $children);
            ?>
                <tr>
                    <td><?php echo esc_html($parent_item->first_name . ' ' . $parent_item->last_name); ?></td>
                    <td><?php echo esc_html($parent_item->relationship); ?></td>
                    <td><?php echo esc_html($parent_item->occupation); ?></td>
                    <td>
                        <?php echo esc_html($parent_item->email); ?><br>
                        <?php echo esc_html($parent_item->phone); ?>
                    </td>
                    <td><?php echo esc_html(implode(', ', $children_names)); ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=mms-parents&action=edit&id=' . $parent_item->id); ?>" class="button button-small">Edit</a>
                        <a href="<?php echo admin_url('admin.php?page=mms-parents&action=view&id=' . $parent_item->id); ?>" class="button button-small">View</a>
                        <a href="<?php echo admin_url('admin.php?page=mms-parents&action=delete&id=' . $parent_item->id); ?>" class="button button-small button-link-delete" onclick="return confirm('Are you sure you want to delete this parent?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>