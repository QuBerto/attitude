import captureWebsite from 'capture-website';

const url = process.argv[2];
const outputPath = process.argv[3];

(async () => {
    try {
        await captureWebsite.file(url, outputPath, {
            fullPage: true,
            overwrite: true
        });
        console.log('Screenshot captured successfully');
    } catch (error) {
        console.error('Error capturing screenshot:', error);
    }
})();
