/**
 * Lesson Page - Interactive Controls Only
 * All content is now static in HTML
 */

// Global variable to store recorded audio blob for form submission
let globalRecordedAudioBlob = null;
let globalReadRecordedBlob = null;

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
  setupTabSwitching();
  setupFooterButtons();
  setupRecordingControls();
  setupTracking();

  // Initialize audio players on page load
  setTimeout(function() {
    console.log('Initializing audio players on page load');
    setupAudioControls();
  }, 500);
});

/**
 * Setup tab switching functionality
 */
function setupTabSwitching() {
  const tabs = document.querySelectorAll('.lesson-tab');
  const sections = {
      0: document.getElementById('readSection'),
      1: document.getElementById('certifiedSection'),

  };
  const lessonContent = document.getElementById('lessonContent');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const saveBtn = document.getElementById('saveBtn');

    setupAudioControls();

    tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
      // Update tab states
      tabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');

      // Fade out current content
      lessonContent.classList.remove('show');

      setTimeout(() => {
        // Hide all sections
        Object.values(sections).forEach(section => {
          if (section) section.style.display = 'none';
        });

        // Show selected section
        if (sections[index]) {
          sections[index].style.display = 'block';
        }

        // Update Previous button visibility
        if (prevBtn) {
          prevBtn.style.display = index === 0 ? 'none' : 'block';
        }

        // Update footer button visibility
        if (index === 2) { // Answer section
            if (saveBtn){
                saveBtn.style.display = 'block';
            }
          // Hide Next button on Answer section
          if (nextBtn) {
            nextBtn.style.display = 'none';
          }
          setupRecordingControls();
          // Initialize audio players for Answer section (teacher feedback, student recordings)
          setupAudioControls();
        } else {
            if (saveBtn){
                saveBtn.style.display = 'none';
            }
          // Show Next button on other sections if not last tab
          if (nextBtn) {
            nextBtn.style.display = index < tabs.length - 1 ? 'block' : 'none';
          }

          // Setup audio controls for Read section (index 0)
          if (index === 0) {
            console.log('Read section active - initializing audio controls');
            setupAudioControls();
            setupRecordingControls();
          }

          // Setup audio controls for Certified section (index 1)
          if (index === 1) {
            setupAudioControls();
          }

          // Setup answer controls for Answer section
          if (index === 4) {
          }
        }

        // Trigger reflow and fade in
        lessonContent.offsetHeight;
        lessonContent.classList.add('show');
      }, 400);
    });
  });
}

/**
 * Setup footer buttons
 */
