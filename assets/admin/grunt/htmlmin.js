/* global module */

module.exports = {
    options: {
        removeComments: true,
        collapseWhitespace: true,
        collapseBooleanAttributes:false
    },
    dist: {
        files: {
            '<%= distDir %>/index.html': '<%= distDir %>/index.html'
        }
    }
};