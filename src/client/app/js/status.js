/**
 * The view model for this page
 * @constructor
 */
function StatusViewModel() {
  this.connected = ko.observable(false);
  this.instances = ko.observableArray();
}

// Create the view model and attach bindings
var statusView = new StatusViewModel();
ko.applyBindings(statusView);

var handleSocketOpened = function() {
  statusView.connected(true);
};

var handleSocketClosed = function() {
  statusView.connected(false);
  statusView.instances([]);
};

/**
 * Handles instance update messages
 * @param instances
 */
var handleInstanceUpdates = function(instances) {
  statusView.instances(instances);
};
