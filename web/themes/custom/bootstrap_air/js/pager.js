(function ($, Drupal) {

  Drupal.behaviors.statistics = {
    attach() {
      $('#go_to_page_link').once('go_page').on('click', go_page);

      function go_page() {
        let value_input = $('#go_to_page_link_input').val();
        let count_li = $('#pager_id_ul').children().length;
        if (value_input >= 1 && value_input <= count_li) {
          $('#pager_id_ul li:nth-child(' + value_input + ') a').click();
        }
        else {
          $('#go_to_page_link_input').val('');
        }
      }

      $('#go_to_page').once('submit_go_to_page').submit(function () {
        go_page();
        return false;
      });

    },
  };
})(jQuery, Drupal);
