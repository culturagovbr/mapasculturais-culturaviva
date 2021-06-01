/* global module */

module.exports = {
    options: {
        basePath: '<%= srcDir %>'
    },
    development: {
        files: {
            '<%= distDir %>/index.html': '<%= srcDir %>/index.html'
        }
    }
};