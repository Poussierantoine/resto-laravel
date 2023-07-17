import './bootstrap';

import Carousel from './carousel.js';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();


window.onload = function () {
    let carousel = document.querySelector(".carousel");
    if(carousel){
        new Carousel(carousel, {
            slidesToScroll: 2,
            slidesVisible: 2,
            loop: false,
            leftIcon: '../images/icons/chevron-left.svg',
            rightIcon: '../images/icons/chevron-right.svg',
          });
    }
  };


  
