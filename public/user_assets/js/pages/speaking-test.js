/**
 * Speaking Test Page - Recording Controls
 */

// Global variables for question navigation
let currentQuestionIndex = 1;
let totalQuestions = 0;
let recordingStates = {};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    totalQuestions = parseInt(document.querySelector('input[name="total_questions"]')?.value || 0);
    setupQuestionNavigation();
    setupRecordingControls();
    initializeAudioPlayers();
});

/**
 * Setup question navigation
 */
function setupQuestionNavigation() {
    const nextBtn = document.getElementById('nextQuestion');
    const prevBtn = document.getElementById('previousQuestion');

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentQuestionIndex < totalQuestions) {
                document.getElementById(`question-${currentQuestionIndex}`).classList.add('d-none');
                currentQuestionIndex++;
                document.getElementById(`question-${currentQuestionIndex}`).classList.remove('d-none');

                // Update navigation buttons
                prevBtn.classList.remove('d-none');
                if (currentQuestionIndex === totalQuestions) {
                    this.classList.add('d-none');
                }

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentQuestionIndex > 1) {
                document.getElementById(`question-${currentQuestionIndex}`).classList.add('d-none');
                currentQuestionIndex--;
                document.getElementById(`question-${currentQuestionIndex}`).classList.remove('d-none');

                // Update navigation buttons
                nextBtn.classList.remove('d-none');
                if (currentQuestionIndex === 1) {
                    this.classList.add('d-none');
                }

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }
}

/**
 * Initialize audio players
 */
function initializeAudioPlayers() {
    setTimeout(function() {
        // Initialize all audio players including existing recordings
        GreenAudioPlayer.init({
            selector: '.audio-player',
            stopOthersOnPlay: true
        });
        console.log('Audio players initialized');

        // Check if existing recording players were initialized
        const existingPlayers = document.querySelectorAll('.existing-recording-player');
        console.log('Found', existingPlayers.length, 'existing recording players');
    }, 500);
}

/**
 * Setup recording controls for all questions
 */
function setupRecordingControls() {
    const startButtons = document.querySelectorAll('.startRecordingBtn');

    startButtons.forEach(btn => {
        const questionId = btn.dataset.questionId;
        setupQuestionRecording(questionId);
    });

    // Setup re-record buttons
    const rerecordButtons = document.querySelectorAll('.rerecord-btn');
    rerecordButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            const existingContainer = document.getElementById(`existingRecording-${questionId}`);
            const recordingInitial = document.getElementById(`recordingInitial-${questionId}`);

            if (existingContainer) {
                existingContainer.style.display = 'none';
            }
            if (recordingInitial) {
                recordingInitial.style.display = 'flex';
            }
        });
    });
}

/**
 * Setup recording for a specific question
 */
