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

  public function __construct() {
    if (is_admin()) {
        add_action('admin_menu', array($this, 'setupAdminMenu'));
    } else {
        add_action('wp_enqueue_scripts', array($this, 'addStylesheet'));
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
        $settingsUpdated = true;
    }

    include('template-settings.php');
  }
}

$aboutMe = new AboutMe();
}