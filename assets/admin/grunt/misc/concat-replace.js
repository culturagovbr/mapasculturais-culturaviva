/* global module */
'use strict';

/**
 * Configuration of grunt-replace to allow automatic concat of dependencies
 *
 * <code>
 *     <!-- concat:js[:distVendorJS] vendor/vendor.min.js -->
 *     <script src="vendor/jquery/dist/jquery.min.js"></script>
 *     <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
 *     <!-- /concat -->
 * </code>
 *
 * @param {type} grunt
 * @returns
 */
module.exports = function (grunt) {
    return {
        files: [
            {
                expand: true,
                flatten: true,
                src: [
                    '<%= srcDir %>/index.html'
                ],
                dest: '<%= distDir %>'
            }
        ],
        options: {
            patterns: [
                {
                    match: /<!--\s*concat:(\S*)\[\:(\S*)\]\s+(\S*)\s*-->((\n|\r|.)*?)<!--\s*\/concat\s*-->/gi,
                    replacement: function ($0, type, target, dest, value) {

                        // Config for concat task
                        var concatConfig = grunt.config.get('concat') || {};
                        concatConfig[target] = {
                            src: [],
                            dest: '<%= distDir %>/' + dest
                        };

                        var out;
                        if (type === 'js') {
                            var reg = /(?:\s+src=['"])([^'"]+)/gi;
                            var match;
                            while (true) {
                                match = reg.exec(value);
                                if (!match) {
                                    break;
                                }
                                concatConfig[target].src.push('<%= srcDir %>/' + match[1]);
                            }
                            out = '<script src="' + dest + '"></script>';
                        } else if (type === 'css') {
                            var reg = /(?:\s+href=['"])([^'"]+)/gi;
                            var match;
                            while (true) {
                                match = reg.exec(value);
                                if (!match) {
                                    break;
                                }
                                concatConfig[target].src.push('<%= srcDir %>/' + match[1]);
                            }
                            out = '<link rel="stylesheet" href="' + dest + '" />';
                        } else {
                            return '';
                        }

                        grunt.config.set('concat', concatConfig);

                        return out;
                    }
                }
            ]
        }
    };
};