function setupQuestionRecording(questionId) {
    const recordBtn = document.querySelector(`.startRecordingBtn[data-question-id="${questionId}"]`);
    const recordingInitial = document.getElementById(`recordingInitial-${questionId}`);
    const recordingControls = document.getElementById(`recordingControls-${questionId}`);
    const recordingActions = document.getElementById(`recordingActions-${questionId}`);
    const timer = document.getElementById(`timer-${questionId}`);
    const stopBtn = document.querySelector(`.stopRecordingBtn[data-question-id="${questionId}"]`);
    const playPauseBtn = document.querySelector(`.playPauseBtn[data-question-id="${questionId}"]`);
    const deleteBtn = document.querySelectorAll(`.deleteRecordingBtn[data-question-id="${questionId}"]`);
    const saveBtn = document.querySelector(`.saveRecordingBtn[data-question-id="${questionId}"]`);

    if (!recordBtn || !recordingControls) {
        console.error('Recording elements not found for question:', questionId);
        return;
    }

    // Initialize recording state
    recordingStates[questionId] = {
        isRecording: false,
        isPlaying: false,
        mediaRecorder: null,
        recordedChunks: [],
        recordedAudio: null,
        timerInterval: null,
        startTime: 0,
        recordingDuration: 0,
        blob: null
    };

    const state = recordingStates[questionId];

    // Update timer display
    const updateTimer = () => {
        const elapsed = Date.now() - state.startTime;
        const seconds = Math.floor(elapsed / 1000);
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    };

    // Create audio from chunks
    const createAudioFromChunks = () => {
        state.blob = new Blob(state.recordedChunks, { type: 'audio/webm' });
        const audioUrl = URL.createObjectURL(state.blob);
        state.recordedAudio = new Audio(audioUrl);
        return audioUrl;
    };

    // Start recording
    const startRecording = async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            state.mediaRecorder = new MediaRecorder(stream);
            state.recordedChunks = [];

            state.mediaRecorder.addEventListener('dataavailable', (event) => {
                if (event.data.size > 0) {
                    state.recordedChunks.push(event.data);
                }
            });

            state.mediaRecorder.addEventListener('stop', () => {
                console.log('Recording saved. Chunks:', state.recordedChunks.length);
                createAudioFromChunks();
                state.recordingDuration = Date.now() - state.startTime;
                console.log('Recording blob saved:', state.blob);
            });

            state.mediaRecorder.start();
            state.isRecording = true;

            // Update UI - show recording controls
            recordingInitial.style.display = 'none';
            recordingControls.style.display = 'flex';
            if (timer) timer.style.display = 'block';
            if (stopBtn) stopBtn.style.display = 'block';
            if (playPauseBtn) playPauseBtn.style.display = 'none';
            deleteBtn.forEach(btn => btn.style.display = 'none');

            // Start timer
            state.startTime = Date.now();
            timer.textContent = '0:00';
            state.timerInterval = setInterval(updateTimer, 100);

        } catch (error) {
            console.error('Error accessing microphone:', error);
            alert('Could not access microphone. Please check your permissions.');
        }
    };

    // Stop recording
    const stopRecording = () => {
        if (state.mediaRecorder && state.mediaRecorder.state !== 'inactive') {
            state.mediaRecorder.stop();
            state.mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
        state.isRecording = false;

        // Update UI - show playback controls
        if (stopBtn) stopBtn.style.display = 'none';
        if (playPauseBtn) playPauseBtn.style.display = 'block';
        deleteBtn.forEach(btn => btn.style.display = 'block');

        // Stop timer but keep it visible
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }

        // Show action buttons
        setTimeout(() => {
            if (recordingActions) {
                recordingActions.style.display = 'flex';
            }
        }, 300);
    };

    // Play/Pause recording
    const playPauseRecording = () => {
        if (!state.recordedAudio || state.recordedChunks.length === 0) {
            console.log('No recording to play');
            return;
        }

        if (state.isPlaying) {
            // Pause playback
            state.recordedAudio.pause();
            state.isPlaying = false;
            updatePlayPauseIcon(playPauseBtn, 'play');

            // Stop timer
            if (state.timerInterval) {
                clearInterval(state.timerInterval);
                state.timerInterval = null;
            }
        } else {
            // Start playback
            state.recordedAudio.play();
            state.isPlaying = true;
            updatePlayPauseIcon(playPauseBtn, 'pause');

            // Update timer during playback
            state.startTime = Date.now();
            state.timerInterval = setInterval(() => {
                const elapsed = Math.floor(state.recordedAudio.currentTime * 1000);
                const seconds = Math.floor(elapsed / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                timer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }, 100);

            // Handle playback end
            state.recordedAudio.onended = () => {
                state.isPlaying = false;
                updatePlayPauseIcon(playPauseBtn, 'play');

                if (state.timerInterval) {
                    clearInterval(state.timerInterval);
                    state.timerInterval = null;
                }

                // Reset timer to recording duration
                const seconds = Math.floor(state.recordingDuration / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                timer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            };
        }
    };

    // Delete recording
    const deleteRecording = () => {
        // Stop playback if playing
        if (state.isPlaying && state.recordedAudio) {
            state.recordedAudio.pause();
            state.recordedAudio.currentTime = 0;
            state.isPlaying = false;
        }

        // Clean up audio resources
        if (state.recordedAudio) {
            state.recordedAudio = null;
        }

        // Clear recorded chunks
        state.recordedChunks = [];
        state.recordingDuration = 0;
        state.blob = null;

        // Stop timer
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }

        // Reset UI - hide all recording controls, show initial button
        recordingControls.style.display = 'none';
        recordingInitial.style.display = 'flex';
        if (recordingActions) recordingActions.style.display = 'none';
        if (timer) {
            timer.textContent = '0:00';
            timer.style.display = 'none';
        }
        if (stopBtn) stopBtn.style.display = 'none';
        if (playPauseBtn) playPauseBtn.style.display = 'none';
        deleteBtn.forEach(btn => btn.style.display = 'none');
    };

    // Save recording
    const saveRecording = () => {
        if (!state.blob) {
            alert('No recording to save');
            return;
        }

        // Show loading state
        const btnText = saveBtn.querySelector('.btn-text');
        const btnSpinner = saveBtn.querySelector('.btn-spinner');
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'flex';
        saveBtn.disabled = true;

        // Show loading dialog
        const loadingDialog = document.getElementById('loadingDialog');
        if (loadingDialog) {
            loadingDialog.style.display = 'flex';
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('question_id', questionId);
        formData.append('record', state.blob, 'recording.wav');
        formData.append('start_at', document.querySelector('input[name="start_at"]').value);

        // Get save URL from form action
        const form = document.getElementById('speaking_test_form');
        const saveUrl = form ? form.action : '';

        if (!saveUrl) {
            console.error('Save URL not found');
            alert('Error: Save URL not configured');
            return;
        }

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: saveUrl,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log('Success:', data);

                // Hide loading dialog
                if (loadingDialog) {
                    loadingDialog.style.display = 'none';
                }

                // Reset button state
                if (btnText) btnText.style.display = 'block';
                if (btnSpinner) btnSpinner.style.display = 'none';
                saveBtn.disabled = false;

                // Show success dialog
                const successDialog = document.getElementById('successDialog');
                const successText = document.getElementById('successText');
                if (successDialog) {
                    if (successText && data.message) {
                        successText.textContent = data.message;
                    }
                    successDialog.style.display = 'flex';
                } else if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Recording saved successfully');
                }

                // Move to next question or redirect
                setTimeout(function() {
                    if (currentQuestionIndex < totalQuestions) {
                        document.getElementById('nextQuestion').click();
                    } else {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // Get redirect URL from data attribute or default
                            const form = document.getElementById('speaking_test_form');
                            const defaultRedirect = form?.dataset.redirectUrl || '/';
                            window.location.href = defaultRedirect;
                        }
                    }
                }, 2000);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);

                // Hide loading dialog
                if (loadingDialog) {
                    loadingDialog.style.display = 'none';
                }

                // Reset button state
                if (btnText) btnText.style.display = 'block';
                if (btnSpinner) btnSpinner.style.display = 'none';
                saveBtn.disabled = false;

                // Show error message
                let errorMsg = 'Failed to save recording. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            }
        });
    };

    // Event listeners
    if (recordBtn) {
        recordBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            await startRecording();
        });
    }

    if (stopBtn) {
        stopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            stopRecording();
        });
    }

    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            playPauseRecording();
        });
    }

    if (deleteBtn) {
        deleteBtn.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteRecording();
            });
        });
    }

    if (saveBtn) {
        saveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            saveRecording();
        });
    }
}

