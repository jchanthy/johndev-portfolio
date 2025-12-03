document.addEventListener('DOMContentLoaded', () => {
    // Smooth scrolling for anchor links
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

    // Mobile Menu Toggle (Basic Implementation)
    /* 
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            // Add CSS for .nav-links.active to show menu on mobile
        });
    }
    */
    
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
});