function setupFooterButtons() {
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const saveBtn = document.getElementById('saveBtn');
  const tabs = document.querySelectorAll('.lesson-tab');

  // Next button - move to next tab
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      const activeTab = document.querySelector('.lesson-tab.active');
      const currentIndex = activeTab ? parseInt(activeTab.getAttribute('data-section-index')) : 0;
      const nextIndex = currentIndex + 1;

      // If there's a next tab, click it
      if (nextIndex < tabs.length) {
        tabs[nextIndex].click();
      }
    });
  }

  // Previous button - move to previous tab
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      const activeTab = document.querySelector('.lesson-tab.active');
      const currentIndex = activeTab ? parseInt(activeTab.getAttribute('data-section-index')) : 0;
      const prevIndex = currentIndex - 1;

      // If there's a previous tab, click it
      if (prevIndex >= 0) {
        tabs[prevIndex].click();
      }
    });
  }

  if (saveBtn) {
    saveBtn.addEventListener('click', (e) => {
      e.preventDefault();

      // Get lesson ID from hidden input
      const lessonForm = document.getElementById('lesson_form');
      if (!lessonForm) {
        console.error('Lesson form not found');
        return;
      }

      // Use the save URL defined in the blade file
      const saveUrl = typeof LESSON_SAVE_URL !== 'undefined' ? LESSON_SAVE_URL : null;

      if (!saveUrl) {
        console.error('Save URL not defined');
        if (typeof showMessageDialog !== 'undefined') {
          showMessageDialog('Save URL not configured', 'error');
        } else {
          alert('Error: Save URL not configured');
        }
        return;
      }

      // Create FormData from the form
      const formData = new FormData(lessonForm);

      // Add the audio recording if it exists
      if (globalRecordedAudioBlob) {
        const timestamp = new Date().getTime();
        formData.append('record_file', globalRecordedAudioBlob, `recording_${timestamp}.webm`);
        console.log('Audio blob added to form data');
      }

      // Show loading dialog
      const loadingDialog = document.getElementById('loadingDialog');
      if (loadingDialog) {
        loadingDialog.style.display = 'flex';
      }

      // Show progress bar
      const progressContainer = document.querySelector('.form-status-container');
      const progressBar = document.querySelector('.progress-bar');
      const progressPercent = document.querySelector('.progress-percent');
      const message = document.getElementById('message');

      if (progressContainer) {
        progressContainer.style.display = 'block';
      }

      // Send AJAX request
      $.ajax({
        url: saveUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
          const xhr = $.ajaxSettings.xhr();
          // Upload progress
          xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
              const percentComplete = Math.ceil((e.loaded / e.total) * 100);
              if (progressBar) {
                progressBar.style.width = percentComplete + '%';
              }
              if (progressPercent) {
                progressPercent.textContent = percentComplete + '%';
              }
            }
          };
          return xhr;
        },
        success: function(response) {
          console.log('Success:', response);

          // Hide loading dialog
          if (loadingDialog) {
            loadingDialog.style.display = 'none';
          }

          // Show success dialog
          const successDialog = document.getElementById('successDialog');
          const successText = document.getElementById('successText');

          if (successDialog) {
            if (successText && response) {
              successText.textContent = response;
            }
            successDialog.style.display = 'flex';
          } else if (typeof toastr !== 'undefined') {
            toastr.success(response);
          } else {
            alert(response);
          }

          // Reset progress after 2 seconds
          setTimeout(() => {
            if (progressContainer) {
              progressContainer.style.display = 'none';
            }
            if (progressBar) {
              progressBar.style.width = '0%';
            }
            if (progressPercent) {
              progressPercent.textContent = '0%';
            }
          }, 2000);
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);

          // Hide loading dialog
          if (loadingDialog) {
            loadingDialog.style.display = 'none';
          }

          // Show error message
          if (typeof showMessageDialog !== 'undefined') {
            showMessageDialog('Failed to save. Please try again.', 'error');
          } else if (typeof toastr !== 'undefined') {
            toastr.error('Failed to save. Please try again.');
          } else {
            alert('Failed to save. Please try again.');
          }

          // Hide progress bar on error
          if (progressContainer) {
            progressContainer.style.display = 'none';
          }
        }
      });
    });
  }
}

/**
 * Setup recording controls for read section
 */
