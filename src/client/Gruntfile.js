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
          'node_modules/reconnectingwebsocket/reconnecting-websocket.js',
          'node_modules/knockout/build/output/knockout-latest.debug.js',
          'node_modules/moment/moment.js',
          'node_modules/jquery/dist/jquery.js'
        ],
        dest: 'app/js/vendor/vendor.js'
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
};
