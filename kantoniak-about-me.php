<?php
/*
Plugin Name: kantoniak About Me
Plugin URI: 
Description: This plugin adds personal info like social media URLs.
Version: 0.0.1
Author: Krzysztof Antoniak
Author URI: http://antoniak.in/
License: GNU General Public License, version 3.0 (GPL-3.0)
 */

namespace kantoniak {

class AboutMe {

  const PLUGIN_NAME = 'About Me';
  const PLUGIN_SLUG = 'kantoniak-about-me';

  const OPTION_SOCIAL_URLS = AboutMe::PLUGIN_SLUG .'-social_urls';

  private $socialMediaSites = [];

  public function __construct() {

    $this->registerSocialMediaSite('facebook', 'Facebook', 'https://www.facebook.com/');
    $this->registerSocialMediaSite('twitter', 'Twitter', 'https://twitter.com/');
    $this->registerSocialMediaSite('github', 'GitHub', 'https://github.com/');
    $this->registerSocialMediaSite('youtube', 'YouTube', 'https://www.youtube.com/');
    $this->registerSocialMediaSite('linkedin', 'LinkedIn', 'https://www.linkedin.com/in/');
    $this->registerSocialMediaSite('rss', 'RSS', '');

    if (is_admin()) {
        add_action('admin_menu', array($this, 'setupAdminMenu'));
    } else {
        add_action('wp_enqueue_scripts', array($this, 'addStylesheet'));
    }

    add_action('widgets_init', function() {
      register_widget('\kantoniak\AboutMeWidget');
      
      // To pass arguments to widget, see https://wordpress.org/ideas/topic/allow-ability-to-pass-parameters-when-registering-widgets
      global $wp_widget_factory;
      $widget = $wp_widget_factory->widgets['\kantoniak\AboutMeWidget'];
      $widget->setPlugin($this);
    });
  }

  public function registerSocialMediaSite($slug, $name, $urlPrefix) {
    $this->socialMediaSites[$slug] = array(
      'slug' => $slug,
      'name' => $name,
      'url_prefix' => $urlPrefix
    );
  }

  public function getMediaSiteBySlug($slug) {
    if (isset($this->socialMediaSites[$slug])) {
      return $this->socialMediaSites[$slug];
    } else {
      return null;
    }
  }

  public function setupAdminMenu() {
    add_options_page(AboutMe::PLUGIN_NAME, AboutMe::PLUGIN_NAME, 'edit_plugins', AboutMe::PLUGIN_SLUG, array($this, 'handleSettingsPage'));
  }

  public function addStylesheet() {
    wp_enqueue_style(AboutMe::PLUGIN_NAME, plugins_url(AboutMe::PLUGIN_SLUG .'/css/style.css')); 
  }

  public function handleSettingsPage() {

    if (isset($_POST['submitted'])) {
        $socialUrls = $this->getSocialUrlsFromPost();
        update_option(AboutMe::OPTION_SOCIAL_URLS, json_encode($socialUrls, JSON_UNESCAPED_UNICODE));        
        $settingsUpdated = true;
    } else {
      $socialUrls = $this->getSocialUrlsFromDatabase();
    }

    include('template-settings.php');
  }

  private function getSocialUrlsFromPost() {
    $socialUrls = [];
    foreach ($this->socialMediaSites as $site) {
      $fieldValue = $_POST['social_urls-'. $site['slug']];
      if (!empty(trim($fieldValue))) {
        $socialUrls[$site['slug']] = $fieldValue;
      }
    }
    return $socialUrls;
  }

  public function getSocialUrlsFromDatabase() {
    return json_decode(get_option(AboutMe::OPTION_SOCIAL_URLS, []), true);
  }
}

class AboutMeWidget extends \WP_Widget {

    private $plugin;

    function __construct() {
        parent::__construct(
            AboutMe::PLUGIN_SLUG .'-social',  // Base ID
            AboutMe::PLUGIN_NAME .' Social'   // Name
        );
 
        $this->args['before_widget'] = '<div class="widget-wrap '. $this->base_id .'">'; 
    }

    public function setPlugin($plugin) {
      $this->plugin = $plugin;
    }
 
    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
 
    public function widget($args, $instance) {
        include('template-widget.php'); 
    }
 
    public function form($instance) {
 
        $title = ! empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
 
    }
 
    public function update($new_instance, $old_instance) {
 
        $instance = array();
 
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
 
        return $instance;
    }
}
 

$aboutMe = new AboutMe();
$aboutMeWidget = new AboutMeWidget();

}