function setupRecordingControls() {
    const recordBtn = document.getElementById('recordBtn');
    const readRecordingInitial = document.getElementById('readRecordingInitial');
    const readRecordingControls = document.getElementById('readRecordingControls');
    const readTimer = document.getElementById('readTimer');
    const recordingPlayPauseBtn = document.getElementById('recordingPlayPauseBtn');
    const recordingStartBtn = document.getElementById('recordingStartBtn');
    const recordingRemoveBtn = document.getElementById('recordingRemoveBtn');

    // Setup re-record button
    const rerecordBtn = document.getElementById('rerecordBtn');
    if (rerecordBtn) {
        rerecordBtn.addEventListener('click', function() {
            const existingContainer = document.getElementById('existingRecording');
            if (existingContainer) {
                existingContainer.style.display = 'none';
            }
            if (readRecordingInitial) {
                readRecordingInitial.style.display = 'flex';
            }
        });
    }

    if (!recordBtn || !readRecordingControls) return;

    let isRecording = false;
    let isPlaying = false;
    let readMediaRecorder = null;
    let readRecordedChunks = [];
    let readRecordedAudio = null;
    let readTimerInterval = null;
    let readStartTime = 0;
    let readRecordingDuration = 0;

    const updateReadTimer = () => {
        const elapsed = Date.now() - readStartTime;
        const seconds = Math.floor(elapsed / 1000);
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        readTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    };

    const createReadAudioFromChunks = () => {
        const blob = new Blob(readRecordedChunks, {type: 'audio/webm'});
        const audioUrl = URL.createObjectURL(blob);
        const audio = new Audio(audioUrl);
        return audio;
    };

    const playReadRecording = () => {
        if (!readRecordedAudio || readRecordedChunks.length === 0) {
            console.log('No recording to play');
            return;
        }

        if (isPlaying) {
            // Stop playback
            readRecordedAudio.pause();
            readRecordedAudio.currentTime = 0;
            isPlaying = false;

            // Change play button back to play icon
            handlePlayPauseIcon('play');

            // Stop timer
            if (readTimerInterval) {
                clearInterval(readTimerInterval);
                readTimerInterval = null;
            }
        } else {
            // Start playback
            readRecordedAudio.play();
            isPlaying = true;

            // Change play button to pause icon
            handlePlayPauseIcon('pause');

            // Update timer during playback
            readStartTime = Date.now();
            readTimerInterval = setInterval(() => {
                const elapsed = Math.floor(readRecordedAudio.currentTime * 1000);
                const seconds = Math.floor(elapsed / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                readTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }, 100);

            // Handle playback end
            readRecordedAudio.onended = () => {
                isPlaying = false;
                handlePlayPauseIcon('play');

                if (readTimerInterval) {
                    clearInterval(readTimerInterval);
                    readTimerInterval = null;
                }
                // Reset timer to recording duration
                const seconds = Math.floor(readRecordingDuration / 1000);
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                readTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            };
        }
    };

    const startReadRecording = async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({audio: true});
            readMediaRecorder = new MediaRecorder(stream);
            readRecordedChunks = [];

            readMediaRecorder.addEventListener('dataavailable', (event) => {
                if (event.data.size > 0) {
                    readRecordedChunks.push(event.data);
                }
            });

            readMediaRecorder.addEventListener('stop', () => {
                console.log('Recording saved. Chunks:', readRecordedChunks.length);
                // Create audio element from recorded chunks
                readRecordedAudio = createReadAudioFromChunks();
                readRecordingDuration = Date.now() - readStartTime;

                // Save blob globally for saving
                globalReadRecordedBlob = new Blob(readRecordedChunks, {type: 'audio/webm'});
                console.log('Read recording blob saved globally:', globalReadRecordedBlob);
            });

            readMediaRecorder.start();
            isRecording = true;

            recordingPlayPauseBtn.style.display = 'none';
            recordingRemoveBtn.style.display = 'none';
            recordingStartBtn.style.display = 'block';
            //reset to play icon
            handlePlayPauseIcon('play');

            // Hide initial button, show recording controls
            readRecordingInitial.style.display = 'none';
            readRecordingControls.classList.add('active');

            // Start timer
            readStartTime = Date.now();
            readTimer.textContent = '0:00';
            readTimerInterval = setInterval(updateReadTimer, 100);

        } catch (error) {
            console.error('Error accessing microphone:', error);
            if (typeof showMessageDialog !== 'undefined') {
                showMessageDialog('Could not access microphone. Please check your permissions.', 'error');
            } else {
                alert('Could not access microphone. Please check your permissions.');
            }
        }
    };

    const stopReadRecording = () => {
        if (readMediaRecorder && readMediaRecorder.state !== 'inactive') {
            readMediaRecorder.stop();
            readMediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
        isRecording = false;

        recordingPlayPauseBtn.style.display = 'block';
        recordingRemoveBtn.style.display = 'block';
        recordingStartBtn.style.display = 'none';

        // Stop timer
        if (readTimerInterval) {
            clearInterval(readTimerInterval);
            readTimerInterval = null;
        }

        // Show save/delete buttons after recording stops
        setTimeout(() => {
            const readRecordingActions = document.getElementById('readRecordingActions');
            if (readRecordingActions) {
                readRecordingActions.style.display = 'flex';
            }
        }, 300);
    };

    const deleteReadRecording = () => {
        // Stop playback if playing
        if (isPlaying && readRecordedAudio) {
            readRecordedAudio.pause();
            readRecordedAudio.currentTime = 0;
            isPlaying = false;
        }

        // Clean up audio resources
        if (readRecordedAudio) {
            readRecordedAudio = null;
        }

        // Clear recorded chunks
        readRecordedChunks = [];
        readRecordingDuration = 0;

        // Clear global blob
        globalReadRecordedBlob = null;

        // Stop timer
        if (readTimerInterval) {
            clearInterval(readTimerInterval);
            readTimerInterval = null;
        }

        // Hide recording controls and action buttons, show initial button
        readRecordingControls.classList.remove('active');
        readRecordingInitial.style.display = 'flex';
        const readRecordingActions = document.getElementById('readRecordingActions');
        if (readRecordingActions) {
            readRecordingActions.style.display = 'none';
        }
    };

    const handlePlayPauseIcon = (type) => {
        if (type === 'play') {
            recordingPlayPauseBtn.innerHTML = `
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5V19L19 12L8 5Z" fill="white"/>
                </svg>
                `;
        } else {
            recordingPlayPauseBtn.innerHTML = `
                 <svg viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" id="Pause--Streamline-Iconoir" height="24" width="24">
                <path d="M11.5 35.266666666666666V10.733333333333333c0 -0.6351258333333334 0.5148741666666666 -1.15 1.15 -1.15h5.366666666666666c0.6351258333333334 0 1.15 0.5148741666666666 1.15 1.15v24.533333333333335c0 0.6351833333333333 -0.5148741666666666 1.15 -1.15 1.15H12.65c-0.6351258333333334 0 -1.15 -0.5148166666666667 -1.15 -1.15Z" fill="#ffffff" stroke="#ffffff" stroke-width="2.875"></path>
                <path d="M26.833333333333336 35.266666666666666V10.733333333333333c0 -0.6351258333333334 0.5148166666666667 -1.15 1.15 -1.15h5.366666666666666c0.6351833333333333 0 1.15 0.5148741666666666 1.15 1.15v24.533333333333335c0 0.6351833333333333 -0.5148166666666667 1.15 -1.15 1.15h-5.366666666666666c-0.6351833333333333 0 -1.15 -0.5148166666666667 -1.15 -1.15Z" fill="#ffffff" stroke="#ffffff" stroke-width="2.875"></path>
                </svg>
                `;
        }
    };


    // Initial button click - start recording
    recordBtn.addEventListener('click', startReadRecording);

    // Play button - play/pause recording
    if (recordingPlayPauseBtn) {
        recordingPlayPauseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            playReadRecording();
        });
    }

    // Stop button - stop recording or delete recording
    if (recordingStartBtn) {
        recordingStartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (isRecording) {
                stopReadRecording();
            }
        });
    }
    if (recordingRemoveBtn) {
        recordingRemoveBtn.addEventListener('click', (e) => {
            e.preventDefault();
            deleteReadRecording();

        });
    }

    // Save button - save recording with AJAX
    const saveReadRecordingBtn = document.getElementById('saveReadRecordingBtn');
    if (saveReadRecordingBtn) {
        saveReadRecordingBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (!globalReadRecordedBlob) {
                alert('No recording to save');
                return;
            }

            // Show loading state
            const btnText = this.querySelector('.btn-text');
            const btnSpinner = this.querySelector('.btn-spinner');
            if (btnText) btnText.style.display = 'none';
            if (btnSpinner) btnSpinner.style.display = 'flex';
            this.disabled = true;

            // Show loading dialog
            const loadingDialog = document.getElementById('loadingDialog');
            if (loadingDialog) {
                loadingDialog.style.display = 'flex';
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('record', globalReadRecordedBlob, 'recording.wav');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Send AJAX request
            $.ajax({
                url: SAVE_RECORDING_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function (event) {
                            if (event.lengthComputable) {
                                const percentComplete = Math.ceil((event.loaded / event.total) * 100);
                                console.log('Upload progress: ' + percentComplete + '%');
                            }
                        }, false);
                    }
                    return xhr;
                },
                success: function (response) {
                    console.log('Success:', response);

                    // Hide loading dialog
                    if (loadingDialog) {
                        loadingDialog.style.display = 'none';
                    }

                    // Show success dialog
                    const successDialog = document.getElementById('successDialog');
                    const successText = document.getElementById('successText');
                    if (successDialog) {
                        if (successText && response.message) {
                            successText.textContent = response.message;
                        }
                        successDialog.style.display = 'flex';
                    } else if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'Recording saved successfully');
                    }

                    // Reload after 2 seconds
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 2000);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);

                    // Hide loading dialog
                    if (loadingDialog) {
                        loadingDialog.style.display = 'none';
                    }

                    // Reset button state
                    if (btnText) btnText.style.display = 'inline';
                    if (btnSpinner) btnSpinner.style.display = 'none';
                    saveReadRecordingBtn.disabled = false;

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
        });
    }

    // Delete button - delete recording
    const deleteReadRecordingBtn = document.getElementById('deleteReadRecordingBtn');
    if (deleteReadRecordingBtn) {
        deleteReadRecordingBtn.addEventListener('click', function (e) {
            e.preventDefault();
            deleteReadRecording();
        });
    }
}

