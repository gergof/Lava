<?php
require_once("config/config.php");
require_once("modules/loader.php");

use function \LightFrame\loadPart;

$view=isset($_GET["view"])?$_GET["view"]:"";
$sub=isset($_GET["sub"])?$_GET["sub"]:"";

if($lm->validateLogin()){
    //logged in
    if(isset($_GET["logout"])){
        $lm->logout();
    }
}
else{
    $lm->loginPrepare();
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $lm->login($_POST["username"], $_POST["password"], isset($_POST["remember"]));
    }
    if(isset($_GET["autologin"])){
        $lm->login("", "");
    }
    if(isset($_GET["forgetuser"])){
        $lm->forgetUser();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo (isset($extend_title)?$extend_title." :: ":"").($sub!=""?$lang[$sub]." :: ":"").($view!=""?$lang[$view]." :: ":"").$lang["site_title"] ?></title>
        <meta charset="UTF-8"/>
        <!-- link icon -->
        <link rel="icon" href="./res/icon.png"/>
        <!-- import main script -->
        <script src="./script/bundle.js"></script>
        <!-- cookie consent -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css"/>
        <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
        <script>
            window.addEventListener("load", function(){
                window.cookieconsent.initialise({
                    "palette": {
                        "popup": {
                            "background": "#000000"
                        },
                        "button": {
                            "background": "#F1D600"
                        }
                    },
                    "content": {
                        "message": "<?php echo $lang["cookie_message"] ?>",
                        "dismiss": "<?php echo $lang["cookie_dismiss"] ?>"
                    }
                });
            });
        </script>
        <!-- reCaptcha -->
        <script src="https://www.google.com/recaptcha/api.js"></script>
    </head>
    <body>
        <div id="messageOverlay" class="message__overlay"></div>
        <div id="header" class="header">
            <img class="header__logo" alt="logo" src="./res/logo.png"/>
            <p class="header__title"><?php echo $lang["site_title"] ?></p>
            <div class="header__languageSelector">
                <span><?php echo $lang['language'].": " ?></span>
                <select id="languageSelector" class="header__languageSelector__select" onchange="ui.main.changeLanguage()">
                    <?php
                    foreach($config["language"]["available"] as $l){
                        echo "<option value=\"".$l."\">".$lang[$l]."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="header__break"></div>
        <div id="content">
            <div id="menu" class="menu">
                <div class="menu__item" onclick="ui.main.goTo('')">
                    <span><?php echo $lang["index"] ?></span>
                </div>
                <div class="menu__item" onclick="ui.main.goTo('about')">
                    <span><?php echo $lang["about"] ?></span>
                </div>
                <?php if($lm->validateLogin()): ?>
                    <?php if(hasGroup("admin") || hasGroup("manager")): ?>
                        <div class="menu__item" onclick="ui.main.goTo('users')">
                            <span><?php echo $lang["users"] ?></span>
                        </div>
                        <div class="menu__item" onclick="ui.main.goTo('groups')">
                            <span><?php echo $lang["groups"] ?></span>
                        </div>
                        <div class="menu__item" onclick="ui.main.goTo('news')">
                            <span><?php echo $lang["news"] ?></span>
                        </div>
                        <div class="menu__item" onclick="ui.main.goTo('polls')">
                            <span><?php echo $lang["polls"] ?></span>
                        </div>
                    <?php endif; if(hasGroup("admin")): ?>
                        <div class="menu__item" onclick="ui.main.goTo('adminarea')">
                            <span><?php echo $lang["adminarea"] ?></span>
                        </div>
                    <?php endif; if(hasGroup("headteacher")): ?>
                        <div class="menu__item" onclick="ui.main.goTo('myclass')">
                            <span><?php echo $lang["myclass"] ?></span>
                        </div>
                    <?php endif ?>
                    <div class="menu__item" onclick="ui.main.goTo('profile')">
                        <span><?php echo $lang["profile"] ?></span>
                    </div>
                    <div class="menu__item" onclick="window.location='./?logout'">
                        <span><?php echo $lang["logout"] ?></span>
                    </div>
                <?php else: ?>
                    <div class="menu__item" onclick="ui.main.goTo('login')">
                        <span><?php echo $lang["login"] ?></span>
                    </div>
                <?php endif ?>
            </div>
            <div id="module" class="module">
                <?php loadPart($view, $sub) ?>
            </div>
        </div>
        <div id="footer" class="footer">
            <p>&copy; Copyright <?php echo $config["general"]["name"]." ".date("Y") ?></p>
            <p>Powered by: <a href="https://github.com/gergof/LightFrame">Lightframe</a></p>
            <p>Created by: Fándly Gergő-Zoltán (<a href="mailto:contact@systemtest.tk">contact@systemtest.tk</a>, <a href="https://systemtest.tk">Systemtest.tk</a>, <a href="https://github.com/gergof">GitHub</a>)</p>
            <p>Licensed under <a href="https://www.gnu.org/licenses/gpl-3.0.html">GPLv3</a> | <a href="https://github.com/gergof/Lava">GitHub repo</a></p>
        </div>
    </body>
</html>