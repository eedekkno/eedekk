// Tailkit (Tailwind CSS v3 Configuration)
import plugin from "tailwindcss/plugin";
import defaultTheme from "tailwindcss/defaultTheme";
import colors from "tailwindcss/colors";

import aspectRatio from "@tailwindcss/aspect-ratio";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Enums/*.php',
        "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
        "./vendor/livewire/flux/stubs/**/*.blade.php",
    ],
    darkMode: 'selector', // "selector",
    theme: {
        extend: {
            colors: {
                // Re-assign Flux's gray of choice...
                zinc: colors.gray,

                // Accent variables are defined in resources/css/app.css...
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    content: 'var(--color-accent-content)',
                    foreground: 'var(--color-accent-foreground)',
                },
            },
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                "8xl": "90rem",
                "9xl": "105rem",
                "10xl": "120rem",
            },
            zIndex: {
                1: 1,
                60: 60,
                70: 70,
                80: 80,
                90: 90,
                100: 100,
            },
            keyframes: {
                "spin-slow": {
                    "100%": {
                        transform: "rotate(-360deg)",
                    },
                },
            },
            animation: {
                "spin-slow": "spin-slow 8s linear infinite",
            },
            typography: {
                DEFAULT: {
                    css: {
                        a: {
                            textDecoration: "none",
                            "&:hover": {
                                opacity: ".75",
                            },
                        },
                        img: {
                            borderRadius: defaultTheme.borderRadius.lg,
                        },
                    },
                },
            },
        },
    },
    safelist: [
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl']
        }
    ],
    plugins: [
        aspectRatio,
        forms,
        typography,
        plugin(function ({addUtilities}) {
            const utilFormSwitch = {
                ".form-switch": {
                    border: "transparent",
                    "background-color": colors.gray[300],
                    "background-image":
                        "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e\")",
                    "background-position": "left center",
                    "background-repeat": "no-repeat",
                    "background-size": "contain !important",
                    "vertical-align": "top",
                    "&:checked": {
                        border: "transparent",
                        "background-color": "currentColor",
                        "background-image":
                            "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e\")",
                        "background-position": "right center",
                    },
                    "&:disabled, &:disabled + label": {
                        opacity: ".5",
                        cursor: "not-allowed",
                    },
                },
            };

            addUtilities(utilFormSwitch);
        }),
    ],
};
