import Iodine from "@caneara/iodine";
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

// Registrasi sebagai Alpine.data
export default function FormValidation(rules) {

   return {
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
   };
}
