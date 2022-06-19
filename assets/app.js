/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
//loads the jquery package from node_modules
const $ = require("jquery");
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require("bootstrap");
require("bootstrap-icons/font/bootstrap-icons.css");
// any CSS you import will output into a single css file (app.css in this case)
import "./styles/root.scss";
import "./styles/app.scss";
import "./styles/articles.scss";
import "./styles/article.scss";
import "./styles/albums.scss";
import "./styles/contact.scss";

// start the Stimulus application
import "./bootstrap";
import "./controllers/contact.js";
import "./controllers/nav.js";
