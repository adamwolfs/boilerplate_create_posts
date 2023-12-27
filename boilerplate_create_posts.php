<?php
/**
 * Plugin Name: Boilerplate Create Posts
 * Plugin URI: https://www.adamwolfs.com/
 * Description: Plugin to create multiple pages with Lorem Ipsum text for speedy page development.
 * Version: 1.0
 * Author: Adam Wolfs
 * Author URI: https://www.adamwolfs.com/
 **/

// -------------------------------------------------------------------------------------------------------- //
// PLUGIN NAMESPACE
// -------------------------------------------------------------------------------------------------------- // 
namespace EmbraceGeekComAuBoilerplateCreatePosts;

// -------------------------------------------------------------------------------------------------------- //
// DEFINES
// -------------------------------------------------------------------------------------------------------- //
define('BOILERPLATE_CREATE_POSTS_VERSION', '1.0');
define('BOILERPLATE_CREATE_POSTS_DIR', plugin_dir_path(dirname(__FILE__)) . 'boilerplate_create_posts/');
define('BOILERPLATE_CREATE_POSTS_URL', plugin_dir_url(dirname(__FILE__)) . 'boilerplate_create_posts/');
define('BOILERPLATE_CREATE_POSTS_TITLE', 'Boilerplate Create <span class="change_post_type">Posts</span>');
define('BOILERPLATE_CREATE_POSTS_DESCRIPTION', 'Generates a series of <span class="change_post_type">Posts</span> based on a nested structure using dashes.');
define('BOILERPLATE_CREATE_POSTS_LOREM_COUNT', 10);
define('BOILERPLATE_CREATE_POSTS_LOREM_COUNT_DEFAULT', 3);

// -------------------------------------------------------------------------------------------------------- //
// REQUIRES
// -------------------------------------------------------------------------------------------------------- //
require(BOILERPLATE_CREATE_POSTS_DIR . 'classes\LipsumGenerator.extended.class.php');
require(BOILERPLATE_CREATE_POSTS_DIR . 'classes\boilerplate_create_posts_process.class.php');
require(BOILERPLATE_CREATE_POSTS_DIR . 'classes\boilerplate_create_posts_admin_panel.class.php');

// -------------------------------------------------------------------------------------------------------- //
// ACTIONS
// -------------------------------------------------------------------------------------------------------- //
add_action('admin_menu', __NAMESPACE__ . '\AdminPanel\Createpostsadminpanel::create_page');
add_action('admin_init', __NAMESPACE__ . '\AdminPanel\Createpostsadminpanel::add_assets');