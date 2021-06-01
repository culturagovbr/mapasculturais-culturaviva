/* global module */

module.exports = {
    sass: {
        files: ['<%= srcDir %>/<%= mdlDir %>/**/*.scss'],
        tasks: ['sass:development']
    },
    includeSourceNewFiles: {
        options: {
            livereload: '<%= connectLivereload %>',
            event: ['added', 'deleted']
        },
        files: [
            '<%= srcDir %>/<%= mdlDir %>/**/*.scss',
            '<%= srcDir %>/<%= mdlDir %>/**/*.js'
        ],
        tasks: ['includeSource:development']
    },
    includeSourceIndex: {
        options: {event: ['changed']},
        files: ['<%= srcDir %>/index.html'],
        tasks: ['includeSource:development']
    },
    livereload: {
        options: {
            livereload: '<%= connectLivereload %>',
            event: ['changed']
        },
        files: [
            '<%= srcDir %>/<%= mdlDir %>/**/*',
            '!<%= srcDir %>/<%= mdlDir %>/**/*.scss',
            '<%= distDir %>/**/*'
        ]
    }
};
