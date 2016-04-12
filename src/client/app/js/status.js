/**
 * The view model for this page
 * @constructor
 */
function StatusViewModel() {
  this.instances = ko.observableArray();
}

var statusView = new StatusViewModel();
ko.applyBindings(statusView, document.getElementById('streaming-status'));

// Handle connection events
Connection.registerEventHandler('onclose', function() {
  statusView.instances([]);
});

/**
 *
 */
Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_STATUS_UPDATES)
    return;

  var instances = message.getPayload();
  statusView.instances(instances);
});
