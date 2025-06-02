<div>
    <label class="block text-sm font-medium text-gray-700">Kamera</label>
    <div>
        <button type="button" id="capture" class="px-4 py-2 shadow hover:bg-indigo-700">
            Capture
        </button>
        <button type="button" id="retake" class="px-4 py-2 shadow hover:bg-gray-700 hidden">
            Retake
        </button>
    </div>
    <div class="mt-2 relative w-64 h-64 border rounded-md">
        <!-- Video Stream -->
        <video id="camera" autoplay playsinline class="absolute inset-0 w-100 h-100 object-cover rounded-md"></video>
        <!-- Canvas for Captured Image -->
        <canvas id="canvas" class="hidden"></canvas>
        <img id="photo-preview" src="" alt="Captured Image"
            class="w-64 h-64 rounded-md object-cover border hidden">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const retakeButton = document.getElementById('retake');
        const submitButton = document.getElementsByClassName('fi-btn')[0];
        const photoPreview = document.getElementById('photo-preview');

        submitButton.classList.add('hidden'); // Show the retake button

        // Start the live camera feed
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                video.srcObject = stream; // Attach the stream to the video element
            })
            .catch((error) => {
                console.error('Error accessing camera:', error);
            });

        // Capture the current frame from the live feed
        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Stop the live feed
            const stream = video.srcObject;
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop()); // Stop all tracks

            // Convert the canvas to a data URL and display it
            const dataUrl = canvas.toDataURL('image/png');
            photoPreview.src = dataUrl;
            photoPreview.classList.remove('hidden'); // Show the preview
            video.classList.add('hidden'); // Hide the live feed
            captureButton.classList.add('hidden'); // Hide the live feed
            retakeButton.classList.remove('hidden'); // Show the retake button
            retakeButton.classList.remove('hidden'); // Show the retake button
            submitButton.classList.remove('hidden'); // Show the retake button
        });

        // Retake the photo (restart the camera feed)
        retakeButton.addEventListener('click', () => {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then((stream) => {
                    video.srcObject = stream;
                    video.classList.remove('hidden'); // Show the live feed
                    photoPreview.classList.add('hidden'); // Hide the captured image
                    retakeButton.classList.add('hidden'); // Hide the retake button
                    captureButton.classList.remove('hidden'); // Show the retake button
                    submitButton.classList.add('hidden'); // Show the retake button
                })
                .catch((error) => {
                    console.error('Error accessing camera:', error);
                });
        });
    });
</script>
