<<<<<<< HEAD
//import { startStimulusApp } from '@symfony/stimulus-bundle';

//const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
=======
import './styles/app.css'; // ton CSS
import 'admin-lte/dist/js/adminlte.js'; // AdminLTE

// Stimulus setup
import { Application } from "stimulus";
import { definitionsFromContext } from "stimulus/webpack-helpers";

const application = Application.start();
const context = require.context("./controllers", true, /\.js$/);
application.load(definitionsFromContext(context));
>>>>>>> origin/feature/edit-profile
