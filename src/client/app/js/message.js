function Message(type, payload) {
  this.type = type;
  this.payload = payload;
}

Message.prototype = {
  constructor: Message,
  getType: function() {
    return this.type;
  },
  getPayload: function() {
    return this.payload;
  }
};

Message.TYPE_AUTHENTICATION_REQUEST = 'authenticationRequest';
Message.TYPE_STATUS_UPDATES = 'statusUpdates';
Message.TYPE_POPULAR_CHANNELS_REQUEST = 'popularChannelsRequest';
Message.TYPE_POPULAR_CHANNELS_RESPONSE = 'popularChannelsResponse';
Message.TYPE_MOST_ACTIVE_WATCHERS_REQUEST = 'mostActiveWatchersRequest';
Message.TYPE_MOST_ACTIVE_WATCHERS_RESPONSE = 'mostActiveWatchersResponse';
Message.TYPE_INSTANCES_REQUEST = 'instancesRequest';
Message.TYPE_INSTANCES_RESPONSE = 'instancesResponse';
Message.TYPE_USERS_REQUEST = 'usersRequest';
Message.TYPE_USERS_RESPONSE = 'usersResponse';
