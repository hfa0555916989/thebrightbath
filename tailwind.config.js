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
                brand: {
                    // Primary Colors - Based on the Bright Path identity
                    light: '#f7fafc',
                    DEFAULT: '#1F3A63',      // Deep Blue - Primary
                    dark: '#162032',          // Navy - Darker
                    
                    // Gold/Yellow Accent Colors
                    gold: '#F8C524',          // Main Gold
                    goldDeep: '#E5A91F',      // Deeper Gold
                    goldLight: '#FFD96A',     // Light Gold
                    
                    // Orange Accent Colors
                    orange: '#F28C28',        // Main Orange
                    orangeDark: '#E56A1F',    // Darker Orange
                    
                    // Blue Variations
                    blue: '#1F3A63',          // Same as DEFAULT
                    blueLight: '#3B5B89',     // Lighter Blue
                    navy: '#162032',          // Same as dark
                    
                    // Utility Colors
                    red: '#D94235',           // Error/Alert Red
                    accent: '#e2e8f0',        // Light Accent
                    
                    // Background & Surface Colors
                    bg: '#F5F7FA',            // Page Background
                    card: '#FFFFFF',          // Card Background
                    border: '#E0E6ED',        // Border Color
                    
                    // Text Colors
                    text: '#1F2933',          // Primary Text
                    textMuted: '#6B7280',     // Muted Text
                },
            },
            fontFamily: {
                sans: ['Tajawal', 'sans-serif'],
                display: ['Noto Kufi Arabic', 'sans-serif'],
                serif: ['Amiri', 'serif'],
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out',
                'fade-in-right': 'fadeInRight 0.8s ease-out',
                'scale-up': 'scaleUp 0.5s ease-out',
                'float': 'float 3s ease-in-out infinite',
                'pulse-gold': 'pulseGold 2s infinite',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleUp: {
                    '0%': { opacity: '0', transform: 'scale(0.9)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                pulseGold: {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(248, 197, 36, 0.4)' },
                    '50%': { boxShadow: '0 0 0 15px rgba(248, 197, 36, 0)' },
                },
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'elevated': '0 10px 40px -15px rgba(0, 0, 0, 0.2)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
        },
    },
    plugins: [],
};
