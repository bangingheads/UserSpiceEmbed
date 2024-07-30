<?php
require_once("init.php");
//For security purposes, it is MANDATORY that this page be wrapped in the following
//if statement. This prevents remote execution of this code.
if (in_array($user->data()->id, $master_account)){


$db = DB::getInstance();
include "plugin_info.php";



//all actions should be performed here.
$check = $db->query("SELECT * FROM us_plugins WHERE plugin = ?",array($plugin_name))->count();
if($check > 0){
	err($plugin_name.' has already been installed!');
}else{
 $fields = array(
	 'plugin'=>$plugin_name,
	 'status'=>'installed',
 );
 $db->insert('us_plugins',$fields);
 if(!$db->error()) {
	 	err($plugin_name.' installed');
		logger($user->data()->id,"USPlugins",$plugin_name." installed");
 } else {
	 	err($plugin_name.' was not installed');
		logger($user->data()->id,"USPlugins","Failed to to install plugin, Error: ".$db->errorString());
 }

 $db->query("ALTER TABLE `pages` ADD `description` VARCHAR(255) NOT NULL AFTER `title`;");

 $db->query("
 CREATE TABLE `embed_settings` (
	 `id` int(11) NOT NULL AUTO_INCREMENT,
	 `use_default` int(1) NOT NULL DEFAULT 1,
	 `default_description` varchar(255) NOT NULL,
	 `copy_file` int(1) NOT NULL DEFAULT 0,
	 PRIMARY KEY (id)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 ");
 
 if ($db->query("SELECT * FROM embed_settings")->count() == 0) {
	 $db->insert("embed_settings", ["use_default"=>1, "default_description"=>"UserSpice is a PHP User Management Framework. It is designed from the ground up to be the perfect starting point for any web development project that requires users to login. It handles the...", "copy_file"=>0]);
 }
 
 copy($abs_us_root . $us_url_root . 'usersc/includes/head_tags.php', $abs_us_root . $us_url_root . 'usersc/includes/head_tags_backup.php');
 
 $file = new SplFileObject($abs_us_root . $us_url_root . 'usersc/includes/head_tags.php');
 $hasSiteName = false;
 $hasPhp = false;
 $lines = [];
 while (!$file->eof()) {
	 $line = $file->fgets();
	 if (strpos($line, '<?php') !== false) {
		$hasPhp = true;
	}
	 if (strpos($line, '<meta name="description"') !== false) {
		 $line = '<meta name="description" content="<?php echo getPageDescription(); ?>">';
	 }
	 else if (strpos($line, 'og:title') !== false) {
		 $line = '<meta property="og:title" content="<?php echo getPageTitle(); ?>" />';
	 }
	 else if (strpos($line, 'og:description') !== false) {
		 $line = '<meta property="og:description" content="<?php echo getPageDescription(); ?>" />';
	 }
	 else if (strpos($line, 'og:site_name') !== false) {
		 $line = '<meta property="og:site_name" content="<?php echo $settings->site_name; ?>" />';
		 $hasSiteName = true;
	 }
	 if ($line != "\n") {
		 $line = str_replace("\n", "", $line);
	 }
	 
	 array_push($lines, $line);
 }
 $file = null;
 
 if ($hasPhp) {
	 // I don't want to update their head tags if they have php tags it will get complicated quickly
	 // Update your tags manually
	 $db->query("UPDATE embed_settings SET copy_file = 8");
 }
 else {
	 if (file_exists($abs_us_root . $us_url_root . 'usersc/includes/head_tags_backup.php')) {
		 unlink($abs_us_root . $us_url_root . 'usersc/includes/head_tags.php');
		 if ($fp = fopen($abs_us_root . $us_url_root . 'usersc/includes/head_tags.php', 'w')) {
			 fwrite($fp, "<?php" . "\n");
			 fwrite($fp, "/*" . "\n");
			 fwrite($fp, "This file has been edited by the embed plugin." . "\n");
			 fwrite($fp, "You can find your old head_tags.php as head_tags_backup.php." . "\n");
			 fwrite($fp, "\n");
			 fwrite($fp, "If you deactivate the plugin it will be reverted to the backup." . "\n");
			 fwrite($fp, "*/" . "\n");
			 fwrite($fp, "?>" . "\n");
			 foreach($lines as $line) {
				 fwrite($fp, $line . "\n");
			 }
			 if (!$hasSiteName) {
				 fwrite($fp, '<meta property="og:site_name" content="<?=$settings->site_name?>" />');
			 }
			 fclose($fp);
		 } else {
			 $db->query("UPDATE embed_settings SET copy_file = 1");
		 }
	 }
 }

}

//do you want to inject your plugin in the middle of core UserSpice pages?
$hooks = [];

//The format is $hooks['userspicepage.php']['position'] = path to filename to include
//Note you can include the same filename on multiple pages if that makes sense;
//postion options are post,body,form,bottom
//See documentation for more information
$hooks['adminPage']['form'] = 'hooks/adminPage_form.php';
$hooks['adminPage']['post'] = 'hooks/adminPage_post.php';
registerHooks($hooks,$plugin_name);

} //do not perform actions outside of this statement
