module.exports = {
     content: [    
        './src/views/**/*.twig',
        './src/views/*.twig',
        './src/**/*.{js,jsx,ts,tsx}',
        './*.php',
    ],
    theme: {
        extend: {}
    },
    daisyui: {
        themes: ['dark'],
    },
    plugins: [
        require('daisyui'),
        require('@tailwindcss/typography')
    ]
}