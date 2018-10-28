import $ from "jquery";

const disposeMessageOverlay=() => {
    $("#messageOverlay").fadeOut(function(){
        $(this).html("");
    });
};

const showMessage=(html) => {
    $("#messageOverlay").html(html);
    $("#messageOverlay").fadeIn();
    setTimeout(function(){
        disposeMessageOverlay();
    }, 5000);
};

export const loadMessges=() => {
    $.ajax({
        url: "./modules/msg.php",
        type: "GET",
        success: function(response){
            if(response!=""){
                showMessage(response);
            }
        }
    });
};

export const goTo=(site, pop=false) => {
    $("#module").slideUp(function(){
        $.ajax({
            url: "./modules/loader.php",
            type: "GET",
            data: {"load": site.split("/")[0], "sub": site.split("/")[1]},
            success: function(response){
                $("#module").html(response);
                if(!pop){
                    window.history.pushState({"site": site}, null, "./"+site);
                }
                $("#module").slideDown();
            }
        });
    });
};

export const run=() => {
    window.addEventListener("popstate", function(e){
        if(e.state!=null){
            goTo(e.state["site"], true);
        }
        else{
            goTo("", true);
        }
    });
};

export const changeLanguage=() => {
    console.log("Switching to: ", $("#languageSelector").val());
    window.location=window.location+"?langstr="+$("#languageSelector").val();
}