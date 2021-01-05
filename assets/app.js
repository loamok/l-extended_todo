/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
const $ = require('jquery');
global.$ = global.jQuery = $;

import './styles/app.scss';

//import 'bootstrap';
require('bootstrap');
require('./js/jquery.timesetter/jquery.timesetter');

//import 'jose';
import bsCustomFileInput from 'bs-custom-file-input';
// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();

const routes = require('./js/fos_js_routes.json');
import Routing from '../public/bundles/fosjsrouting/js/router.min.js';

Routing.setRoutingData(routes);
global.routes = routes;
global.Routing = Routing;

import { showAlert, dissmissAlert } from './js/alerts';
global.showAlert = showAlert;
global.dissmissAlert = dissmissAlert;

import './js/notYet';
import './manipulateToken';
import './api/getAgTypes';
import './api/wt_parameters/wt_parameters';
import './formActions/params/paramFields';
import './formActions/params/times_selectors';
import './formActions/params/paramsActions';

// Animations
import './interfaces_animations/wtt/interfaces_animations';
