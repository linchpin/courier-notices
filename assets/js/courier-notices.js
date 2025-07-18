import core from './frontend/core';
import dismiss from './frontend/dismiss';
import modal from './frontend/modal';

// Initialize modules when DOM is ready
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', function () {
		core();
		dismiss();
		modal();
	});
} else {
	// DOM already loaded
	core();
	dismiss();
	modal();
}
