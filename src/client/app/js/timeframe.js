function TimeFrame() {

}

TimeFrame.getList = function() {
  return [
    TimeFrame.ALL_TIME,
    TimeFrame.LAST_MONTH,
    TimeFrame.LAST_WEEK,
    TimeFrame.LAST_DAY
  ];
};

TimeFrame.ALL_TIME = 'allTime';
TimeFrame.LAST_MONTH = 'lastMonth';
TimeFrame.LAST_WEEK = 'lastWeek';
TimeFrame.LAST_DAY = 'lastDay';

