// resources/js/components/countDown.js

export default function CountDown({ expiry_date = null } = {}) {
    return {
        expiry_date,
        totalSeconds: 0,
        initialSeconds: 0,
        isPaused: false,
        intervalId: null,
        finished: false,

        init() {
            if (!this.expiry_date) {
                console.error("expiry_date is required for CountDown");
                return;
            }

            // Convert expiry_date ke timestamp
            const expiry = new Date(this.expiry_date).getTime();
            const now = Date.now();

            // Hitung selisih dalam detik
            let diff = Math.floor((expiry - now) / 1000);

            // Jika expire → 0
            this.initialSeconds = diff > 0 ? diff : 0;
            this.totalSeconds = this.initialSeconds;

            this.start();
        },

        // Format HH:MM:SS
        get timeString() {
            const h = Math.floor(this.totalSeconds / 3600);
            const m = Math.floor((this.totalSeconds % 3600) / 60);
            const s = this.totalSeconds % 60;

            return `${String(h).padStart(2,'0')} : ${String(m).padStart(2,'0')} : ${String(s).padStart(2,'0')}`;
        },

        start() {
            if (this.intervalId) return;

            this.intervalId = setInterval(() => {
                if (this.isPaused || this.totalSeconds <= 0) return;

                this.totalSeconds--;

                if (this.totalSeconds <= 0) {
                    this.finish();
                }
            }, 1000);
        },

        togglePause() {
            this.isPaused = !this.isPaused;
        },

        reset() {
            this.totalSeconds = this.initialSeconds;
            this.isPaused = false;
            this.finished = false;
        },

        finish() {
            this.finished = true;
            this.isPaused = true;

            clearInterval(this.intervalId);
            this.intervalId = null;

            this.totalSeconds = 0;
        }
    };
}
