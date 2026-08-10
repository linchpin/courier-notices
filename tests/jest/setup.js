/**
 * Jest setup file for Courier Notices. Runs before each test file.
 */

// jsdom implements neither observer API; the frontend lazy-fetch relies on
// IntersectionObserver, so every view.js test needs these stubs.
global.IntersectionObserver = jest.fn().mockImplementation(() => ({
	observe: jest.fn(),
	unobserve: jest.fn(),
	disconnect: jest.fn(),
}));

global.ResizeObserver = jest.fn().mockImplementation(() => ({
	observe: jest.fn(),
	unobserve: jest.fn(),
	disconnect: jest.fn(),
}));
