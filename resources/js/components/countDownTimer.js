// Registrasi sebagai Alpine.data
export default function CountDownTimer({ countdown = 30 } = {}) {
   return {
      countdown,
      initialCountdown: countdown,
      timer: null,

      startTimer() {
         clearInterval(this.timer);
         this.timer = setInterval(() => {
            if (this.countdown > 0) {
               this.countdown--;
            } else {
               clearInterval(this.timer);
            }
         }, 1000);
      },

      resetTimer() {
         this.countdown = this.initialCountdown;
         this.startTimer();
      },
   };
}
