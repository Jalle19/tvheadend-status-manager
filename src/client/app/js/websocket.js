/**
 * Connection module
 */
var Connection = (function() {

  var websocket = null;
  var accessToken = null;
  var handlers = [];

  /**
   * Creates the WebSocket connection and attaches event handlers to the socket
   * @param hostname
   * @param port
   * @param reconnectInterval
   * @param token
   */
  function connect(hostname, port, reconnectInterval, token) {
    // Store the access token
    accessToken = token;
    
    websocket = new ReconnectingWebSocket('ws://' + hostname + ':' + port, null, {
      reconnectInterval: reconnectInterval
    });

    /**
     * 
     */
    websocket.onopen = function() {
      // Authenticate
      websocket.send(JSON.stringify(new Message(Message.TYPE_AUTHENTICATION_REQUEST, accessToken)));
      
      triggerEvent('onopen', websocket);
    };

    /**
     * 
     */
    websocket.onclose = function() {
      triggerEvent('onclose', websocket);
    };

    /**
     * @param event
     */
    websocket.onmessage = function(event) {
      var data = JSON.parse(event.data);

      triggerEvent('onmessage', new Message(data.type, data.payload));
    };
  }

  /**
   * Registers an event handler
   * @param eventName the event the handler is interested in
   * @param callback
   */
  function registerEventHandler(eventName, callback) {
    handlers.push({
      eventName: eventName,
      callback: callback
    });
  }

  /**
   * Triggers the specified event with the associated data
   * @param eventName
   * @param eventData
   */
  function triggerEvent(eventName, eventData) {
    handlers.forEach(function(handler) {
      if (handler.eventName === eventName) {
        handler.callback(eventData);
      }
    });
  }

  return {
    connect: connect,
    registerEventHandler: registerEventHandler
  };

}());

// Initialize the connection
Connection.connect(Settings.HOSTNAME, Settings.PORT, Settings.UPDATE_INTERVAL, Settings.ACCESS_TOKEN);
