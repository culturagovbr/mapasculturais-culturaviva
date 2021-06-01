/* global module */

module.exports = {
    options: {
        verbose: true,
        failOnError: true,
        updateAndDelete: true
    },
    dist_vendor_fonts: {
        files: [
            {
                expand: true,
                cwd: '<%= srcDir %>/vendor/bootstrap/dist/fonts/',
                src: '*',
                dest: '<%= distDir %>/vendor/fonts/',
                filter: 'isFile'
            },
            {
                expand: true,
                cwd: '<%= srcDir %>/vendor/patternfly/dist/fonts/',
                src: '*',
                dest: '<%= distDir %>/vendor/fonts/',
                filter: 'isFile'
            }
        ]
    },
    dist_index: {
        files: [
            {
                src: '<%= distDir %>/index.html',
                dest: '<%= distDir %>/../../../views/admin/index.php'
            }
        ]
    }
};