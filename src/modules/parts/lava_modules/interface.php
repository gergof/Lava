<?php
interface LavaModule{
    public function getEntitiesForIndex($db, $targeted, $targeted_arr);
    public function printOnIndex($entity);
}