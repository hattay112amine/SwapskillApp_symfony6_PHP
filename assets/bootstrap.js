import './styles/app.css'; // ton CSS
import 'admin-lte/dist/js/adminlte.js'; // AdminLTE

// Stimulus setup
import { Application } from "stimulus";
import { definitionsFromContext } from "stimulus/webpack-helpers";

const application = Application.start();
const context = require.context("./controllers", true, /\.js$/);
application.load(definitionsFromContext(context));
