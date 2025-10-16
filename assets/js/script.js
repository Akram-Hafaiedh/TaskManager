// TaskMaster Application JavaScript
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

function initializeApp() {
    // Auto-hide alerts
    autoHideAlerts();

    // Form enhancements
    enhanceForms();

    // Dashboard interactions
    initDashboard();

    // Navigation effects
    initNavigation();
}

function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });
}

function enhanceForms() {
    // Real-time form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            field.addEventListener('blur', function () {
                validateField(this);
            });

            field.addEventListener('input', function () {
                clearFieldError(this);
            });
        });

        // Password strength indicator
        const passwordFields = form.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            if (field.id.includes('password') && !field.id.includes('confirm')) {
                field.addEventListener('input', function () {
                    showPasswordStrength(this.value);
                });
            }
        });

        // Confirm password validation
        const confirmPassword = form.querySelector('input[name="confirm_password"]');
        const password = form.querySelector('input[name="new_password"], input[name="password"]');

        if (confirmPassword && password) {
            confirmPassword.addEventListener('blur', function () {
                validatePasswordMatch(password.value, this.value, this);
            });
        }
    });
}

function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.name || field.id;

    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'This field is required');
        return false;
    }

    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }
    }

    // Username validation
    if (fieldName.includes('username') && value) {
        if (value.length < 3) {
            showFieldError(field, 'Username must be at least 3 characters long');
            return false;
        }
    }

    clearFieldError(field);
    return true;
}

function showFieldError(field, message) {
    clearFieldError(field);

    field.style.borderColor = '#dc3545';

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.8rem';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;

    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.style.borderColor = '';

    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

function showPasswordStrength(password) {
    let strengthDiv = document.getElementById('password-strength');
    if (!strengthDiv) {
        strengthDiv = document.createElement('div');
        strengthDiv.id = 'password-strength';
        strengthDiv.style.marginTop = '5px';
        strengthDiv.style.fontSize = '0.8rem';

        const passwordField = document.querySelector('input[type="password"]');
        passwordField.parentNode.appendChild(strengthDiv);
    }

    const strength = calculatePasswordStrength(password);
    strengthDiv.textContent = strength.text;
    strengthDiv.style.color = strength.color;
}

function calculatePasswordStrength(password) {
    let score = 0;

    if (password.length >= 8) score++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) score++;
    if (password.match(/\d/)) score++;
    if (password.match(/[^a-zA-Z\d]/)) score++;

    switch (score) {
        case 0:
        case 1:
            return { text: 'Weak', color: '#dc3545' };
        case 2:
            return { text: 'Fair', color: '#ffc107' };
        case 3:
            return { text: 'Good', color: '#28a745' };
        case 4:
            return { text: 'Strong', color: '#20c997' };
        default:
            return { text: '', color: '' };
    }
}

function validatePasswordMatch(password, confirmPassword, field) {
    if (password !== confirmPassword) {
        showFieldError(field, 'Passwords do not match');
        return false;
    }
    clearFieldError(field);
    return true;
}

function initDashboard() {
    // Task completion animations
    const completeButtons = document.querySelectorAll('.btn-success');
    completeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const taskItem = this.closest('.task-item');
            if (taskItem) {
                taskItem.style.transition = 'all 0.5s ease';
                taskItem.style.opacity = '0.7';
            }
        });
    });

    // Priority color coding
    const taskItems = document.querySelectorAll('.task-item');
    taskItems.forEach(item => {
        const priority = item.className.match(/task-priority-(\w+)/);
        if (priority) {
            const priorityText = item.querySelector('.task-priority');
            if (priorityText) {
                switch (priority[1]) {
                    case 'high':
                        priorityText.style.color = '#dc3545';
                        break;
                    case 'medium':
                        priorityText.style.color = '#ffc107';
                        break;
                    case 'low':
                        priorityText.style.color = '#28a745';
                        break;
                }
            }
        }
    });
}

function initNavigation() {
    // Add active state to current page in navigation
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (currentPath.includes(linkPath) && linkPath !== 'index.php') {
            link.style.background = 'rgba(255,255,255,0.3)';
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// AJAX helper function
function makeRequest(url, options = {}) {
    return fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        ...options
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateField,
        showPasswordStrength,
        calculatePasswordStrength
    };
}