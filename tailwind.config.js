/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "var(--primary)",
                "primary-50": "var(--primary-50)",
                "primary-100": "var(--primary-100)",
                "primary-200": "var(--primary-200)",
                "primary-300": "var(--primary-300)",
                "primary-400": "var(--primary-400)",
                "primary-500": "var(--primary-500)",
                "primary-600": "var(--primary-600)",
                "primary-700": "var(--primary-700)",
                "primary-800": "var(--primary-800)",
                "primary-900": "var(--primary-900)",
                "primary-950": "var(--primary-950)",
                "error": "var(--color-error)",
                "warning": "var(--color-warning)",
                "info": "var(--color-info)",
                "success": "var(--color-success)",
                "black": "var(--color-black)"
            },
            screens: {
                xs: "375px",
                sm2: "712px",
                'm-xl': "1440px",
                '3xl': "2560px"
            },
            typography: {
                DEFAULT: {
                    css: {
                        p: {
                            marginTop: "0.5rem",
                            marginBottom: "0.5rem",
                        },
                        img: {
                            marginTop: "0.5rem",
                            marginBottom: "0.5rem",
                        }
                    },
                },
            },
        },
    },
    plugins: [require("@tailwindcss/typography")],
};
