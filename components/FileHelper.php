<?php
/**
 * Created by PhpStorm.
 * User: ryosuke
 * Date: 15/08/27
 * Time: 1:43
 */

namespace app\components;


class FileHelper {

    public static function writeJson($filename, $json, $override = false){

        $current = file_exists($filename) ? file_get_contents($filename) : "[]";
        $current = json_decode($current);
        $current[] = $json;

        return file_put_contents($filename, json_encode($current));
    }

    public static function writeText($filename, $data, $override = false){

        if(is_array($data)){
            $data = implode("\n", $data);
        }

        if($override){
            if(file_exists($filename))unlink($filename);
            return file_put_contents($filename, $data);
        }

        $current = file_exists($filename) ? file_get_contents($filename)."\n".$data : $data;
        return file_put_contents($filename, $current);
    }

    public static function getTextToArray($filename){
        $temp = file_exists($filename) ? file_get_contents($filename) : null;
        if(!isset($temp)) return [];
        else if(empty($temp)) {
            static::removeFile($filename);
            return [];
        }
        return explode("\n", $temp);
    }

    public static function getText($filename){
        return file_exists($filename) ? file_get_contents($filename) : "";
    }

    public static function removeFile($filename){
        if(file_exists($filename))unlink($filename);
    }
}