(function () {
    'use strict';

    document.addEventListener('click', function (event) {
       var element = event.target || event.srcElement;
       var parentElement = element.parentElement;
       var tagName = element.tagName.toUpperCase();

       if (tagName === 'H3' && parentElement && parentElement.classList.contains('c-faq__question')) {
           parentElement.classList.toggle('c-faq__question--active');
       }
    });
})();