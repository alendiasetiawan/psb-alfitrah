import AlpineSwiper from "./components/swiper";
import FormValidation from "./components/formValidation";
import CountDownTimer from "./components/countDownTimer";
import PikadayDatePicker from "./components/datePicker";
import intersect from './components/intersect';

//Swiper
Alpine.data("swiperContainer", AlpineSwiper);

//CSR Form Validation
Alpine.data('formValidation', FormValidation);

//Countdown Timer
Alpine.data('countDownTimer', CountDownTimer);

//Script Pikaday
Alpine.data("datepicker", PikadayDatePicker);

//Action On Viewport
Alpine.plugin(intersect)
