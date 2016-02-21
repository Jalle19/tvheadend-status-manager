// Define message types
var MESSAGE_TYPE_STATUS_UPDATES = 'statusUpdates';

/**
 * Represents a message
 * @param type
 * @constructor
 */
var Message = function(type) {
  this.message = type;
  this.payload = undefined;
};

/**
 * Parses the specified data into a message object
 * @param data JSON data
 * @returns {Message|*}
 */
function parseMessage(data) {
  message = new Message(data.message);

  switch (data.message) {
    case MESSAGE_TYPE_STATUS_UPDATES:
      message.payload = data.payload;
  }

  return message;
}
