module.exports = function(grunt) {
    grunt.initConfig({
        copy: {
            main: {
                expand: true,
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!public/src/**',       // Exclude public/src/ and its contents
                    'public/src/dist/**'    // Include only public/src/dist/
                ],
                dest: 'ecwp',
                rename: function(dest, src) {
                    // Add parent directory to destination path
                    return dest + '/' + src.replace(/^\/?/, '');
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['copy']);
};

