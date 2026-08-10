/**
 * Mock for @wordpress/interactivity module.
 *
 * This mock allows testing view.js files that use the WordPress Interactivity API.
 */

const contexts = new Map();
const elements = new Map();
const stores = new Map();

/**
 * Mock store function.
 *
 * @param {string} namespace - The store namespace.
 * @param {Object} config    - The store configuration.
 * @return {Object} The store object.
 */
export const store = jest.fn((namespace, config) => {
	const storeInstance = {
		state: config.state || {},
		actions: config.actions || {},
		callbacks: config.callbacks || {},
	};
	stores.set(namespace, storeInstance);
	return storeInstance;
});

/**
 * Mock getContext function.
 *
 * @return {Object} The current context.
 */
export const getContext = jest.fn(() => contexts.get('current') || {});

/**
 * Mock getElement function.
 *
 * @return {Object} The current element.
 */
export const getElement = jest.fn(
	() => elements.get('current') || { ref: null }
);

/**
 * Mock getConfig function.
 *
 * @param {string} namespace - The config namespace.
 * @return {Object} The configuration object.
 */
export const getConfig = jest.fn(() => ({}));

/**
 * Mock useContext hook.
 *
 * @return {Object} The context.
 */
export const useContext = jest.fn(() => contexts.get('current') || {});

/**
 * Mock useWatch hook. Deliberately does not run the callback — tests that
 * need watch behaviour should trigger it explicitly.
 */
export const useWatch = jest.fn(() => {});

/**
 * Mock useInit hook.
 *
 * @param {Function} callback - The init callback.
 */
export const useInit = jest.fn((callback) => {
	// Execute callback on mount simulation
	if (typeof callback === 'function') {
		callback();
	}
});

/**
 * Mock useEffect hook.
 *
 * @param {Function} callback - The effect callback.
 */
export const useEffect = jest.fn((callback) => {
	if (typeof callback === 'function') {
		callback();
	}
});

/**
 * Mock useState hook.
 *
 * @param {*} initial - The initial state value.
 * @return {Array} State and setter tuple.
 */
export const useState = jest.fn((initial) => {
	let value = initial;
	const setValue = jest.fn((newValue) => {
		value = typeof newValue === 'function' ? newValue(value) : newValue;
	});
	return [value, setValue];
});

/**
 * Mock useMemo hook.
 *
 * @param {Function} factory - The memoization factory.
 * @return {*} The memoized value.
 */
export const useMemo = jest.fn((factory) => factory());

/**
 * Mock useCallback hook.
 *
 * @param {Function} callback - The callback function.
 * @return {Function} The callback.
 */
export const useCallback = jest.fn((callback) => callback);

/**
 * Mock useRef hook.
 *
 * @param {*} initial - The initial ref value.
 * @return {Object} The ref object.
 */
export const useRef = jest.fn((initial) => ({ current: initial }));

// Test helpers
export const setContext = (ctx) => contexts.set('current', ctx);
export const setElement = (el) => elements.set('current', el);
export const clearMocks = () => {
	contexts.clear();
	elements.clear();
	stores.clear();
};
export const getStore = (namespace) => stores.get(namespace);
