<?php
if(!hasGroup("admin") && !hasGroup("manager")){
    \LightFrame\Utils\setError(403);
    die();
}

if(isset($_POST["new_username"]) && isset($_POST["new_fullname"]) && isset($_POST["new_password"]) && isset($_POST["new_primarygroup"])){
    $sql=$db->prepare("SELECT COUNT(id) FROM users WHERE username=:uname");
    $sql=$db->execute(array(":uname"=>$_POST["new_username"]));
    $res=$sql->fetch(PDO::FETCH_ASSOC);
    
    if($res["count"]>0){
        \LightFrame\Utils\setError(203);
        die();
    }

    $sql=$db->prepare("INSERT INTO users(username, fullname, password) VALUES (:uname, :fname, :passwd)");
    $sql->execute(array(":uname" => $_POST["new_username"], ":fname"=>$_POST["new_fullname"], ":passwd"=>PasswordStorage::create_hash($_POST["new_password"])));
    $res=$sql->rowCount();
    $uid=$db->lastInsertId();

    if($res!=1){
        \LightFrame\Utils\setError(204);
        die();
    }

    $query="(?, ?, ?)";
    $query_arr=array($uid, $_POST["new_primarygroup"], true);
    if(isset($_POST["new_groups"])){
        foreach($_POST["new_groups"] as $g){
            $query.=", (?, ?, ?)";
            array_push($query_arr, $uid, $g, false);
        }
    }
    $sql=$db->prepare("INSERT INTO group_members(user, group, primary) VALUES ".$query);
    $sql->execute($query_arr);

    echo "ok";
    die();
}

$groupSelect="";
$sql=$db->prepare("SELECT id, displayname FROM groups");
$sql->execute();
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
    $groupSelect.="<option value=\"".$row["id"]."\">".($row["displayname"]!=""?$row["displayname"]:$row["id"])."</option>";
}
?>

<div id="user_add" class="form">
    <div class="form__legend"><?php echo $lang["add_user"] ?></div>
    <form onsubmit="ui.users.add(e)" id="user_add_form">
        <div class="fields">
            <span><?php echo $lang["username"].":" ?></span>
            <input type="text" class="input" name="new_username" required/>
            <span><?php echo $lang["fullname"].":" ?></span>
            <input type="text" class="input" name="new_fullname" required/>
            <span><?php echo $lang["password"].":" ?></span>
            <input type="text" class="input" name="new_password" required/>
            <span><?php echo $lang["primary_group"].":" ?></span>
            <select name="new_primarygroup" onchange="ui.users.newDisablePrimary()" required>
                <?php 
                echo $groupSelect;
                ?>
            </select>
            <span><?php echo $lang["additional_groups"].":" ?></span>
            <select name="new_groups" multiple>
                <?php
                echo $groupSelect;
                ?>
            </select>
        </div>
        <br/>
        <button type="submit" class="button button__center"><?php echo $lang["ok"] ?></button>
    </form>
</div>
<hr/>
<table class="table" onload="ui.users.loadTable()">
    <thead>
        <tr class="table__header">
            <th><?php echo $lang["id"] ?></th>
            <th><?php echo $lang["username"] ?></th>
            <th><?php echo $lang["primary_group"] ?></th>
            <th><?php echo $lang["additional_groups"] ?></th>
            <th><?php echo $lang["operations"] ?></th>
        </tr>
    </thead>
    <tbody>
        <!-- rest loads the rest. :P -->
    </tbody>
</table>