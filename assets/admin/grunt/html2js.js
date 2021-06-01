/* global module */

module.exports = {
    options: {
        base: '<%= srcDir %>',
        module: "ng",
        singleModule: true,
        existingModule: true,
        htmlmin: {
            collapseBooleanAttributes: false,
            collapseWhitespace: true,
            removeAttributeQuotes: false,
            removeComments: true,
            removeScriptTypeAttributes: true,
            removeStyleLinkTypeAttributes: true
        }
    }
};