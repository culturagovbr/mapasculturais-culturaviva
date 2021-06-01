/* global module */

module.exports = {
    options: {
        sourceMap: false
    },
    development: {
        options: {
            sourceMap: true,
            sourceMapContents: true
        },
        files: [
            {
                expand: true,
                cwd: '<%= srcDir %>',
                src: ['<%= mdlDir %>/**/*.scss'],
                dest: '<%= distDir %>',
                ext: '.css'
            }
        ]
    }
};