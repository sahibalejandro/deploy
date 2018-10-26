let nodeExternals = require('webpack-node-externals');
let laravelMixConfig = require('../../node_modules/laravel-mix/setup/webpack.config.js');

laravelMixConfig.externals = [nodeExternals()];
laravelMixConfig.devtool = 'inline-cheap-module-source-map';

module.exports = laravelMixConfig;
