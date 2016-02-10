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
          "src/client/css/bootstrap.css": "src/client/css/bootstrap/less/bootstrap.less"
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
};
