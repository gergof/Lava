//import webcomponentsjs 
import "@webcomponents/webcomponentsjs";
//import style
import "../style/main.scss";

///
///import modules
///

//global.js
import * as mod_global from "./global.js";
export const main=mod_global;
main.run();