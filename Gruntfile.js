module.exports = function( grunt ) {
  'use strict';

  // Project configuration
  grunt.initConfig({
    pkg: grunt.file.readJSON( 'package.json' ),
    
    sass: {
      dist: {
        options: {
          style: 'expanded'
        },
        files: [{
          expand: true,
          cwd: './assets/scss',
          src: ['./**/*.scss'],
          dest: './assets/css',
          ext: '.css'
        }]
      },
      postcss: {
        options: {
          map: true,
          processors: [
            require('browserslist'),
            require('cssnano')()
          ]
        },
        dist: {
          expand: true,
          cwd: "./assets/css",
          src: ["*.css", "!*.min.css"],
          dest: "./assets/css",
        }
      },
      cssmin: {
        files: [{
          expand: true,
          cwd: "./assets/css",
          src: ["*.css", "!*.min.css"],
          dest: "./assets/css",
          ext: ".min.css"
        }]
      }
    },

    uglify: {
      dist: {
        files: [{
          expand: true,
          cwd: "./assets/js",
          src: ["**/*.js", "!*.min.js"],
          dest: "./assets/js",
          rename: function (dst, src) {
            return dst + '/' + src.replace('.js', '.min.js');
          }
        }]
      }
    },

    addtextdomain: {
      options: {
        textdomain: 'pixel-labs',
      },
      update_all_domains: {
        options: {
          updateDomains: true
        },
        src: [ '*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*' ]
      }
    },

    wp_readme_to_markdown: {
      readme: {
        files: {
          'README.md': 'readme.txt'
        }
      },
    },

    makepot: {
      target: {
        options: {
          domainPath: '/languages',
          exclude: [ '\.git/*', 'bin/*', 'node_modules/*', 'tests/*' ],
          mainFile: 'pl-jobs.php',
          potFilename: 'pl-jobs.pot',
          potHeaders: {
            poedit: true,
            'x-poedit-keywordslist': true
          },
          type: 'wp-plugin',
          updateTimestamp: true
        }
      }
    },

    watch: {
      sass: {
        files: ["./assets/sass/**/*.scss"],
        tasks: ["sass"],
        options: {
          debounceDelay: 250,
        },
      }
    }
  });

  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('default', ['i18n', 'sass', 'uglify']);
  grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
  grunt.registerTask('readme', ['wp_readme_to_markdown']);
  grunt.registerTask('watch-files', ['watch']);

  grunt.util.linefeed = '\n';
};
