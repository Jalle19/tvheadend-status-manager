// View model for the most popular channels
function MostPopularChannelsViewModel(websocket) {
  this.websocket = websocket;

  this.instances = ko.observableArray();
  this.users = ko.observableArray();
  this.limit = ko.observable(10);
  this.timeFrames = ko.observableArray(TimeFrame.getList());

  this.instance = ko.observable();
  this.user = ko.observable();
  this.timeFrame = ko.observable();

  // Request a list of all instances
  websocket.send(JSON.stringify(new Message(Message.TYPE_INSTANCES_REQUEST)));
}

MostPopularChannelsViewModel.prototype = {
  constructor: MostPopularChannelsViewModel,
  refreshStatistics: function() {
    var instance = this.instance();
    var user = this.user();
    var limit = this.limit();
    var timeFrame = this.timeFrame();

    // Refresh the list of users
    if (instance !== undefined) {
      this.websocket.send(JSON.stringify(new Message(Message.TYPE_USERS_REQUEST, instance)));
    }

    // Ignore early updates
    if (instance === undefined || user === undefined || timeFrame === undefined) {
      return;
    }

    // Request updated statistics
    this.websocket.send(JSON.stringify(new Message(Message.TYPE_POPULAR_CHANNELS_REQUEST, {
      instanceName: instance,
      userName: user,
      timeFrame: timeFrame,
      limit: limit
    })));
  }
};

var mostPopularChannels = null;

/**
 * Create and bind the view model when the connection is opened
 */
Connection.registerEventHandler('onopen', function(websocket) {
  mostPopularChannels = new MostPopularChannelsViewModel(websocket);
  ko.applyBindings(mostPopularChannels, document.getElementById('most-popular-channels'));
});

/**
 * Refreshes the most popular channels chart
 */
Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_POPULAR_CHANNELS_RESPONSE)
    return;

  var payload = message.getPayload();

  var labels = [];
  var series = [];

  payload.response.forEach(function(e) {
    labels.push(e.channelName);
    series.push(e.totalTimeSeconds);
  });

  var options = {
    labelInterpolationFnc: function(value) {
      return value[0]
    }
  };

  var responsiveOptions = [
    ['screen and (min-width: 640px)', {
      chartPadding: 30,
      labelOffset: 100,
      labelDirection: 'explode',
      labelInterpolationFnc: function(value) {
        return value;
      }
    }],
    ['screen and (min-width: 1024px)', {
      labelOffset: 80,
      chartPadding: 20
    }]
  ];

  new Chartist.Pie('#most-popular-channels-chart', {
    labels: labels,
    series: series
  }, options, responsiveOptions);
});

Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_MOST_ACTIVE_WATCHERS_RESPONSE)
    return;

  var payload = message.getPayload();
  console.log(payload);
});

Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_INSTANCES_RESPONSE)
    return;

  mostPopularChannels.instances(message.getPayload());
});

Connection.registerEventHandler('onmessage', function(message) {
  if (message.getType() !== Message.TYPE_USERS_RESPONSE)
    return;

  mostPopularChannels.users(message.getPayload());
});
