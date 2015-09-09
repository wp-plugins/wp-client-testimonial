jQuery(function(){
  // vars for testimonials carousel
  var $txtcarousel = jQuery('#testimonial-list');
  var txtcount = $txtcarousel.children().length;
  var wrapwidth = (txtcount * 415) + 415; // 400px width for each testimonial item
  $txtcarousel.css('width',wrapwidth);
  var animtime = 750; // milliseconds for clients carousel
 
  // prev & next btns for testimonials
  jQuery('#prv-testimonial').on('click', function(){
    var $last = jQuery('#testimonial-list li:last');
    $last.remove().css({ 'margin-left': '-415px' });
    jQuery('#testimonial-list li:first').before($last);
    $last.animate({ 'margin-left': '0px' }, animtime); 
  });
  
  jQuery('#nxt-testimonial').on('click', function(){
    var $first = jQuery('#testimonial-list li:first');
    $first.animate({ 'margin-left': '-415px' }, animtime, function() {
      $first.remove().css({ 'margin-left': '0px' });
      jQuery('#testimonial-list li:last').after($first);
    });  
  });

});