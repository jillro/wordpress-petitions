<?php

// register shortcode to display signatures list
add_shortcode('signaturelist', 'guilro_petitions_signatures_shortcode');
function guilro_petitions_signatures_shortcode($attr)
{
    include_once 'class.signaturelist.php';
    global $guilro_petitions_settings;
    $options = $guilro_petitions_settings->getAll();

    $id = 1;
    $rows = $options['signaturelist_rows'];
    $nextbuttontext = '&gt;';
    $prevbuttontext = '&lt;';
    $dateformat = 'M d, Y';

    if (isset($attr['id']) && is_numeric($attr['id'])) {
        $id = $attr['id'];
    }
    if (isset($attr['rows']) && is_numeric($attr['rows'])) {
        $rows = absint($attr['rows']);
    }
    if (isset($attr['nextbuttontext'])) {
        $nextbuttontext = $attr['nextbuttontext'];
    }
    if (isset($attr['prevbuttontext'])) {
        $prevbuttontext = $attr['prevbuttontext'];
    }
    if (isset($attr['dateformat'])) {
        $dateformat = $attr['dateformat'];
    }

    // make sure ajax callback url works on both https and http
    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $params = array(
        'ajaxurl' => admin_url('admin-ajax.php', $protocol),
        'dateformat' => $dateformat,
    );
    wp_enqueue_script('guilro_petitions_signaturelist_js', plugins_url('guilro-petitions/js/signaturelist.js'), array('jquery'));
    wp_localize_script('guilro_petitions_signaturelist_js', 'guilro_petitions_signaturelist_js', $params);

    $table_html = guilro_petitions_Signaturelist::table($id, 0, $rows, 'shortcode', $dateformat, $nextbuttontext, $prevbuttontext);

    return $table_html;
}

// load CSS on pages/posts that contain the [signaturelist] shortcode
add_filter('the_posts', 'guilro_petitions_signaturelist_css');
function guilro_petitions_signaturelist_css($posts)
{

    // ignore if there are no posts
    if (empty($posts)) {
        return $posts;
    }

    global $guilro_petitions_settings;
    $options = $guilro_petitions_settings->getAll();

    // set flag to determine if post contains shortcode
    $shortcode_found = false;
    foreach ($posts as $post) {
        // if post content contains the shortcode
        if (strstr($post->post_content, '[signaturelist')) {
            // update flag
            $shortcode_found = true;
            break;
        }
    }

    // if flag is now true, load the CSS
    if ($shortcode_found) {
        $theme = $options['signaturelist_theme'];

         // load default theme
        if ($theme === 'default') {
            wp_enqueue_style('guilro_petitions_signaturelist_css', plugins_url('guilro-petitions/css/signaturelist.css'));
        }
        // attempt to load cusom theme (petition-signaturelist.css)
        else {
            $parent_dir = get_template_directory_uri();
            $parent_theme_url = $parent_dir.'/petition-signaturelist.css';

            // if a child theme is in use
            // try to load style from child theme folder
            if (is_child_theme()) {
                $child_dir = get_stylesheet_directory_uri();
                $child_theme_url = $child_dir.'/petition-signaturelist.css';
                $child_theme_path = STYLESHEETPATH.'/petition-signaturelist.css';

                // use child theme if it exists
                if (file_exists($child_theme_path)) {
                    wp_enqueue_style('guilro_petitions_signaturelist_css', $child_theme_url);
                }
                // else try to load style from parent theme folder
                else {
                    wp_enqueue_style('guilro_petitions_signaturelist_css', $parent_theme_url);
                }
            }
            // if not using a child theme, just try to load style from active theme folder
            else {
                wp_enqueue_style('guilro_petitions_signaturelist_css', $parent_theme_url);
            }
        }
    }

    return $posts;
}
