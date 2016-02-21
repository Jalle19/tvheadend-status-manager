module.exports = function(grunt) {
  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          "app/css/bootstrap.css": "app/css/bootstrap/less/bootstrap.less"
        }
      }
    },
    concat: {
      options: {
        separator: ';'
      },
      dist: {
        src: [
          'node_modules/angular/angular.js',
          'node_modules/angular-flot/angular-flot.js',
          'node_modules/jquery-flot/jquery.js',
          'node_modules/jquery-flot/jquery.flot.js',
          'node_modules/ng-lodash/build/ng-lodash.js',
          'node_modules/reconnectingwebsocket/reconnecting-websocket.js',
          'app/js/vendor/jquery-flot-axislabels/jquery.flot.axislabels.js'
        ],
        dest: 'app/js/vendor/vendor.js'
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
};
