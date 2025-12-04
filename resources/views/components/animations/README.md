# Lottie Animation Component Usage

## Overview

The Lottie animation component allows you to easily display Lottie JSON animations in your Blade templates.

## Basic Usage

### Using the Generic Lottie Component

```blade
<x-animations.lottie
    src="images/your-animation.json"
    width="400"
    height="400"
/>
```

### Using the Not Found Component

```blade
<x-animations.not-found />
```

## Available Props

| Prop       | Type    | Default | Description                                              |
| ---------- | ------- | ------- | -------------------------------------------------------- |
| `src`      | string  | null    | Path to the Lottie JSON file (relative to public folder) |
| `width`    | string  | '300'   | Width of the animation in pixels                         |
| `height`   | string  | '300'   | Height of the animation in pixels                        |
| `loop`     | boolean | true    | Whether the animation should loop                        |
| `autoplay` | boolean | true    | Whether the animation should start automatically         |

## Examples

### Custom Size

```blade
<x-animations.lottie
    src="images/loading.json"
    width="200"
    height="200"
/>
```

### No Loop, Manual Play

```blade
<x-animations.lottie
    src="images/success.json"
    width="150"
    height="150"
    :loop="false"
    :autoplay="false"
/>
```

### With Custom Classes

```blade
<x-animations.lottie
    src="images/not-found.json"
    width="300"
    height="300"
    class="my-4 p-4"
/>
```

## Creating New Animation Components

To create a new specific animation component (like `not-found`):

1. Create a new file in `resources/views/components/animations/`
2. Use the generic lottie component:

```blade
<x-animations.lottie
    src="images/your-animation.json"
    width="300"
    height="300"
    :loop="true"
    :autoplay="true"
/>
```

3. Use it anywhere:

```blade
<x-animations.your-animation />
```

## Technical Details

- Uses **lottie-web** library (v5.12.2) from CDN
- Integrates with **Alpine.js** for reactivity
- Automatically loads scripts only once using `@once` directive
- Renders animations as SVG for best quality
- Properly cleans up animations on component destroy

## Requirements

- Alpine.js must be loaded in your layout
- `@stack('scripts')` must be present in your layout file (already added to mobile-app.blade.php)
