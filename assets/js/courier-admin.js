import jQuery from 'jquery';
import core from './admin/core';
import welcome from './admin/welcome';
import types from './admin/types';
import notifications from "./admin/notifications";

jQuery( function() {
    core();
    welcome();
    types();
    notifications();
});
