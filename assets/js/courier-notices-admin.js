import core from './admin/core';
import welcome from './admin/welcome';
import types from './admin/types';
import edit from './admin/edit';
import list from './admin/list';
import settings from './admin/settings';
import notifications from "./admin/notifications";

const $ = jQuery;

jQuery( function() {
    core();
    settings();
    edit();
    list();
    welcome();
    types();
    notifications();
});
