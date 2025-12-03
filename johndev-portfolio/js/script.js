document.addEventListener('DOMContentLoaded', () => {
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Contact Form AJAX
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'john_portfolio_send_message');
            formData.append('nonce', john_portfolio_ajax.nonce);

            const submitBtn = this.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('form-message');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            messageDiv.textContent = '';
            messageDiv.className = 'form-message';

            fetch(john_portfolio_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.textContent = data.data;
                        messageDiv.classList.add('success');
                        contactForm.reset();
                    } else {
                        messageDiv.textContent = data.data;
                        messageDiv.classList.add('error');
                    }
                })
                .catch(error => {
                    messageDiv.textContent = 'An error occurred. Please try again.';
                    messageDiv.classList.add('error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';
                });
        });
    }

    // Add simple hover effect to code block
    const codeBlock = document.querySelector('.code-block-decoration');
    if (codeBlock) {
        codeBlock.addEventListener('mousemove', (e) => {
            const rect = codeBlock.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            codeBlock.style.setProperty('--x', `${x}px`);
            codeBlock.style.setProperty('--y', `${y}px`);
        });
    }
    codeBlock.style.setProperty('--y', `${y}px`);
});
    }

// Scroll Spy
const sections = document.querySelectorAll('section');
const navLinks = document.querySelectorAll('.nav-links a');

const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.3 // Trigger when 30% of section is visible
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${id}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}, observerOptions);

sections.forEach(section => {
    observer.observe(section);
});
});