/**
 * Setup audio controls - Initialize Green Audio Player
 */
let audioPlayersInitialized = {
  listen: false,
  teacher: false,
  studentCorrected: false,
  studentExisting: false,
  certified: false,
  certifiedFeedback: false
};

function setupAudioControls() {
  // Initialize Green Audio Player for Listen section
  const listenPlayers = document.querySelectorAll('.listen-section .audio-player');
  if (listenPlayers.length > 0 && !audioPlayersInitialized.listen) {
    GreenAudioPlayer.init({
      selector: '.listen-section .audio-player',
      stopOthersOnPlay: true
    });
    audioPlayersInitialized.listen = true;
  }

  // Initialize Green Audio Player for Certified Recording section
  const certifiedPlayers = document.querySelectorAll('.certified-audio-player');
  if (certifiedPlayers.length > 0 && !audioPlayersInitialized.certified) {
    // Check if any player hasn't been initialized yet
    let hasUninitializedPlayer = false;
    certifiedPlayers.forEach(player => {
      if (!player.classList.contains('green-audio-player')) {
        hasUninitializedPlayer = true;
      }
    });

    if (hasUninitializedPlayer || !audioPlayersInitialized.certified) {
      GreenAudioPlayer.init({
        selector: '.certified-audio-player',
        stopOthersOnPlay: true
      });
      audioPlayersInitialized.certified = true;
      console.log('Certified recording audio players initialized');
    }
  }

  // Initialize Green Audio Player for Certified Feedback Audio
  const certifiedFeedbackPlayers = document.querySelectorAll('.certified-feedback-audio-player');
  if (certifiedFeedbackPlayers.length > 0 && !audioPlayersInitialized.certifiedFeedback) {
    // Check if any player hasn't been initialized yet
    let hasUninitializedPlayer = false;
    certifiedFeedbackPlayers.forEach(player => {
      if (!player.classList.contains('green-audio-player')) {
        hasUninitializedPlayer = true;
      }
    });

    if (hasUninitializedPlayer || !audioPlayersInitialized.certifiedFeedback) {
      GreenAudioPlayer.init({
        selector: '.certified-feedback-audio-player',
        stopOthersOnPlay: true
      });
      audioPlayersInitialized.certifiedFeedback = true;
      console.log('Certified feedback audio players initialized');
    }
  }

  // Initialize Green Audio Player for Teacher Audio Feedback
  const teacherAudioPlayers = document.querySelectorAll('.teacher-audio-player');
  if (teacherAudioPlayers.length > 0 && !audioPlayersInitialized.teacher) {
    // Check if any player hasn't been initialized yet
    let hasUninitializedPlayer = false;
    teacherAudioPlayers.forEach(player => {
      if (!player.classList.contains('green-audio-player')) {
        hasUninitializedPlayer = true;
      }
    });

    if (hasUninitializedPlayer || !audioPlayersInitialized.teacher) {
      GreenAudioPlayer.init({
        selector: '.teacher-audio-player',
        stopOthersOnPlay: true
      });
      audioPlayersInitialized.teacher = true;
      console.log('Teacher audio players initialized');
    }
  }

  // Initialize Green Audio Player for Student Recordings (Corrected)
  const studentCorrectedPlayers = document.querySelectorAll('.student-audio-player-corrected');
  if (studentCorrectedPlayers.length > 0) {
    if (!audioPlayersInitialized.studentCorrected) {
      GreenAudioPlayer.init({
        selector: '.student-audio-player-corrected',
        stopOthersOnPlay: true
      });
      audioPlayersInitialized.studentCorrected = true;
      console.log('Student recording audio player initialized');
    } else {
      // Re-initialize if new players were added
      studentCorrectedPlayers.forEach(player => {
        if (!player.classList.contains('green-audio-player')) {
          GreenAudioPlayer.init({
            selector: '.student-audio-player-corrected',
            stopOthersOnPlay: true
          });
          console.log('Student recording audio player re-initialized');
        }
      });
    }
  }

  // Initialize Green Audio Player for Student Recordings (Existing)
  const studentExistingPlayers = document.querySelectorAll('.student-audio-player-existing');
  if (studentExistingPlayers.length > 0 && !audioPlayersInitialized.studentExisting) {
    GreenAudioPlayer.init({
      selector: '.student-audio-player-existing',
      stopOthersOnPlay: true
    });
    audioPlayersInitialized.studentExisting = true;
  }
}


/**
 * Setup tracking functionality
 */
function setupTracking() {
  // Track page visit after 10 seconds
  setTimeout(function() {
    if (typeof TRACKING_URL === 'undefined') {
      console.log('Tracking URL not defined');
      return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]');
    if (!csrf) {
      console.log('CSRF token not found');
      return;
    }

    $.ajax({
      url: TRACKING_URL,
      type: 'POST',
      data: { '_token': csrf.getAttribute('content') },
      success: function(data) {
        console.log('Page visit tracked successfully');
      },
      error: function(err) {
        console.log('Tracking failed:', err);
      }
    });
  }, 10000);
}
