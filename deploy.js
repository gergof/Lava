var FTPDeploy=require("ftp-deploy");
var ftpDeploy=new FTPDeploy();
var cnf=require("./deploy.config.js");

var config={
    user: cnf.user,
    host: cnf.host,
    port: cnf.port,
    localRoot: __dirname+"/dist",
    remoteRoot: cnf.remote,
    include: ["**/*", "**/.*"],
    deleteRemote: true,
    forcePasv: true
}

ftpDeploy.on("uploading", function(data){
    console.log("Uploaded: "+data.transferredFileCount+"/"+data.totalFilesCount+" Now uploading: "+data.filename);
});

ftpDeploy.deploy(config).then((res) => console.log("Finished")).catch((e) => console.log(e));