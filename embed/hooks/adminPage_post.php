<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted ?>
<?php
    global $pageDetails, $db, $errors, $user;
    $page = currentFile();
    $description = $db->query("SELECT description FROM pages WHERE page = ?", [$page])->first()->description;
    if($_POST['changeDescription'] != $description){
      $newDescription = Input::get('changeDescription');
      if ($db->query('UPDATE pages SET description = ? WHERE id = ?', array($newDescription, $pageDetails->id))){
        $successes[] = "Successfully changed description";
        logger($user->data()->id,"Pages Manager","Changed description of '{$pageDetails->page}' to '$newDescription'.");
      }else{
        $errors[] = lang("SQL_ERROR");
      }
    }
?>