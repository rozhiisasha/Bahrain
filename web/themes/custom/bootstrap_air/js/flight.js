(function ($, Drupal) {

  Drupal.behaviors.statistics = {
    attach() {
      $('.flight-arrivals tr > td:nth-child(5), .flight-departures tr > td:nth-child(5)').once('status').each(function () {
        if ($(this).text().indexOf('Cancelled') !== -1) {
          $(this).addClass('cancelled');
        }
        else if ($(this).text().indexOf('Scheduled') !== -1) {
          $(this).addClass('scheduled');
        }
        else if ($(this).text().indexOf('Landed') !== -1) {
          $(this).addClass('landed');
        }
      });
      $('.today').once('today').each(function () {
        let date = new Date();
        $(this).text(moment().format('DD MMM'));
      });
      $('.tomorrow').once('tomorrow').each(function () {
        let date = new Date();
        $(this).text(moment().add(1, 'days').format('DD MMM'));
      });

      let select_path = '#block-views-block-flight-finder-flight-finder .form-item:nth-child(1) .select-wrapper';

      $(select_path).once('check').each(function () {
        if (typeof ($.cookie('switsh')) == 'undefined') {
          $.cookie('switsh', 'from');
        }
        if ($.cookie('switsh') === 'from') {
          $(select_path + ' option:first').attr('selected', 'selected');
          $(select_path + ' option:last').removeAttr('selected');
          $(this).removeClass('to-from');
        }
        else {
          $(this).addClass('to-from');
          $(select_path + ' option:first').removeAttr('selected');
          $(select_path + ' option:last').attr('selected', 'selected');
        }
      });

      $('body ').on('click', select_path, function () {
        if ($(this).hasClass('to-from')) {
          $(select_path + ' option:first').attr('selected', 'selected');
          $(select_path + ' option:last').removeAttr('selected');
          $(this).removeClass('to-from');
          $.cookie('switsh', 'from');
        }
        else {
          $(this).addClass('to-from');
          $(select_path + ' option:first').removeAttr('selected');
          $(select_path + ' option:last').attr('selected', 'selected');
          $.cookie('switsh', 'to');
        }
      });
    },
  };
})(jQuery, Drupal);
