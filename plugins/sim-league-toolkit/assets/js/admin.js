var $ = $ || jQuery;

$(document).ready(function() {
  $('#is-single-car-class').change(function() {
    if(this.checked) {
      $('#single-car-selector').show();
      $('.car-class-selector').hide();
    } else {
      $('#single-car-selector').hide();
      $('.car-class-selector').show();
    }
  });

  $('.date-picker-field').datepicker({
    dateFormat: "yy-mm-dd"
  });

  $('.time-picker-field').timepicker({
    timeFormat: 'H:i',
    step: 15,
    defaultTime: '14:00',
    dynamic: false,
    dropdown: true,
    minTime: '00:00',
    maxTime: '23:45',
    scrollbar: true
  });

  $('#sltk-is-hosted-server').change(function() {
    $('#sltk-tcp-port').prop('disabled', (i, v) => !v);
    $('#sltk-udp-port').prop('disabled', (i, v) => !v);
    $('#sltk-register-to-lobby').prop('disabled', (i, v) => !v);
    $('#sltk-max-connections').prop('disabled', (i, v) => !v);
    $('#sltk-lan-discovery').prop('disabled', (i, v) => !v);
    $('#sltk-public-ip').prop('disabled', (i, v) => !v);
    $('#sltk-ftp-host').prop('disabled', (i, v) => !v);
    $('#sltk-ftp-port').prop('disabled', (i, v) => !v);
    $('#sltk-ftp-username').prop('disabled', (i, v) => !v);
    $('#sltk-ftp-password').prop('disabled', (i, v) => !v);
    $('#sltk-ftp-results-directory').prop('disabled', (i, v) => !v);
  });
});