/**
 * Update play/pause icon
 */
function updatePlayPauseIcon(button, type) {
    if (!button) return;

    if (type === 'play') {
        button.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5V19L19 12L8 5Z" fill="white"/>
            </svg>
        `;
    } else {
        button.innerHTML = `
            <svg viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" height="24" width="24">
                <path d="M11.5 35.266666666666666V10.733333333333333c0 -0.6351258333333334 0.5148741666666666 -1.15 1.15 -1.15h5.366666666666666c0.6351258333333334 0 1.15 0.5148741666666666 1.15 1.15v24.533333333333335c0 0.6351833333333333 -0.5148741666666666 1.15 -1.15 1.15H12.65c-0.6351258333333334 0 -1.15 -0.5148166666666667 -1.15 -1.15Z" fill="#ffffff" stroke="#ffffff" stroke-width="2.875"></path>
                <path d="M26.833333333333336 35.266666666666666V10.733333333333333c0 -0.6351258333333334 0.5148166666666667 -1.15 1.15 -1.15h5.366666666666666c0.6351833333333333 0 1.15 0.5148741666666666 1.15 1.15v24.533333333333335c0 0.6351833333333333 -0.5148166666666667 1.15 -1.15 1.15h-5.366666666666666c-0.6351833333333333 0 -1.15 -0.5148166666666667 -1.15 -1.15Z" fill="#ffffff" stroke="#ffffff" stroke-width="2.875"></path>
            </svg>
        `;
    }
}

/**
 * OK Button Handler for success dialog
 */
document.addEventListener('DOMContentLoaded', function() {
    const okBtn = document.getElementById('okBtn');
    if (okBtn) {
        okBtn.addEventListener('click', function() {
            const successDialog = document.getElementById('successDialog');
            if (successDialog) {
                successDialog.style.display = 'none';
            }
        });
    }
});
