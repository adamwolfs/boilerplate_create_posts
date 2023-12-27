<?php
// -------------------------------------------------------------------------------------------------------- //
// CLASS NAMESPACE
// -------------------------------------------------------------------------------------------------------- // 
namespace EmbraceGeekComAuBoilerplateCreatePosts\AdminPanel;

/** --------------------------------------------------------------------------------------------------------
  Createpostsadminpanel class
  @variable $localnamespace - Used to add a unique identifier to the display elements.
  @method add_assets() - Injects assets for html
  @method create_page() - Creates menu item for html insertion.
  @method create_page_ui() - Outputs html for the tool page.
  @method return_post_types_list_html() - Returns html for the select element of fitered post types
  @method return_get_post_type_list() - Generates filtered post type list.
  @method return_is_page_processing() - Checks if the page has $POST data to begin processing
  @method get_current_admin_url() Get the page URL of the current admin page
-------------------------------------------------------------------------------------------------------- */
class Createpostsadminpanel
{
    public static $localnamespace = 'EGBoilerplate';

    // -------------------------------------------------------------------------------------------------------- //
    // Injects assets for html
    // -------------------------------------------------------------------------------------------------------- //
    public static function add_assets()
    {
        wp_register_style(self::$localnamespace . 'css_file', BOILERPLATE_CREATE_POSTS_URL . 'assets/css/boilerplate.css');
        wp_enqueue_style(self::$localnamespace . 'css_file');

        wp_register_style(self::$localnamespace . 'bootstrap_css', BOILERPLATE_CREATE_POSTS_URL . 'assets/css/bootstrap/styles/styles.css');
        // wp_enqueue_style(self::$localnamespace . 'bootstrap_css');

        wp_register_script(self::$localnamespace . 'js_file', BOILERPLATE_CREATE_POSTS_URL . 'assets/js/boilerplate.js');
        wp_enqueue_script(self::$localnamespace . 'js_file');
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Creates menu item for html insertion.
    // -------------------------------------------------------------------------------------------------------- //
    public static function create_page()
    {
        add_management_page(
            'Boilerplate Create Posts', // Page Title
            'Boilerplate Create Posts', // Menu name
            'manage_options', // Permissions
            'boilerplate-create-posts', // Slug
            array(static::class, 'create_page_ui') // Function for display
        );
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Outputs html for the tool page.
    // -------------------------------------------------------------------------------------------------------- //
    public static function create_page_ui()
    {
        ?>
        <div class="newwrap"
            style="background: var(--var-content-field-dark-color); color: #fff; padding: 40px; margin-left: -20px;">
            <h1 style="color: #fff;">
                <?php echo BOILERPLATE_CREATE_POSTS_TITLE; ?>
            </h1>
            <p>
                <?php echo BOILERPLATE_CREATE_POSTS_DESCRIPTION; ?>
            </p>
        </div>
        <div style="padding: 20px;">

            <?php
            // check if the page is processing
            if (self::return_is_page_processing($_POST))
                echo (\EmbraceGeekComAuBoilerplateCreatePosts\Process\Createpostsprocess::process_posts($_POST));

            ?>

            <form method="post" action="<?php echo self::get_current_admin_url(); ?>"
                id="<?php echo self::$localnamespace; ?>Wrap">

                <?php if (!self::_isCurl()) { ?>
                    <div id="<?php echo self::$localnamespace; ?>Error">
                        Please note: You must have curl enabled in order to use this plugin.
                    </div>
                <?php } ?>

                <div id="<?php echo self::$localnamespace; ?>Content">
                    <textarea id="<?php echo self::$localnamespace; ?>PlainContent"
                        name="<?php echo self::$localnamespace; ?>PlainContent" required="required"
                        oninvalid="this.setCustomValidity('You must have itemed entered to create.')"
                        onchange="this.setCustomValidity('')" placeholder="Enter your nested post structure"></textarea>

                    <div id="<?php echo self::$localnamespace; ?>fieldsets">
                        <div>
                            <label>
                                <input type="checkbox" name="<?php echo self::$localnamespace; ?>AddLoremIpsum"
                                    id="<?php echo self::$localnamespace; ?>AddLoremIpsum" value="1" checked="checked">
                                Add <b>Placeholder text</b> to the created <span class="change_post_type">posts</span>
                            </label>
                        </div>
                        <div>
                            <label>
                                <select name="<?php echo self::$localnamespace; ?>LoremIpsumCount"
                                    id="<?php echo self::$localnamespace; ?>LoremIpsumCount">
                                    <?php
                                    for ($i = 1; $i <= BOILERPLATE_CREATE_POSTS_LOREM_COUNT; $i++) {
                                        if ($i == BOILERPLATE_CREATE_POSTS_LOREM_COUNT_DEFAULT) {
                                            echo "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
                                        } else {
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                Number of <b>Placeholder</b> paragraphs
                            </label>
                        </div>
                        <div>
                            <label>
                                <select name="<?php echo self::$localnamespace; ?>PostType"
                                    id="<?php echo self::$localnamespace; ?>PostType">
                                    <?php echo self::return_post_types_list_html(); ?>
                                </select>
                                Which post type for creation?
                            </label>
                        </div>
                        <div>
                            <?php if (self::_isCurl()): ?>
                                <label>
                                    <input type="submit" class="button button-primary"
                                        id="<?php echo self::$localnamespace; ?>Button"
                                        name="<?php echo self::$localnamespace; ?>Button" value="Create Posts">
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p><b><i>Note: You can nest up to 2 levels. Please sort in correct level order. Posts cannot have parent
                                posts.
                                Only
                                pages.</i></b></p>
                </div>
            </form>

        </div>
        <?php
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Checks if the page has $POST data to begin processing
    // -------------------------------------------------------------------------------------------------------- //
    private static function return_is_page_processing($post)
    {
        return (isset($post['EGBoilerplateButton']) ? true : false);
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Generates filtered post type list.
    // -------------------------------------------------------------------------------------------------------- //
    private static function return_get_post_type_list()
    {
        // built in post types to exclude
        $excludes = array('attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'user_request',
            'wp_block',
            'wp_template',
            'wp_template_part',
            'wp_global_styles',
            'wp_navigation'
        );

        $post_types = get_post_types(array(), 'objects', 'and');

        foreach ($post_types as $key => $post_type) {
            if (in_array($key, $excludes)) {
                unset($post_types[$key]);
            }
        }

        return $post_types;
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Returns html for the select element of fitered post types
    // -------------------------------------------------------------------------------------------------------- //
    private static function return_post_types_list_html()
    {
        $post_types = self::return_get_post_type_list();
        $html = '';

        foreach ($post_types as $key => $post_type) {
            $html .= '<option value="' . $key . '">' . $post_type->labels->name . '</option>';
        }

        return $html;
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Get current page admin URL
    // -------------------------------------------------------------------------------------------------------- //
    public static function get_current_admin_url()
    {
        return admin_url(sprintf(basename($_SERVER['REQUEST_URI'])));
    }

    // -------------------------------------------------------------------------------------------------------- //
    // Is curl activated
    // -------------------------------------------------------------------------------------------------------- //
    private static function _isCurl()
    {
        return function_exists('curl_version');
    }

}