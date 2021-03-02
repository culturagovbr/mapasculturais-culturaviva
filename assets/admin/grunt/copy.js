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
    },
    dist_asset: {
        files: {
            cwd: '<%= distDir %>',  // set working folder / root to copy
            src: '**/*',           // copy all files and subfolders
            dest: '<%= distDir %>/../../../../assets/admin',    // destination folder
            expand: true           // required when using cwd
        }
    }
};