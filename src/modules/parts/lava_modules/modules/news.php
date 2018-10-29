<?php
require_once("../interface.php");

class News implements LavaModule{
    public function getEntitiesForIndex($db, $targeted, $targeted_arr){
        $sql=$db->prepare("SELECT DISTINCT 'news' AS type, nf.news AS id, n.title, n.content, n.publish, u.fullname AS user FROM news_for AS nf INNER JOIN news AS n ON (n.id=nf.news) INNER JOIN users AS u ON (u.id=n.user) WHERE ".$targeted." ORDER BY publish DESC LIMIT 10");
        $sql->execute($targeted_arr);
        $news=$sql->fetchAll(PRO::FETCH_ASSOC);

        return $news;
    }
    public function printOnIndex($entity){
        global $lang;
        echo "
            <div>
                <h3>".$entity["title"]."</h3>
                <p style=\"font-size: 0.8em\">".$entity["publish"]."</p>
                <p style=\"font-size: 0.8em\">".$lang["by"].": ".$entity["user"]."</p>
                <hr/>
                <p>".str_replace($entity["content"], "\n", "<br/>")."</p>
            </div>
        ";
    }
}