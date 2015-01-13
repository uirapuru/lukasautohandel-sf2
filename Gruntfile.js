
module.exports = function (grunt) {
    require('time-grunt')(grunt);
    require('quiet-grunt');

    var cssVendors = [
        'bower_components/bootstrap/dist/css/bootstrap.css',
        'bower_components/font-awesome/css/font-awesome.css',
        'bower_components/flag-icon-css/css/flag-icon.css',
        'bower_components/prettyPhoto/css/prettyPhoto.css',
    ];

    var lessFiles = [
        'src/Dende/FrontBundle/Resources/less/main.less',
    ];

    var cssVendorsCv = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css'
    ];

    var lessFilesCv = [
        'src/Dende/FrontBundle/Resources/less/download.less',
    ];

    var jsVendors = [
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/angular/angular.min.js',
        'bower_components/angular-animate/angular-animate.min.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'bower_components/modernizr/modernizr.js',
        'bower_components/prettyPhoto/js/jquery.prettyPhoto.js',
    ];

    var coffeeFiles = [
        'src/Dende/FrontBundle/Resources/coffee/main.coffee'
    ];

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            build:            { src: "build/assets" },
            web:              { src: [ "web/assets", "web/js", "web/css", "web/fonts", "web/images", "web/flags"] },
            "dev-assets":     { src: ["!web/js/*.js", "!web/js/*.min.js", "!web/css/*.css", "!web/css/*.min.css"] }
        },
        watch: {
            scripts: {
                files: coffeeFiles,
                tasks: ['coffee:development'],
                options: {
                    spawn: false,
                },
            },
            styles: {
                files: lessFiles,
                tasks: ['less:development-project'],
                options: {
                    spawn: false,
                },
            }
        },
        less: {
            "development-project": {
                options: {
                    paths: [ "src", 'app/Resources' ],
                    compress: false,
                    yuicompress: false,
                    optimization: 0
                },
                files : {
                    "web/css/project.css" : lessFiles
                }
            },
        },
        uglify: {
            production: {
                files: {
                    'web/js/vendors.min.js': 'web/js/vendors.js',
                    'web/js/project.min.js': 'web/js/project.js'
                },
            },
        },
        cssmin: {
            "production-vendors": {
                src: 'web/css/vendors.css',
                dest: 'web/css/vendors.min.css'
            },
            "production-project": {
                src: 'web/css/project.css',
                dest: 'web/css/project.min.css'
            },
        },
        coffee: {
            development: {
                files: {
                    'web/js/project.js': coffeeFiles
                },
            },
        },
        concat: {
            "vendors.css": {
                src: cssVendors,
                dest: 'web/css/vendors.css',
                nonull: true
            },
            "vendors.js": {
                src: jsVendors,
                dest: 'web/js/vendors.js',
                nonull: true
            },
        },
        copy: {
            fonts: {
                expand: true,
                flatten: true,
                filter: 'isFile',
                src: [
                    'bower_components/font-awesome/fonts/*',
                    'bower_components/bootstrap/fonts/*',
                ],
                dest: "./web/fonts/"
            },
            images: {
                expand: true,
                flatten: true,
                cwd: '',
                filter: 'isFile',
                src: [
                    './src/**/images/**/*.{png,jpg,svg,gif}',
                    './bower_components/bootstrap/images/*.{png,jpg,svg,gif}',
                ],
                dest: "./web/images"
            },
            flags: {
                expand: true,
                flatten: false,
                cwd: './bower_components/flag-icon-css/flags/4x3',
                src: [
                    '*.svg',
                ],
                dest: "./web/flags/4x3"
            }
        }
    });

    grunt.registerTask('css:development', [
        "concat:vendors.css",                   // concatenates vendors into one web/css/vendors.css file
        "less:development-project",             // compiles *.less from project into one web/css/project.css file
    ]);

    grunt.registerTask('js:development', [
        "coffee:development",                  // compiles *.coffee files into one web/js/project.js
        "concat:vendors.js",                   // concatenates vendors into one web/js/vendors.js
    ]);

    grunt.registerTask('development', [
        "clean:build",
        "clean:web",
        "css:development",
        "js:development",
        "copy:images",
        "copy:fonts",
        "copy:flags",
    ]);

    grunt.registerTask('production', [
        "development",
        "cssmin:production-vendors",
        "cssmin:production-project",
        "uglify:production",
        "clean:dev-assets"
    ]);

    grunt.registerTask('default', [
        'production'
    ]);

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks("grunt-contrib-clean");
    grunt.loadNpmTasks("grunt-exec");
    grunt.loadNpmTasks('grunt-contrib-watch');
};

