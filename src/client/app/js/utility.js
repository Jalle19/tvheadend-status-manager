/**
 * @type {string} the date format string to use
 */
var dateFormat = 'YYYY-MM-DD HH:mm:ss';

/**
 * Formats the specified date
 * @param date
 * @returns {Date}
 */
function formatDate(date) {
  return moment(new Date(date * 1000)).format(dateFormat);
}

/**
 * Formats the specified bitrate
 * @param bps
 * @returns {number}
 */
function formatBitrate(bps) {
  if (bps !== undefined)
    return Math.round(bps * 8 / 1024);
  else
    return '';
}
