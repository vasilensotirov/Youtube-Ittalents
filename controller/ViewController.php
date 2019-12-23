<?php


namespace controller;


class ViewController{
    public function viewRouter($view) {
        include_once "view/$view.php";
    }
}