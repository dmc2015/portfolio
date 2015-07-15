// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

mediaCheck({
  media: '(max-width: 901px)',
  entry: function() {
    $(document).ready(function(){
      $('.slideshow').slick({
        dots: true,
        cssEase: 'linear',
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        centerPadding: '40px',
        arrows: true,
        variableWidth: false,
        // adaptiveHeight: true
      });


      console.log('starting ')

    })
    // exit: function(min-width 901px) {
    //   console.log('exiting')
    // }

  })
})
