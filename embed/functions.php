<?php

if (!function_exists('getPageDescription')) {
    function getPageDescription($page = null) {
        global $db, $embedDescription;
        if ($page === null) $page = currentFile();
        if (isset($embedDescription) && $embedDescription !== null) {
            $description = $embedDescription;
        } else {
            $description = $db->query("SELECT description FROM pages WHERE page = ?", [$page])->first()->description;
        }
        if ($description === "") {
            $r = $db->query("SELECT * from embed_settings")->first();
            if ($r->use_default) {
                $description = $r->default_description;
            }
        }
        return $description;
    }
}

if (!function_exists('getPageTitle')) {
    function getPageTitle() {
        global $embedTitle, $pageTitle;
        if (isset($embedTitle) && $embedTitle !== null) {
            return $embedTitle;
        }
        return $pageTitle;
    }
}