<?php
namespace controller;
function uploadFile ($file, $username){
    if (is_uploaded_file($_FILES[$file]["tmp_name"])) {
        $file_name_parts = explode(".", $_FILES[$file]["name"]);
        $extension = $file_name_parts[count($file_name_parts) - 1];
        $filename = $username . "-" . time() . "." . $extension;
        $file_url = "uploads" . DIRECTORY_SEPARATOR . $filename;
        if (move_uploaded_file($_FILES[$file]["tmp_name"], $file_url)){
            return $file_url;
        }
    }
    return false;
}