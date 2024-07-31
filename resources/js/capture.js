import captureWebsite from 'capture-website';

const url = process.argv[2];
const outputPath = process.argv[3];

(async () => {
    try {
        await captureWebsite.file(url, outputPath, {
            fullPage: false, // Set to false since we're only capturing a specific element
            overwrite: true,
            launchOptions: {
                args: ['--no-sandbox', '--disable-setuid-sandbox', '--ignore-certificate-errors'],
                ignoreHTTPSErrors: true
            },
            mediaFeatures: [
                {
                    name: 'prefers-color-scheme',
                    value: 'dark'
                }
            ],
            element: '#docs-card' // Specify the CSS selector for the element to capture
        });
        console.log('Screenshot captured successfully');
    } catch (error) {
        console.error('Error capturing screenshot:', error);
    }
})();
