// recorder.js

let mediaRecorder;
let audioChunks = [];

$(document).ready(function() {
    $('#record').on('mousedown', async () => {
        // Start recording
        let stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);

        mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
        };

        mediaRecorder.onstop = () => {
            let audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
            let formData = new FormData();
            formData.append('audio', audioBlob);

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.filePath) {
                        saveRecordingInfo(response.filePath);
                    } else {
                        console.error('File path not found in response');
                    }
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    console.error('Upload failed: ' + errorMessage);
                }
            });

            audioChunks = [];
        };

        mediaRecorder.start();
        $('#record').text('Recording...').prop('disabled', true);
    });

    $('#record').on('mouseup mouseleave', () => {
        // Stop recording
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            $('#record').text('Record').prop('disabled', false);
        }
    });

    function saveRecordingInfo(filePath) {
        let name = $('#name').val();
        if (name) {
            $.ajax({
                url: 'save_info.php',
                type: 'POST',
                data: { name: name, filePath: filePath },
                success: function(response) {
                    console.log(response);
                    loadLatestRecording();
                },
                error: function(jqXHR, textStatus, errorMessage) {
                    console.error('Save info failed: ' + errorMessage);
                }
            });
        } else {
            alert('Please enter your name.');
        }
    }

    function loadRecordings() {
        $.ajax({
            url: 'load_recordings.php',
            type: 'GET',
            success: function(response) {
                $('#recordedFiles').html(response);
                addAudioEventHandlers();
            },
            error: function(jqXHR, textStatus, errorMessage) {
                console.error('Load recordings failed: ' + errorMessage);
            }
        });
    }

    function loadLatestRecording() {
        $.ajax({
            url: 'load_recordings.php',
            type: 'GET',
            data: { latest: 1 },
            success: function(response) {
                $('#recordedFiles').append(response);
                addAudioEventHandlers();
            },
            error: function(jqXHR, textStatus, errorMessage) {
                console.error('Load latest recording failed: ' + errorMessage);
            }
        });
    }

    function addAudioEventHandlers() {
        $('audio').on('mouseenter', function() {
            this.play();
        }).on('mouseleave', function() {
            this.pause();
            this.currentTime = 0;
        });
    }

    // Load recordings on page load
    loadRecordings();
});
