<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = CS_PROFILE_OPTION;

//
// Create profile options
//
CSF::createProfileOptions($prefix, array(
    'data_type' => 'unserialize',
));

/**
 * 用户高级信息设置
 */
CSF::createSection($prefix, array(
    'title'  => '用户高级信息',
    'fields' => array(

      

    ),
));
