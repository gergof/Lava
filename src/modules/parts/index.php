<?php
require_once("lava_modules/lava_modules.php");

//get the entities targeted for the current group
$targeted="(";
$targeted_arr=array();
if(isset($_SESSION["groups"])){
    for($i=0; $i<count($_SESSION["groups"]); $i++){
        if($i!=0){
            $targeted.=" or ";
        }
        $targeted.="group=?";
        array_push(targeted_arr, $_SESSION["groups"][$i]["group"]);
    }
}
else{
    $targeted="group='guest'"
}
$targeted.=")";

//get entities from modules
$entities=array();
foreach($modules->getModules() as $m){
    array_merge($entities, $m->getEntitiesForIndex($db, $targeted, $targeted_arr));
}

//sort entities by publish time
$publish=array();
foreach($entities as $key => $e){
    $publish[$key]=$e["publish"];
}
array_multisort($publish, SORT_DESC, $entities);
?>

<div id="index_entities">
    <?php
    foreach($entities as $e){
        echo "<div class=\"entity\">"
        $mdoules->getModule($e["type"])->printOnIndex($e);
        echo "</div>";
    }
    ?>
</div>