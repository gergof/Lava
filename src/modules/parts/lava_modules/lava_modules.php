<?php

class lava_modules{
    private $mods=array();

    public function register($typename, $class){
        $this->mods[$typename]=$class;
    }

    public function getModule($typename){
        return $this->mods[$typename];
    }

    public function getModules(){
        return $this->mods;
    }
};

$modules=new lava_modules();


///
///Register modules down here:
///

require_once("modules/news.php");
$modules->register("news", new News());

require_once("modules/poll.php");
$modules->register("poll", new Poll());