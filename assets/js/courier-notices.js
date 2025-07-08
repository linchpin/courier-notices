import core from "./frontend/core";
import dismiss from "./frontend/dismiss";
import modal from "./frontend/modal";

const $ = jQuery;

jQuery( function() {
	core();
	dismiss();
	modal();
} );
