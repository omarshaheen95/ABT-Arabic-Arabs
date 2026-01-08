/**
 * Lesson Page - Interactive Controls Only
 * All content is now static in HTML
 */

// Global variable to store recorded audio blob for form submission
let globalRecordedAudioBlob = null;

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
  setupTabSwitching();
  setupFooterButtons();
  setupRecordingControls();
  initializeActiveTab();
});

/**
 * Setup tab switching functionality
 */
function setupTabSwitching() {
  const tabs = document.querySelectorAll('.lesson-tab');
  const sections = {
      0: document.getElementById('listenSection'),
      1: document.getElementById('watchSection'),
      2: document.getElementById('answerSection')
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
          //setupRecordingControls();
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

          // Setup audio controls for Listen section
          if (index === 0) {
            setupAudioControls();
          }

          // Setup answer controls for Answer section
          // if (index === 4) {
          //     setupRecordingControls();
          // }
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
    // Speak Answer Button (Recording)
    const speakAnswerBtn = document.getElementById('speakAnswerBtn');
    const speakAnswerContainer = document.getElementById('speakAnswerContainer');
    const recordingControls = document.getElementById('recordingControls');
    const recordingTimer = document.getElementById('recordingTimer');
    const recordingPlayPauseBtn = document.getElementById('recordingPlayPauseBtn');
    const recordingStartBtn = document.getElementById('recordingStartBtn');
    const recordingRemoveBtn = document.getElementById('recordingRemoveBtn');

    if (speakAnswerBtn && recordingControls) {
        let isRecording = false;
        let isPlaying = false;
        let speakMediaRecorder = null;
        let speakRecordedChunks = [];
        let recordedAudio = null;
        let timerInterval = null;
        let startTime = 0;
        let recordingDuration = 0;

        const updateTimer = () => {
            const elapsed = Date.now() - startTime;
            const seconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            recordingTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        };

        const createAudioFromChunks = () => {
            const blob = new Blob(speakRecordedChunks, { type: 'audio/webm' });
            const audioUrl = URL.createObjectURL(blob);
            const audio = new Audio(audioUrl);
            return audio;
        };

        const playRecording = () => {
            if (!recordedAudio || speakRecordedChunks.length === 0) {
                console.log('No recording to play');
                return;
            }

            if (isPlaying) {
                // Stop playback
                recordedAudio.pause();
                recordedAudio.currentTime = 0;
                isPlaying = false;


                // Change play button back to play icon
                handlePlayPauseIcon('play');


                // Stop timer
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            } else {
                // Start playback
                recordedAudio.play();
                isPlaying = true;
                // Change play button to pause icon
                handlePlayPauseIcon('pause');
                // Update timer during playback
                startTime = Date.now();
                timerInterval = setInterval(() => {
                    const elapsed = Math.floor(recordedAudio.currentTime * 1000);
                    const seconds = Math.floor(elapsed / 1000);
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    recordingTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
                }, 100);

                // Handle playback end
                recordedAudio.onended = () => {
                    isPlaying = false;
                    handlePlayPauseIcon('play');
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                    }
                    // Reset timer to recording duration
                    const seconds = Math.floor(recordingDuration / 1000);
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    recordingTimer.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
                };
            }
        };

        const startRecording = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                speakMediaRecorder = new MediaRecorder(stream);
                speakRecordedChunks = [];

                speakMediaRecorder.addEventListener('dataavailable', (event) => {
                    if (event.data.size > 0) {
                        speakRecordedChunks.push(event.data);
                    }
                });

                speakMediaRecorder.addEventListener('stop', () => {
                    console.log('Recording saved. Chunks:', speakRecordedChunks.length);
                    // Create audio element from recorded chunks
                    recordedAudio = createAudioFromChunks();
                    recordingDuration = Date.now() - startTime;

                    // Save blob globally for form submission
                    globalRecordedAudioBlob = new Blob(speakRecordedChunks, { type: 'audio/webm' });
                    console.log('Audio blob saved globally:', globalRecordedAudioBlob);
                });

                speakMediaRecorder.start();
                isRecording = true;

                recordingPlayPauseBtn.style.display = 'none';
                recordingRemoveBtn.style.display = 'none';
                recordingStartBtn.style.display = 'block';
                //reset to play icon
                handlePlayPauseIcon('play');


                // Hide initial button, show recording controls
                speakAnswerContainer.style.display = 'none';
                recordingControls.classList.add('active');

                // Start timer
                startTime = Date.now();
                recordingTimer.textContent = '0:00';
                timerInterval = setInterval(updateTimer, 100);

            } catch (error) {
                console.error('Error accessing microphone:', error);
                if (typeof showMessageDialog !== 'undefined') {
                    showMessageDialog('Could not access microphone. Please check your permissions.', 'error');
                } else {
                    alert('Could not access microphone. Please check your permissions.');
                }
            }
        };

        const stopRecording = () => {
            if (speakMediaRecorder && speakMediaRecorder.state !== 'inactive') {
                speakMediaRecorder.stop();
                speakMediaRecorder.stream.getTracks().forEach(track => track.stop());
            }
            isRecording = false;


            recordingPlayPauseBtn.style.display = 'block';
            recordingRemoveBtn.style.display = 'block';
            recordingStartBtn.style.display = 'none';

            // Stop timer
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        };

        const deleteRecording = () => {
            // Stop playback if playing
            if (isPlaying && recordedAudio) {
                recordedAudio.pause();
                recordedAudio.currentTime = 0;
                isPlaying = false;
            }

            // Clean up audio resources
            if (recordedAudio) {
                recordedAudio = null;
            }

            // Clear recorded chunks
            speakRecordedChunks = [];
            recordingDuration = 0;

            // Clear global blob
            globalRecordedAudioBlob = null;

            // Stop timer
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }

            // Hide recording controls, show initial button
            recordingControls.classList.remove('active');
            speakAnswerContainer.style.display = 'flex';
        };

        const handlePlayPauseIcon = (type) => {
            if (type==='play') {
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
        speakAnswerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            startRecording();
        });

        // Play button - play/pause recording
        if (recordingPlayPauseBtn) {
            recordingPlayPauseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                playRecording();
            });
        }

        // Stop button - stop recording or delete recording
        if (recordingStartBtn) {
            recordingStartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (isRecording) {
                    stopRecording();
                }
            });
        }
        if (recordingRemoveBtn) {
            recordingRemoveBtn.addEventListener('click', (e) => {
                e.preventDefault();
                deleteRecording();

            });
        }
    }

    // Word Count for Textarea
    const answerTextarea = document.getElementById('answerTextarea');
    const wordCountSpan = document.getElementById('wordCount');

    if (answerTextarea && wordCountSpan) {
        answerTextarea.addEventListener('input', () => {
            const text = answerTextarea.value.trim();
            const wordCount = text === '' ? 0 : text.split(/\s+/).length;
            wordCountSpan.textContent = wordCount;
        });
    }

    // File Upload
    const fileUpload = document.getElementById('fileUpload');
    const fileNameSpan = document.getElementById('fileName');

    if (fileUpload && fileNameSpan) {
        fileUpload.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                fileNameSpan.textContent = file.name;
            } else {
                fileNameSpan.textContent = '';
            }
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
  studentExisting: false
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
  const certifiedPlayers = document.querySelectorAll('.certified-recording-section .certified-audio-player');
  if (certifiedPlayers.length > 0) {
    GreenAudioPlayer.init({
      selector: '.certified-recording-section .certified-audio-player',
      stopOthersOnPlay: true
    });
  }

  // Initialize Green Audio Player for Teacher Audio Feedback
  const teacherAudioPlayers = document.querySelectorAll('.teacher-audio-player');
  if (teacherAudioPlayers.length > 0 && !audioPlayersInitialized.teacher) {
    GreenAudioPlayer.init({
      selector: '.teacher-audio-player',
      stopOthersOnPlay: true
    });
    audioPlayersInitialized.teacher = true;
  }

  // Initialize Green Audio Player for Student Recordings (Corrected)
  const studentCorrectedPlayers = document.querySelectorAll('.student-audio-player-corrected');
  if (studentCorrectedPlayers.length > 0 && !audioPlayersInitialized.studentCorrected) {
    GreenAudioPlayer.init({
      selector: '.student-audio-player-corrected',
      stopOthersOnPlay: true
    });
    audioPlayersInitialized.studentCorrected = true;
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
 * Initialize buttons based on currently active tab on page load
 */
function initializeActiveTab() {
  const activeTab = document.querySelector('.lesson-tab.active');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const saveBtn = document.getElementById('saveBtn');
  const tabs = document.querySelectorAll('.lesson-tab');

  if (!activeTab || !nextBtn || !prevBtn) return;

  const activeIndex = parseInt(activeTab.getAttribute('data-section-index'));

  // Update Previous button visibility
  prevBtn.style.display = activeIndex === 0 ? 'none' : 'block';

  // Update Next and Save button visibility based on active section
  if (activeIndex === 2) { // Answer section
    nextBtn.style.display = 'none';
    if (saveBtn) {
      saveBtn.style.display = 'block';
    }
  } else {
    if (saveBtn) {
      saveBtn.style.display = 'none';
    }
    nextBtn.style.display = activeIndex < tabs.length - 1 ? 'block' : 'none';
  }
}
