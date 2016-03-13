// Define message types
var MESSAGE_TYPE_STATUS_UPDATES = 'statusUpdates';

/**
 * Represents a message
 * @param type
 * @param payload
 * @constructor
 */
var Message = function(type, payload) {
  this.type = type;
  this.payload = payload;
};

/**
 * Parses the specified data into a message object
 * @param data JSON data
 * @returns {Message|*}
 */
function parseMessage(data) {
  message = new Message(data.type);

  switch (data.type) {
    case MESSAGE_TYPE_STATUS_UPDATES:
      message.payload = data.payload;
  }

  return message;
}
