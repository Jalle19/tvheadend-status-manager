var statusManagerApp = angular.module('statusManagerApp', []);

/**
 * Main controller
 */
statusManagerApp.controller('StatusController', function($scope) {
  // Keeps track of the connection status
  $scope.connected = false;

  // Date formatting
  $scope.dateFormat = 'yyyy-MM-dd HH:mm:ss';
  $scope.formatDate = function(date) {
    var dateOut = new Date(date * 1000);
    return dateOut;
  };

  // Bitrate formatting
  $scope.formatBitrate = function(bps) {
    return Math.round(bps / 1024);
  };

  // Connect to the server
  var websocket = new ReconnectingWebSocket('ws://192.168.47.47:9333');

  /**
   * Called when the connection is opened
   */
  websocket.onopen = function() {
    $scope.connected = true;
    $scope.$apply();
  };

  /**
   * Parses incoming messages
   * @param event
   */
  websocket.onmessage = function(event) {
    var instances = JSON.parse(event.data).instances;
    var scopeInstances = [];

    for (var i = 0; i < instances.length; i++) {
      var instance = instances[i];

      var name = instance.instanceName;
      var inputs = instance.inputs;
      var subscriptions = instance.subscriptions;
      var connections = instance.connections;

      scopeInstances.push({
        'name': name,
        'inputs': inputs,
        'subscriptions': subscriptions,
        'connections': connections
      });
    }

    // Update scope
    $scope.instances = scopeInstances;
    $scope.$apply();
  };

  /**
   * Called when the connection is lost
   */
  websocket.onclose = function() {
    $scope.connected = false;

    // Clear all instances
    $scope.instances = [];

    $scope.$apply();
  };
});
