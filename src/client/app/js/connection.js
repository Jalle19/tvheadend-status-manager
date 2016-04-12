/**
 * The view model
 * @constructor
 */
function ConnectionViewModel() {
  this.connected = ko.observable(false);
  this.numInstances = ko.observable(0)
}

var connectionView = new ConnectionViewModel();
ko.applyBindings(connectionView, document.getElementById('connection-status'));

Connection.registerEventHandler('onopen', function() {
  connectionView.connected(true);
});

Connection.registerEventHandler('onclose', function() {
  connectionView.connected(false);
  connectionView.numInstances(0);
});

Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_STATUS_UPDATES)
    return;

  var instances = message.getPayload();
  connectionView.numInstances(instances.length);
});
