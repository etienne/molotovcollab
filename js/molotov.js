jQuery(function($) {
  // Quick post widget
  $('li.widget_quick_post_widget h2').addClass('button').click(function() {
    if ($(this).hasClass('active')) {
      // Hide form
      $(this).removeClass('active');
      $(this).parent().find('div.quick_post_form').slideUp("fast");
    } else {
      // Show form
      $('li.widget_quick_post_widget h2').removeClass('active');
      $('li.widget_quick_post_widget div.quick_post_form').slideUp("fast");
      $(this).addClass('active');
      $(this).parent().find('div.quick_post_form').slideDown("fast");
    }
  });
  $('li.widget_quick_post_widget div.quick_post_form').hide();
  
  $('li.widget_quick_post_widget a.cancel').click(function() {
    $(this).closest('li.widget_quick_post_widget').find('h2.button').click();
    return false;
  });
  
  // Tabs
  $('ul.tabs a').each(function() {
    $(this).click(function() {
      $('.tab-panel').hide();
      $($(this).attr('href')).show();
      $('ul.tabs a').removeClass('selected');
      $(this).addClass('selected');
    })
    if ($(this).attr('href') == document.location.hash) {
      $(this).click();
    }
  })
  
})