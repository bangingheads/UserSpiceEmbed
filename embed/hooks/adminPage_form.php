<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted ?>
<?php
global $db, $pageDetails;
$description = $db->query("SELECT description FROM pages WHERE id = ?", [$pageDetails->id])->first()->description;
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="form-group">
            <label for="description">Page Description:</label> <span class="small">(This is the text that's displayed on an embed of your site)</span>
            <input type="text" class="form-control" name="changeDescription" maxlength="200" value="<?= $description; ?>" />
        </div>
    </div>
</div>