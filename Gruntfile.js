module.exports = function (grunt) {
    grunt.initConfig({
        clean: {
            build: ['ecwp']          // wipe the previous assembled folder
        },
        copy: {
            main: {
                expand: true,
                cwd: '.',
                src: [
                    '**',
                    '!node_modules/**',
                    '!build/**',
                    '!ecwp/**',
                    '!.git/**',
                    '!.github/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!package-lock.json',
                    '!.gitignore',
                    '!.distignore',
                    '!public/src/**',
                    'public/src/dist/**',
                    '!release.sh',
                    // blocks/** is covered by ** and not excluded — verify it lands
                ],
                dest: 'ecwp/'
            }
        },
        compress: {
            main: {
                options: { archive: 'ecwp.zip' },
                files: [{ expand: true, src: ['ecwp/**'], dest: '/' }]
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.registerTask('default', ['clean', 'copy']);
    grunt.registerTask('release', ['clean', 'copy', 'compress']);
};