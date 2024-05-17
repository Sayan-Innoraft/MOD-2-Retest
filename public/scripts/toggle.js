/**
 * On clicking the man of the match button, it toggles the man of the match
 * details.
 */
$(document).ready(function () {
  $('#unhealthy').click(function () {
    $('#tbl-u').toggleClass('visually-hidden');
  })
  $('#healthy').click(function () {
    $('#tbl-h').toggleClass('visually-hidden');
  })
})