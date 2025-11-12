// tailwind.config.js
import { defineConfig } from 'tailwindcss'

export default defineConfig({
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.ts',
    './resources/js/**/*.vue',
    './resources/js/**/*.tsx',
    './resources/js/**/*.jsx',

    // flux UI stubs
    './vendor/livewire/flux/stubs/**/*.blade.php',
    './vendor/livewire/flux-pro/stubs/**/*.blade.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
})
