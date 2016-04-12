/**
 * Connection module
 */
var Connection = (function() {

  var websocket = null;
  var handlers = [];

  /**
   * Creates the WebSocket connection and attaches event handlers to the socket
   * @param hostname
   * @param reconnectInterval
   */
  function connect(hostname, reconnectInterval) {
    websocket = new ReconnectingWebSocket('ws://' + hostname + ':9333', null, {
      reconnectInterval: reconnectInterval
    });

    /**
     * 
     */
    websocket.onopen = function() {
      triggerEvent('onopen');
    };

    /**
     * 
     */
    websocket.onclose = function() {
      triggerEvent('onclose');
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

Connection.connect('192.168.47.47', 1000);
