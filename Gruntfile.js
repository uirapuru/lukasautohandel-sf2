
module.exports = function (grunt) {
    //require('time-grunt')(grunt);
    //require('quiet-grunt');

    var cssVendors = [
        'bower_components/bootstrap/dist/css/bootstrap.css',
        'bower_components/font-awesome/css/font-awesome.css',
        'bower_components/flag-icon-css/css/flag-icon.css',
        'bower_components/prettyPhoto/css/prettyPhoto.css',
    ];

    var lessFiles = [
        'src/Dende/FrontBundle/Resources/less/main.less',
    ];

    var jsVendors = [
        'bower_components/jquery/dist/jquery.min.js',
        'bower_components/jquery-migrate/jquery-migrate.js',
        'bower_components/prettyPhoto/js/jquery.prettyPhoto.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
    ];

    var coffeeFilesAdmin = [
        'src/Dende/FrontBundle/Resources/coffee/addImagePlugin.coffee',
        'src/Dende/FrontBundle/Resources/coffee/toggleWidgets.coffee',
        'src/Dende/FrontBundle/Resources/coffee/updatePriceSelect.coffee',
        'src/Dende/FrontBundle/Resources/coffee/main.coffee',
    ];

    var coffeeFilesFront = [
        'src/Dende/FrontBundle/Resources/coffee/searchForm.coffee',
        'src/Dende/FrontBundle/Resources/coffee/frontend.coffee',
    ];

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: {
            build:            { src: "build/assets" },
            web:              { src: [ "web/assets", "web/js", "web/css", "web/fonts", "web/images", "web/flags"] },
            "dev-assets":     { src: ["!web/js/*.js", "!web/js/*.min.js", "!web/css/backend/*.css", "!web/css/backend/*.min.css"] }
        },
        watch: {
            scriptsBackend: {
                files: coffeeFilesAdmin,
                tasks: ['coffee:development'],
                options: {
                    spawn: false,
                },
            },
            scriptsFrontend: {
                files: coffeeFilesFront,
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
                    "web/css/backend/project.css" : lessFiles
                }
            },
        },
        uglify: {
            production: {
                files: {
                    'web/js/backend/vendors.min.js': 'web/js/backend/vendors.js',
                    'web/js/backend/project.min.js': 'web/js/backend/project.js',
                    //'web/js/frontend/vendors.min.js': 'web/js/frontend/vendors.js',
                    'web/js/frontend/project.min.js': 'web/js/frontend/project.js'

                },
            },
        },
        cssmin: {
            "production-vendors": {
                src: 'web/css/backend/vendors.css',
                dest: 'web/css/backend/vendors.min.css'
            },
            "production-project": {
                src: 'web/css/backend/project.css',
                dest: 'web/css/backend/project.min.css'
            },
        },
        coffee: {
            development: {
                files: {
                    'web/js/backend/project.js': coffeeFilesAdmin,
                    'web/js/frontend/project.js': coffeeFilesFront
                },
            },
        },
        concat: {
            "vendors.css": {
                src: cssVendors,
                dest: 'web/css/backend/vendors.css',
                nonull: true
            },
            "vendors.js": {
                src: jsVendors,
                dest: 'web/js/backend/vendors.js',
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
            prettyPhotoImages: {
                expand: true,
                flatten: false,
                cwd: './bower_components/prettyPhoto/images/prettyPhoto/default',
                src: [
                    '*',
                ],
                dest: "./web/images/prettyPhoto/default"
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
        "concat:vendors.css",                   // concatenates vendors into one web/css/backend/vendors.css file
        "less:development-project",             // compiles *.less from project into one web/css/backend/project.css file
    ]);

    grunt.registerTask('js:development', [
        "coffee:development",                  // compiles *.coffee files into one web/js/backend/project.js
        "concat:vendors.js",                   // concatenates vendors into one web/js/backend/vendors.js
    ]);

    grunt.registerTask('development', [
        //"clean:build",
        //"clean:web",
        "css:development",
        "js:development",
        "copy:images",
        "copy:prettyPhotoImages",
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

