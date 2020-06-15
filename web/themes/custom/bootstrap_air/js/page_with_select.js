(function ($, Drupal) {
  Drupal.behaviors.pageWithSelect = {
    attach() {
      $('#select_list').on('change', function () {
        $(this.value)[0].scrollIntoView({block: 'start', behavior: 'smooth'});
      });

      $('.link-to-top').click(function () {
        $('body,html').animate({
          scrollTop: 0
        }, 400);
        return false;
      });

      $('td.views-field-field-days-of-the-week ul').once('days').each(function () {
        let a = $(this).children();
        let weekday = [Drupal.t('Sunday'), Drupal.t('Monday'), Drupal.t('Tuesday'),
          Drupal.t('Wednesday'), Drupal.t('Thursday'), Drupal.t('Friday'), Drupal.t('Saturday')];
        let active = [];
        let inner = '';
        for (let i = 0; i < a.length; i++) {
          active.push(a[i].innerHTML);
        }
        let j = 0;
        for (let i = 0; i < weekday.length; i++) {
          inner += '<li';
          if (typeof (active[j]) !== 'undefined' && weekday[i] === active[j]) {
            inner += " class='active'";
            j++;
          }
          inner += '>' + weekday[i][0] + '</li>';
        }
        this.innerHTML = inner;
      });
    },
  };
})(jQuery, Drupal);
