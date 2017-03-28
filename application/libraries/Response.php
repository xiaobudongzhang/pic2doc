<?php
class Response{
    public static function json($data){
        header('Content-type: application/json');
        echo json_encode($data);
    }
}