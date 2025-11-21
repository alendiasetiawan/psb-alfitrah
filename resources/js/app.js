import Iodine from "@caneara/iodine";
import AlpineSwiper from "./components/swiper";

const iodine = new Iodine();

iodine.setErrorMessages({
  required: `Wajib diisi!`,
  minLength: `Minimal [PARAM] karakter`,
  maxLength: `Maksimal [PARAM] karakter`,
  min: `Minimal [PARAM] digit`,
  max: `Maksimal [PARAM] digit`,
  email: `Format email tidak valid`,
  url: `Format URL tidak valid`,
  regexMatch: `Format tidak valid`,
  startingWith: `Harus diawali dengan [PARAM]`,
  endingWith: `Harus diakhiri dengan [PARAM]`,
});

Alpine.data("swiperContainer", AlpineSwiper);

Alpine.data("formValidation", (rules) => ({
  form: {},
  errors: {},
  rules: rules,
  isSubmitActive: false,
  isModalLoading: false,
  isEditingMode: false,

  get isValid() {
    return Object.keys(this.errors).length === 0;
  },

  validate(field) {
    let value = this.form[field];
    let fieldRules = this.rules[field] ?? [];
    let result = iodine.assert(value, fieldRules);

    const filled = Object.keys(this.rules).every((f) => {
      return this.form[f] && this.form[f].toString().trim() !== "";
    });

    if (result.valid) {
      delete this.errors[field];
    } else {
      this.errors[field] = result.error;
    }

    if (this.isEditingMode) {
      this.isSubmitActive = this.isValid;
    } else {
      this.isSubmitActive = filled && this.isValid;
    }
  },
}));

//Script Pikaday
Alpine.data("datepicker", (params) => ({
  model: params.model || "",
  format: params.format || "YYYY-MM-DD",
  minDate: params.minDate || null,
  maxDate: params.maxDate || null,
  picker: null,

  initPicker() {
    const self = this;

    // Cari modal aktif di atas input (Flux/Alpine)
    const modal = this.$root.closest(
      "[role=dialog], .modal, [data-modal], [x-show]"
    );
    const containerTarget = modal || document.body;

    // Inisialisasi Pikaday
    this.picker = new Pikaday({
      field: this.$refs.input,
      format: this.format,
      i18n: {
        previousMonth: "Bulan Sebelumnya",
        nextMonth: "Bulan Berikutnya",
        months: [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ],
        weekdays: [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ],
        weekdaysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
      },
      firstDay: 1,
      minDate: this.minDate ? new Date(this.minDate) : null,
      maxDate: this.maxDate ? new Date(this.maxDate) : null,
      bound: true,
      reposition: false,
      container: containerTarget, // 💡 otomatis tempel ke modal terdekat
      onSelect: function (date) {
        self.model = moment(date).format(self.format);
        self.$refs.input.dispatchEvent(new Event("input", { bubbles: false }));
      },
      onOpen: function () {
        const el = this.el;
        const input = self.$refs.input;
        const modal = input.closest("[role=dialog], .modal, [data-modal]");

        if (modal && el) {
          const inputRect = input.getBoundingClientRect();
          const modalRect = modal.getBoundingClientRect();
          const pickerHeight = el.offsetHeight || 250; // fallback jika belum ter-render

          // Hitung ruang kosong di atas & bawah input
          const spaceAbove = inputRect.top - modalRect.top;
          const spaceBelow = modalRect.bottom - inputRect.bottom;

          el.style.position = "absolute";
          el.style.left = `${inputRect.left - modalRect.left}px`;

          // 💡 Tentukan posisi berdasarkan ruang yang tersedia
          if (spaceBelow >= pickerHeight + 8 || spaceBelow > spaceAbove) {
            // Cukup ruang di bawah ➜ tampil di bawah input
            el.style.top = `${inputRect.bottom - modalRect.top + 4}px`;
          } else {
            // Lebih banyak ruang di atas ➜ tampil di atas input
            el.style.top = `${
              inputRect.top - modalRect.top - pickerHeight - 4
            }px`;
          }

          // Pastikan selalu muncul di depan modal
          el.style.zIndex = "9999";
        } else if (el) {
          // fallback kalau tidak ada modal
          const inputRect = input.getBoundingClientRect();
          const pickerHeight = el.offsetHeight || 250;
          const spaceBelow = window.innerHeight - inputRect.bottom;
          const spaceAbove = inputRect.top;

          el.style.position = "absolute";
          el.style.left = `${inputRect.left}px`;

          if (spaceBelow >= pickerHeight + 8 || spaceBelow > spaceAbove) {
            el.style.top = `${inputRect.bottom + 4}px`;
          } else {
            el.style.top = `${inputRect.top - pickerHeight - 4}px`;
          }

          el.style.zIndex = "9999";
        }
      },
    });

    if (!modal) {
      this.picker.el.style.zIndex = "9999";
    }
  },
}));

//Script Countdown OTP
Alpine.data("countDownTimer", ({ countdown = 30 } = {}) => ({
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
}));
