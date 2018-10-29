<?php
require_once("../interface.php");

class Poll implements LavaModule{
    public function getEntitiesForIndex($db, $targeted, $targeted_arr){
        $sql=$db->prepare("SELECT DISTINCT 'poll' AS type, pf.poll as id, p.id, p.title, p.message, p.from, p.until, p.results_type, p.allow_change, p.publish FROM polls_for AS pf INNER JOIN polls AS p ON (p.id=pf.poll) WHERE ".$targeted." ORDER BY publish DESC LIMIT 10");
        $sql->execute($targeted_arr);
        $polls=$sql->fetchAll(PDO::FETCH_ASSOC);

        return $polls;
    }
    public function printOnIndex($entity){
        echo "POLL";
    }
}