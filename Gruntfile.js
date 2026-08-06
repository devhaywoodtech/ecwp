module.exports = function (grunt) {
    grunt.initConfig({
        copy: {
            main: {
                expand: true,
                cwd: '.',
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!ecwp/**',            // don't copy the output folder into itself
                    '!.git/**',
                    '!.github/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!.gitignore',
                    '!.distignore',
                    '!public/src/**',       // exclude the React source...
                    'public/src/dist/**'    // ...but keep the built assets
                ],
                dest: 'ecwp/'
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['copy']);
};