
document.addEventListener('DOMContentLoaded', function() {
    // Render courses dynamically before initializing functionality
    renderCourses();

    initLearningPath()
});

// ===== DYNAMIC COURSE RENDERING =====

/**
 * Render courses dynamically based on courses array
 */
function renderCourses() {
    const container = document.querySelector('.course-path-container');
    let mainCoursesLength = courses.length
    if (!container) {
        console.error('Course path container not found');
        return;
    }

    // Clear existing content
    container.innerHTML = '';
    if (alternate_courses && alternate_courses.length > 0) {
        courses = [...courses,...alternate_courses];
    }
    // Render path connectors for main courses
    renderPathConnectors(courses);

    let start_alternate = false;
    // Render main course cards
    courses.forEach((course, index) => {
        if (index===mainCoursesLength){
            start_alternate = true;
        }
        let courseCard= createCourseCard(course, index,start_alternate);
        container.appendChild(courseCard);
    });
}

/**
 * Create dynamic path connectors based on number of courses
 */
function renderPathConnectors(courses) {
    const container = document.querySelector('.course-path-container');

    // Create connectors between courses (n-1 connectors for n courses)
    for (let i = 0; i < courses.length - 1; i++) {
        const connector = document.createElement('div');

        connector.className = `path-connector`;
        connector.style.position = 'absolute';

        // Calculate dynamic position for connector
        // Connectors appear between cards in the center
        const baseTop = 30;
        const cardHeight = 160; // Actual height of course card
        const spacing = 280;

        // Position at bottom of current card (no gap)
        const startTop = baseTop + (i * spacing) + cardHeight - 10;

        connector.style.top = `clamp(${startTop/2}px, ${startTop / 19.2}vw, ${startTop}px)`;
        connector.style.left = '50%';
        connector.style.transform = 'translateX(-50%)';
        connector.style.width = '16px';
        connector.style.height = `${spacing - cardHeight + 10}px`; // Gap between cards

        // Determine if connector should be completed or incomplete
        const isCompleted = courses[i].status === 'completed';

        // Style the connector with gradient background and borders
        if (isCompleted) {
            // Completed connector - golden gradient
            connector.style.background = 'linear-gradient(0deg, #F3C757 0%, #F9E09D 100%)';
            connector.style.borderStyle = 'solid';
            connector.style.borderWidth = '1px 1px 3px 1px';
            connector.style.borderColor = '#F3C757';
            connector.style.boxShadow = '0 0 0 1px rgba(0, 0, 0, 0.16)';
        } else {
            // Incomplete connector - light blue
            connector.style.background = '#C8E6FF82';
            connector.style.border = 'none';
            connector.style.boxShadow = 'none';
        }

        connector.style.borderRadius = '2px';

        // Add line drawing animation using clip-path
        connector.style.clipPath = 'inset(0 0 100% 0)';
        connector.style.transition = 'clip-path 0.8s ease-out';

        container.appendChild(connector);
    }

    // Trigger line drawing animation after connectors are added
    setTimeout(() => animateConnectors(), 100);
}

/**
 * Create a course card element
 */
