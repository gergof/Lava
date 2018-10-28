<?php

//check if currently logged in user is member of group
function hasGroup($group){
    if(!isset($_SESSION["groups"])){
        return false;
    }
    
    foreach($_SESSION["groups"] as $g){
        if($g["group"]==$group){
            return true;
        }
    }
    return false;
}