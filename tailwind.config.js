const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        fontSize: {
            mc: "0.6rem",
            xs: "0.7rem",
            sm: "0.8rem",
            base: "1rem",
            xl: "1.25rem",
            "2xl": "1.563rem",
            "3xl": "1.953rem",
            "4xl": "2.441rem",
            "5xl": "3.052rem",
        },
        borderWidth: {
            DEFAULT: "1px",
            0: "0",
            1: "1px",
            2: "2px",
            3: "3px",
            4: "4px",
            6: "6px",
            8: "8px",
        },

        extend: {
            colors: {
                vino: {
                    50: "#c18c95",
                    100: "#b47580",
                    200: "#a85e6b",
                    300: "#9b4656",
                    400: "#8f2f41",
                    500: "#83192c",
                    600: "#751627",
                    700: "#681423",
                    800: "#5b111e",
                    900: "#4e0f1a",
                },
            },

            spacing: {
                100: "25rem",
                120: "30rem",
                128: "32rem",
                140: "35rem",
                144: "36rem",
                160: "40rem",
                224: "56rem",
            },

            borderWidth: {
                10: "10px",
                12: "12px",
            },

            animation: {
                ping1: "ping 0.8s cubic-bezier(0, 0, 0.2, 1) infinite",
                ping2: "ping 1s cubic-bezier(0, 0, 0.2, 1) infinite",
                ping3: "ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite",
                ping4: "ping 1.8s cubic-bezier(0, 0, 0.2, 1) infinite",
                ping5: "ping 2s cubic-bezier(0, 0, 0.2, 1) infinite",
                ping10: "ping 5s cubic-bezier(0, 0, 0.2, 1) infinite",

                ping20: "ping 2s cubic-bezier(0, 0, 0.2, 1) ",

                pulseA: "pulse 4s infinite",
                pulseB: "pulse 4.2s infinite",
                pulseC: "pulse 4.4s infinite",
                pulseD: "pulse 4.6s infinite",
                pulseE: "pulse 4.8s infinite",
                pulseF: "pulse 5s infinite",
            },
        },
        rotate: {
            0: "0deg",
            6: "6deg",
            12: "12deg",
            30: "30deg",
            90: "90deg",
        },

       
    },

    plugins: [require('@tailwindcss/forms')],
};
