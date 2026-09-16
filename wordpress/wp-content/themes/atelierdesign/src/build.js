import "./fonts.scss";
import "./build.scss";



import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';

Swiper.use([Navigation]);

// Make Swiper and Navigation available globally for inline scripts
window.Swiper = Swiper;
window.SwiperNavigation = Navigation;

// Initialize other JS components if needed

import "./scripts/lenis.js";
import './components/header/header.js';
import './components/contact-others/contact-others.js';


