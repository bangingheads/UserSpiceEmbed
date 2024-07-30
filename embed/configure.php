<?php if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);

if(!empty($_POST['plugin_embed'])){
  $embedSettings = $db->query("SELECT * FROM embed_settings")->first();
  $token = $_POST['csrf'];
  if(!Token::check($token)){
    include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
  }
  $useDefault = isset($_POST["use_default"]);
  if ($embedSettings->use_default != $useDefault) {
    $db->update("embed_settings", 1, ["use_default"=>(int)$useDefault]);
  }
  if ($embedSettings->default_description != $_POST["default_description"]) {
    $description = Input::get("default_description");
    $db->update("embed_settings", 1, ["default_description"=>$description]);
  }
 }
 $token = Token::generate();
 $embedSettings = $db->query("SELECT * FROM embed_settings")->first();
 ?>
<div class="content mt-3">
 		<div class="row">
 			<div class="col-sm-12">
       <div class="col-6">
          <a href="<?=$us_url_root?>users/admin.php?view=plugins">Return to the Plugin Manager</a>
 					<h1>Configure the Embed Plugin</h1>
          <p>
            <?php 
            if ($embedSettings->copy_file) {
              if (strpos(file_get_contents($abs_us_root.$us_url_root.'usersc/includes/head_tags.php'), "getPageDescription()") === false) {
                echo "There was an error editing your head tags file due to file permissions or it containing php content. Please manually edit it to make title and description tags match usersc/plugins/embed/files/head_tags.php<br><br>";
              } else {
                $db->update("embed_settings", 1, ["copy_file"=>0]);
              }
            }
            ?>
          </p>
          <form action="<?=$_SERVER['PHP_SELF']?>?view=plugins_config&plugin=embed" method="POST">
          <input type="hidden" name="csrf" value="<?=$token?>">
          <div class="form-group">
            <label for="use_default">Use default description on pages that are not set</label>
            <span style="float:right;">
                <label class="switch switch-text switch-success">
                    <input id="use_default" name="use_default" type="checkbox" class="switch-input toggle"
                        <?php if( $embedSettings->use_default==1) echo 'checked="true"'; ?>>
                    <span data-on="Yes" data-off="No" class="switch-label"></span>
                    <span class="switch-handle"></span>
                </label>
            </span>
          </div>
          <div class="form-group">
            <label for="default_description">The description to be shown on pages that do not have a description:</label><br><br>
              <textarea name="default_description" cols="40" rows="5" maxlength="200"><?=$embedSettings->default_description?></textarea>
          </div>
          <input type="submit" class="btn btn-success" name="plugin_embed" value="Update">
          </form><br><br>
          <p>Make sure to add descriptions to pages in the Page Manager!</p>
          <p>If you would like an image in your embed, edit usersc/includes/head_tags.php to include your image url in the "og:image" tag.</p>
          <p>If you would like to override your page title and/or description, maybe depending on a parameter, set the $embedTitle and $embedDescription variables before your template is loaded.</p>
          </div>

 			</div> <!-- /.col -->
 		</div> <!-- /.row -->
