/* global module */
'use strict';


var path = require('path');

// Source code dir
var SRC_DIR;

// Build dir
var DIST_DIR;

// Application modules dir
var MODL_DIR;


// ---------------------------------------------------------------------------------------------------------------------
// Allows you to minify or not the bundle (useful for debugging the final version)
//
// MARK AS TRUE FOR PRODUCTION
// ---------------------------------------------------------------------------------------------------------------------
var UGLIFY_BUNDLE = true;


module.exports = function (grunt) {
    srcMod.grunt = grunt;
    destMod.grunt = grunt;


    /**
     * Dynamic task for the packaging of application modules
     */
    grunt.registerTask("packModules", "Dynamic task for the packaging of application modules (like Webpack)", function () {

        // Get dirs from config
        SRC_DIR = grunt.config.get('srcDir');
        DIST_DIR = grunt.config.get('distDir');
        MODL_DIR = grunt.config.get('mdlDir');

        grunt.file.expand(srcMod('*')).forEach(function (dir) {
            packModule(grunt, dir);
        });
    });
};

/**
 *
 * @param {type} grunt
 * @param {type} moduleDir
 */
function packModule(grunt, moduleDir) {
    var moduleTasks = [];
    var module = path.basename(moduleDir);
    var moduleName = module
            .replace(/[^a-z]/gi, ' ')
            .replace(/(\s+(.))/g, function ($0, $1, $2) {
                return $2.toUpperCase();
            });

    // clean
    configure('clean', function (config) {
        config.push(destMod(module, '*.js'));
        config.push(destMod(module, '**/*.css'));
        config.push(destMod(module, '**/*.map'));
    }, 'start');

    // sass
    configure('sass', function (config) {
        config.files = [
            {
                expand: true,
                cwd: srcMod(module),
                src: ['styles/*.scss'],
                dest: destMod(module),
                ext: '.css'
            }
        ];
    });

    // html2js - templates angular.js
    configure('html2js', function (config) {
        config.options.module = moduleName;
        config.src = srcMod(module, '**/*.html');
        config.dest = destMod(module, 'templates.js');
    });

    // copy - Assets
    configure('copy', function (config) {
        config.files = [
            {
                expand: true,
                cwd: srcMod(module),
                src: ['**/*.{css,png,jpg,jpeg,ttf,ico,json,svg,woff,woff2,eot}'],
                dest: destMod(module),
                filter: 'isFile'
            }
        ];
    });

    // cssUrlEmbed - base64 in css
    configure('cssUrlEmbed', function (config) {
        config.expand = true;
        config.cwd = destMod(module);
        config.src = ['**/*.css'];
        config.dest = destMod(module);
    });

    // cssmin
    configure('cssmin', function (config) {
        config.files = {};
        config.files[destMod(module, 'styles.min.css')] = destMod(module, '**/*.css');
    });

    // css2js
    configure('css2js', function (config) {
        config.src = destMod(module, 'styles.min.css');
        config.dest = destMod(module, 'styles.js');
    });

    // concat - Concatenate all module .js resources in a single file
    configure('concat', function (config) {
        config.options.process = function (src, filepath) {
            return ';(function(window){\n' + src + '\n})(window);';
        };
        config.src = [
            srcMod(module, module + '.js'),
            srcMod(module, '**/*.js')
        ];
        config.dest = destMod(module, 'module.js');
    }, 'JsFromSource');

    // Uglify - Package
    configure(UGLIFY_BUNDLE ? 'uglify' : 'concat', function (config) {
        config.options.banner = 'window.__BUNDLED__MODULES__ = true;';
        config.src = [
            destMod(module, 'styles.js'),
            destMod(module, 'module.js'),
            destMod(module, 'templates.js')
        ];
        config.dest = destMod(module, moduleName + '.bundle.min.js');
    }, 'bundle');

    // clean - Files used in the build process
    configure('clean', function (config) {
        config.push(
                destMod(module, '*.js'),
                '!' + destMod(module, '*.min.js'),
                destMod(module, '**/*.css'),
                destMod(module, '**/*.map')
                );
    }, 'end');

    /**
     * Utility to generate module configuration
     *
     * @param {type} task
     * @param {type} callback
     * @param {type} suffix
     * @returns {undefined}
     */
    function configure(task, callback, suffix) {
        grunt.config.set(task, (function (config) {
            var subTask = moduleName + (suffix ? ('__' + suffix) : '');
            moduleTasks.push(task + ':' + subTask);

            if (task === 'clean') {
                config[subTask] = [];
            } else {
                config[subTask] = {options: {}};
            }
            callback(config[subTask]);
            return config;
        })(grunt.config.get(task) || {}));
    }

    // Finally, perform the tasks (per module)
    moduleTasks.forEach(function (task) {
        grunt.task.run(task);
    });
}

/**
 * Generates the module source code path
 *
 * @param {type} module
 * @param {type} filter
 * @returns {String}
 */
function srcMod(module, filter) {
    return SRC_DIR + '/' + MODL_DIR + '/' + module + (filter ? '/' + filter : '');
}

/**
 * Generates the module destiny path
 *
 * @param {type} module
 * @param {type} filter
 * @returns {String}
 */
function destMod(module, filter) {
    return DIST_DIR + '/' + MODL_DIR + '/' + module + (filter ? '/' + filter : '');
}