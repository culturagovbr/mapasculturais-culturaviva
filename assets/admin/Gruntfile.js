/* global module, require */

module.exports = gruntConfig;

function gruntConfig(grunt) {

    // require it at the top and pass in the grunt instance
    require('time-grunt')(grunt);

    // Load grunt tasks automatically
    require('load-grunt-config')(grunt, {
        init: true
    });

    // Configs for connect
    grunt.config.set('connectPort', 9005);
    grunt.config.set('connectLivereload', 35731);

    // Config dirs
    grunt.config.set('srcDir', 'src');
    grunt.config.set('mdlDir', 'modules');
    grunt.config.set('distDir', 'dist');


    // Configuration of grunt-replace to allow automatic concat of dependencies
    grunt.config.set('replace', (function (config) {
        config.concat = require('./grunt/misc/concat-replace')(grunt);
        return config;
    })(grunt.config.get('replace') || {}));

    // packModules task
    require('./grunt/misc/pack-modules')(grunt);
}