function createCourseCard(course, index,alternate=false) {
    const article = document.createElement('article');
    article.className = `course-card course-card--${course.status}`;
    article.setAttribute('data-course-id', course.id);
    if (alternate){
        article.style.background = '#dbdbdb';
    }
    // Calculate dynamic position based on index
    // Base spacing: first card at 30px, then larger spacing between cards
    const baseTop = 30;
    const spacing = 280; // Increased spacing to prevent overlap
    const calculatedTop = baseTop + (index * spacing);

    // Determine if card is on left or right (alternating)
    const isLeft = index % 2 === 0;

    // Apply dynamic positioning - alternate between left and right
    article.style.top = `clamp(${calculatedTop/2}px, ${calculatedTop / 19.2}vw, ${calculatedTop}px)`;
    article.style.position = 'absolute';

    if (isLeft) {
        article.style.left = 'calc(50% - 90px)'; // Position on left side
        article.style.right = 'auto'; // Position on right side
        article.style.transform = 'translateX(0)';
    } else {
        article.style.left = 'auto'; // Position on right side
        article.style.right = 'calc(50% - 90px)';
        article.style.transform = 'translateX(0)';
    }

    // Create course icon
    const icon = document.createElement('img');
    icon.className = 'course-icon';
    icon.src = course.icon;
    icon.alt = `${course.title} course icon`;

    // Create course content
    const content = document.createElement('div');
    content.className = 'course-content';
    content.dataset.redirectUrl = course.redirect_url;

    // Create course header
    const header = document.createElement('header');
    header.className = 'course-header';

    const title = document.createElement('h3');
    title.className = course.status === 'current' ? 'course-title course-title--rtl' : 'course-title';
    title.textContent = course.title;

    header.appendChild(title);
    content.appendChild(header);

    // Create course description
    const description = document.createElement('p');
    description.className = course.status === 'locked'
        ? 'course-description course-description--locked'
        : 'course-description';
    description.textContent = course.description;
    //content.appendChild(description);

    // Create progress section
    if (course.status === 'completed') {
        // Container for progress bar and check icon
        const progressContainer = document.createElement('div');
        progressContainer.className = 'course-progress-container';

        // Full progress bar for completed courses
        const progress = document.createElement('div');
        progress.className = 'course-progress course-progress--full';
        progress.setAttribute('role', 'progressbar');
        progress.setAttribute('aria-valuenow', '100');
        progress.setAttribute('aria-valuemin', '0');
        progress.setAttribute('aria-valuemax', '16');
        progress.setAttribute('aria-label', 'Course progress: 100%');

        // Create lesson dots
        for (let i = 0; i < 16; i++) {
            const dot = document.createElement('div');
            dot.className = 'lesson-dot-completed';
            progress.appendChild(dot);
        }

        // Create check icon for completed courses
        const checkIcon = document.createElement('div');
        checkIcon.className = 'progress-check-icon';
        checkIcon.innerHTML = `
      <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 5L4.5 8.5L11 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    `;

        progressContainer.appendChild(progress);
        progressContainer.appendChild(checkIcon);
        content.appendChild(progressContainer);
    } else {
        // Lesson dots for current and locked courses
        const lessonProgress = document.createElement('div');
        lessonProgress.className = 'lesson-progress';
        lessonProgress.setAttribute('role', 'progressbar');
        lessonProgress.setAttribute('aria-valuenow', course.progress);
        lessonProgress.setAttribute('aria-valuemin', '0');
        lessonProgress.setAttribute('aria-valuemax', '16');
        lessonProgress.setAttribute('aria-label',
            `Course progress: ${course.progress} out of ${course.totalLessons} lessons ${course.status === 'current' ? 'completed' : ''}`
        );

        // Create lesson dots
        for (let i = 0; i < 16; i++) {
            const dot = document.createElement('div');
            dot.className = i < (course.progress*16) / 100
                ? 'lesson-dot lesson-dot--complete'
                : 'lesson-dot lesson-dot--incomplete';
            lessonProgress.appendChild(dot);
        }

        content.appendChild(lessonProgress);
    }

    // Append elements based on position (left/right alternating)
    // Left cards: icon first, content second
    // Right cards: content first, icon second (mirrored)
    if (isLeft) {
        article.appendChild(icon);
        article.appendChild(content);
    } else {
        article.appendChild(content);
        article.appendChild(icon);
    }

    return article;
}
// Learning path interactions
function initLearningPath() {
    const courseCards = document.querySelectorAll('.course-card');

    courseCards.forEach(card => {
        // Only add interactions to unlocked courses
        if (!card.classList.contains('course-card--locked')) {
            card.addEventListener('click', () => {
                const title = card.querySelector('.course-title').textContent;
                console.log(`Starting course: ${title}`);

                // Add selection animation
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                    // Redirect to course roadmap
                    window.location.href = card.querySelector('.course-content').dataset.redirectUrl;
                }, 200);
            });

            // Add hover effects for unlocked courses
            card.addEventListener('mouseenter', () => {
                if (!card.classList.contains('course-card--current')) {
                    card.style.transform = 'translateY(-4px)';
                    card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                    card.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
                }
            });

            card.addEventListener('mouseleave', () => {
                if (!card.classList.contains('course-card--current')) {
                    card.style.transform = 'translateY(0)';
                    card.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
                }
            });
        }

        // Add pulse animation to current course
        if (card.classList.contains('course-card--current')) {
            setInterval(() => {
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 300);
            }, 3000);
        }
    });

    // Animate lesson dots
    const lessonDots = document.querySelectorAll('.lesson-dot');
    lessonDots.forEach((dot, index) => {
        setTimeout(() => {
            dot.style.transform = 'scale(1.2)';
            setTimeout(() => {
                dot.style.transform = 'scale(1)';
            }, 200);
        }, index * 50);
    });
}
