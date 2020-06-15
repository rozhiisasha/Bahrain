/**
 * @file
 * @type {{attach: Drupal.behaviors.tabsFlight.attach}}
 */
(function ($, Drupal) {
  Drupal.behaviors.tabsFlight = {
    attach: function () {
      $('.button').click(function () {
        $('#edit-arrivals').attr("disabled", false);
        $('#edit-departures').attr("disabled", false);
        $('table').toggleClass('active no-active');
        $('.form_tabs').children($('form')).toggleClass('active no-active');
        $('button').toggleClass('active-btn no-active-btn');
      })
    }
  };
})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.checkBah = {
    attach: function () {
      $('label[for=edit-field-in-bahreyn-value-2]').addClass('label_act');
      $('label').click(function () {
        if (String(this.htmlFor) === 'edit-field-in-bahreyn-value-2') {
          $('label[for=edit-field-in-bahreyn-value-2]').addClass('label_act');
          $('label[for=edit-field-in-bahreyn-value-1]').removeClass('label_act');
          $('#edit-field-in-bahreyn-value-2').prop('checked',false);
          $('#edit-field-in-bahreyn-value-1').prop('checked', true);
        }
        else if (String(this.htmlFor) === 'edit-field-in-bahreyn-value-1') {
          $('label[for=edit-field-in-bahreyn-value-2]').removeClass('label_act');
          $('label[for=edit-field-in-bahreyn-value-1]').addClass('label_act');
          $('#edit-field-in-bahreyn-value-1').prop('checked',false);
          $('#edit-field-in-bahreyn-value-2').prop('checked', true);
        }
      })
    }
  };
})(jQuery, Drupal);

(function ($, Drupal) {
  Drupal.behaviors.selectContactUs = {
    attach: function () {
      $('#edit-processed-text').once().append('<div id="department">'+ $('.select-wrapper').children('select').children('option[selected]').text()+'</div>');
      $('#edit-contact-us').change(function () {
        let a = $('#edit-contact-us').val();
        let b = $("option[value='"+a+"']").text();
        $('.select-wrapper').children('select').children('option[selected]').removeAttr('selected');
        $("option[value='"+a+"']").attr('selected', true);
        $("#department").replaceWith('<div id="department">'+b+'</div>')
      })
    }
  };
})(jQuery, Drupal);
(function ($, Drupal) {
  Drupal.behaviors.reinitSlider = {
    attach: function () {
      $(window).resize( function() {
        $('.slick__slider').slick('reinit');
      })
    }
  };
})(jQuery, Drupal);
