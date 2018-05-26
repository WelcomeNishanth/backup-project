$(document).ready(function() {
  console.log('Jquery Ready');
  $('.noaccordion').on('click', function (e) {
      e.stopPropagation();
  });
  /*Shipping-Rates---
  */
  $('.shipping--rate__bg').click(function() {
    $('.shipping--rate__bg___active').toggleClass('shipping--rate__bg___active');
      $(this).toggleClass('shipping--rate__bg___active');
  });
  $('a.step--one__btn').click(function() {
      $('#step--one').addClass("display--none");
      $('#step--two').addClass("display--block");
  });
});
$(document).ready(function() {
  $("#countries").msDropdown();
  $("#countries2").msDropdown();
})
// function to set the height on fly
function autoHeight() {
  $('.bd-gateway').css('min-height', 0);
  $('.bd-gateway').css('min-height', (
    $(document).height()
    - $('.bd-gateway').height()
    - $('.gateway--footer').height()
  ));
}
// onDocumentReady function bind
$(document).ready(function() {
  autoHeight();
});
// onResize bind of the function
$(window).resize(function() {
  autoHeight();
});
