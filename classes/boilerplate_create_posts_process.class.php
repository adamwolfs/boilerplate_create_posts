<?php
// -------------------------------------------------------------------------------------------------------- //
// CLASS NAMESPACE
// -------------------------------------------------------------------------------------------------------- // 
namespace EmbraceGeekComAuBoilerplateCreatePosts\Process;

/** --------------------------------------------------------------------------------------------------------
  Createpostsprocess class
  @method add_assets() - Injects assets for html
  @method process_posts($posts) - Process through the entered text area, and create posts nested accordingly
  @method create_post() - Creates the post based on set details
  @method cleanup_field() - Cleans field to make sure no bad data.
  @methpod clean_array() - Loops through textarea array
  #method startsWith() - Support function to see what a string starts with
-------------------------------------------------------------------------------------------------------- */
class Createpostsprocess
{
  public static $localnamespace = 'EGBoilerplate';
  private static $working_array = array();
  private static $post_type = 'post';
  private static $add_lorem_ipsum = true;
  private static $lorem_ipsum_length = 3;
  private static $lorem_ipsum = '';

  // -------------------------------------------------------------------------------------------------------- // 
  // Process through the entered text area, and create posts nested accordingly
  // -------------------------------------------------------------------------------------------------------- // 
  public static function process_posts($post_data)
  {
    // Split post structure into array.
    self::$working_array = preg_split('/\r\n|[\r\n]/', $post_data['EGBoilerplatePlainContent']);

    // Clean through fields, remove empty ones.
    self::clean_array();

    // Set post type
    self::$post_type =
      (isset($post_data[self::$localnamespace . 'PostType']) && $post_data[self::$localnamespace . 'PostType'] != "")
      ? $post_data['EGBoilerplatePostType']
      : 'post';

    // Set lorem impsum status
    self::$add_lorem_ipsum =
      (isset($post_data[self::$localnamespace . 'AddLoremIpsum']) && $post_data[self::$localnamespace . 'AddLoremIpsum'] == "1")
      ? true
      : false;

    // Set length of lorem ipsum
    self::$lorem_ipsum_length = (isset($post_data[self::$localnamespace . 'LoremIpsumCount']) ? $post_data[self::$localnamespace . 'LoremIpsumCount'] : self::$lorem_ipsum_length);

    // Get lorem ipsum checked, generated from Lipsum Generator Class
    self::$lorem_ipsum = (self::$add_lorem_ipsum) ? \MasterOdin\Gists\LipsumGenerator_extended_class::getParagraphs(self::$lorem_ipsum_length, true) : '';

    // process array and insert into database.
    $current_post_id = 0;
    $current_parent_id = 0;

    foreach (self::$working_array as $key => $value) {

      if (self::startsWith($value, "--")) {
        $current_parent_id = $current_post_id;
        $current_post_id = self::create_post(ltrim($value, '-'), $current_parent_id);

      } else if (self::startsWith($value, "-")) {
        $current_post_id = self::create_post(ltrim($value, '-'), $current_parent_id);

      } else {
        $current_post_id = self::create_post($value, 0);
        $current_parent_id = $current_post_id;
      }

    }

    return '<p id="' . self::$localnamespace . 'Result">
      Posts have been created
    </p>';
  }

  // -------------------------------------------------------------------------------------------------------- // 
  // Creates the post based on set details
  // -------------------------------------------------------------------------------------------------------- // 
  private static function create_post($title, $parent = 0)
  {
    return wp_insert_post(array(
      'post_title' => $title,
      'post_type' => self::$post_type,
      'post_status' => 'publish',
      'post_content' => self::$lorem_ipsum,
      'post_parent' => $parent,
    ));
  }

  // -------------------------------------------------------------------------------------------------------- // 
  // cleans field to make sure no bad data.
  // -------------------------------------------------------------------------------------------------------- // 
  private static function cleanup_field($field)
  {
    $field = trim($field);
    return sanitize_text_field($field);
  }

  // -------------------------------------------------------------------------------------------------------- // 
  // Loops through textarea array
  // -------------------------------------------------------------------------------------------------------- // 
  private static function clean_array()
  {
    // load temporary array
    $temp_array = array();

    // filter array elements, remove empty ones
    foreach (self::$working_array as $key => $el) {
      if (empty($el)) {
        unset(self::$working_array[$key]);
      } else {
        $temp_array[] = self::cleanup_field($el);
      }
    }

    // reset class param
    self::$working_array = $temp_array;
  }

  // -------------------------------------------------------------------------------------------------------- // 
  // Support function to see what a string starts with
  // -------------------------------------------------------------------------------------------------------- // 
  private static function startsWith($string, $startString)
  {
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
  }
}