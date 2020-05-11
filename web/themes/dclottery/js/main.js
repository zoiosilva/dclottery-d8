// Custom scripts file

(function ($) {

  'use strict';


  $(document).ready(function() {
    initialize_scrollbarClass();
  });


  
  $(window).on('load', function() {
    $('body').addClass('document-ready');
  });



  // this adds a scrollbar- class to
  // body which is used by the layout-breakout()
  // sass mixin.
  function initialize_scrollbarClass() {
    var $body = $('body');

    // Get the scrollbar width
    var scrollBarWidth = getScrollbarWidth();
    var scrollBarWidthClass = "scrollbar-zero";


    if (scrollBarWidth == 5) {
      // Firefox style
      scrollBarWidthClass = "scrollbar-5"
    }
    else if (scrollBarWidth == 12) {
      // Edge style
      scrollBarWidthClass = "scrollbar-12"
    }
    else if (scrollBarWidth == 15) {
      // Chrome style
      scrollBarWidthClass = "scrollbar-15"
    }
    else if (scrollBarWidth == 17) {
      // IE11 style
      scrollBarWidthClass = "scrollbar-17"
    }

    $body.addClass(scrollBarWidthClass);


    function getScrollbarWidth() {
      // Creating invisible container
      const outer = document.createElement('div');
      outer.style.visibility = 'hidden';
      outer.style.overflow = 'scroll'; // forcing scrollbar to appear
      outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps
      document.body.appendChild(outer);
    
      // Creating inner element and placing it in the container
      const inner = document.createElement('div');
      outer.appendChild(inner);
    
      // Calculating difference between container's full width and the child width
      const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);
    
      // Removing temporary elements from the DOM
      outer.parentNode.removeChild(outer);
    
      return scrollbarWidth;
    }
  }


})(jQuery);
