var WEBSOCKET_HOSTNAME = '192.168.47.47';
var WEBSOCKET_RECONNECT_INTERVAL_MS = 1000;

var statusManagerApp = angular.module('statusManagerApp', [
  'angular-flot',
  'ngLodash'
]);

/**
 * Main controller
 */
statusManagerApp.controller('StatusController', function($scope, lodash) {
  /**
   * @type {number} the maximum amount of data points to display in graphs
   */
  var MAXIMUM_GRAPH_DATAPOINTS = 15;

  /**
   * @type {boolean} whether the client is connected to the server
   */
  $scope.connected = false;

  /**
   * @type {Array} the instances that are monitored
   */
  $scope.instances = [];

  /**
   * @type {string} the date format string to use
   */
  $scope.dateFormat = 'yyyy-MM-dd HH:mm:ss';

  /**
   * @type {Array} the subscription chart data
   */
  $scope.subscriptionChartData = [];

  /**
   * The options for the subscription chart
   */
  $scope.subscriptionChartOptions = {
    xaxis: {
      fill: true,
      show: false
    },
    yaxis: {
      axisLabel: "kbps",
      axisLabelFontSizePixels: 11
    },
    series: {
      stack: true,
      lines: {
        fill: true
      }
    }
  };

  /**
   * Formats the specified date
   * @param date
   * @returns {Date}
   */
  $scope.formatDate = function(date) {
    return new Date(date * 1000);
  };

  /**
   * Formats the specified bitrate
   * @param bps
   * @returns {number}
   */
  $scope.formatBitrate = function(bps) {
    return Math.round(bps * 8 / 1024);
  };

  /**
   *
   * @param subscriptionId
   * @returns {*}
   */
  var getSubscriptionDataset = function(instanceName, subscriptionId) {
    return lodash.find($scope.subscriptionChartData[instanceName], function(s) {
      return s.subscriptionId === subscriptionId;
    });
  };

  /**
   *
   * @param instanceName
   * @param subscriptionId
   */
  var removeSubscriptionDataset = function(instanceName, subscriptionId) {
    lodash.remove($scope.subscriptionChartData[instanceName], function(s) {
      return s.subscriptionId === subscriptionId;
    });
  };

  // Connect to the server
  var websocket = new ReconnectingWebSocket('ws://' + WEBSOCKET_HOSTNAME + ':9333', null, {
    reconnectInterval: WEBSOCKET_RECONNECT_INTERVAL_MS
  });

  /**
   * Called when the connection is opened
   */
  websocket.onopen = function() {
    $scope.connected = true;
    $scope.$apply();
  };

  /**
   * Called when the connection is lost
   */
  websocket.onclose = function() {
    $scope.connected = false;
    $scope.instances = [];
    $scope.subscriptionChartData = [];
    $scope.$apply();
  };

  /**
   * Parses incoming messages
   * @param event
   */
  websocket.onmessage = function(event) {
    $scope.instances = JSON.parse(event.data).instances;

    $scope.instances.forEach(function(instance) {
      var name = instance.instanceName;

      // Create a data set for the instance
      if (!(name in $scope.subscriptionChartData)) {
        $scope.subscriptionChartData[name] = [];
      }

      // Update the data set for each subscription
      instance.subscriptions.forEach(function(subscription) {
        var dataset = getSubscriptionDataset(name, subscription.id);

        // Create a new data set for new subscriptions
        if (dataset === undefined) {
          $scope.subscriptionChartData[name].push({
            subscriptionId: subscription.id,
            label: subscription.channel,
            data: []
          });
        }

        // Append the data
        var bitrate = $scope.formatBitrate(subscription.in);

        dataset = getSubscriptionDataset(name, subscription.id);
        dataset.data.push([Date.now(), bitrate]);

        // Limit the data
        if (dataset.data.length > MAXIMUM_GRAPH_DATAPOINTS)
          dataset.data.shift();
      });

      // Handle subscription state changes
      instance.subscriptionStateChanges.forEach(function(stateChange) {
        if (stateChange.state === 'stopped') {
          removeSubscriptionDataset(name, stateChange.subscriptionId);
        }
      });
    });

    $scope.$apply();
  };
});
