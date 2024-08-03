import captureWebsite from 'capture-website';

const url = process.argv[2];
const outputPath = process.argv[3];
const selector = process.argv[4]; // Add this line to accept the selector as an argument

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
            element: selector // Use the selector argument here
        });
        console.log('Screenshot captured successfully');
    } catch (error) {
        console.error('Error capturing screenshot:', error);
    }
})();
