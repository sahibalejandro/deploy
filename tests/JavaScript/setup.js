require('jsdom-global')();
global.expect = require('expect');

// Temporary bug fix, should be removed after vue-test-utils fixes #936
window.Date = Date;
