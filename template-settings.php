<div class="wrap kantoniak-about-me-settings-page">
  <h1><?php echo kantoniak\AboutMe::PLUGIN_NAME; ?></h1>

<?php
  if ($settingsUpdated) {
    echo '<div class="updated"><p>Settings updated.</p></div>';
  }
?>

  <form method="POST">
    <div>
      <h2>Social Media</h2>
      <table class="form-table">
      <!-- <tr>
          <th scope="row"><label for="social_urls-facebook">Facebook</label></th>
          <td>
            <input name="social_urls-facebook" type="text" />
          </td>
      </tr> -->
      </table>
    </div>
    
    <p class="submit">
      <input type="submit" value="Save settings" name="submitted" class="button button-primary button-large">
    </p>
  </form>

</div>