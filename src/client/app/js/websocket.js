var WEBSOCKET_HOSTNAME = '192.168.47.47';
var WEBSOCKET_RECONNECT_INTERVAL_MS = 1000;

// Connect to the server
var websocket = new ReconnectingWebSocket('ws://' + WEBSOCKET_HOSTNAME + ':9333', null, {
  reconnectInterval: WEBSOCKET_RECONNECT_INTERVAL_MS
});

/**
 * Called when the connection is opened
 */
websocket.onopen = function() {
  handleSocketOpened();
};

/**
 * Called when the connection is lost
 */
websocket.onclose = function() {
  handleSocketClosed();
};

/**
 * Parses incoming messages
 * @param event
 */
websocket.onmessage = function(event) {
  // Parse the data into a message
  var data = JSON.parse(event.data);
  var message = parseMessage(data);

  if (message.message === MESSAGE_TYPE_STATUS_UPDATES)
    handleInstanceUpdates(message.payload);
};
