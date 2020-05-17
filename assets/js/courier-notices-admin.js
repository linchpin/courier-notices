import jQuery from 'jquery';
import core from './admin/core';
import welcome from './admin/welcome';
import types from './admin/types';
import edit from './admin/edit';
import list from './admin/list';
import notifications from "./admin/notifications";

jQuery( function() {
    core();
    edit();
    list();
    welcome();
    types();
    notifications();
});
