<?php

echo $args['before_widget'];
 
if (!empty($instance['title'])) {
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
}

echo '<p>Content</p>';

echo $args['after_widget'];