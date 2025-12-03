<?php get_header(); ?>

    <main>
        <section id="hero" class="hero-section container">
            <div class="hero-content">
                <div class="code-badge">
                    <span class="comment">// Welcome to my digital fortress</span>
                </div>
                <h1>Building Secure <br> <span class="text-highlight">Digital Experiences</span></h1>
                <p class="hero-subtitle">Full Stack Developer & Cybersecurity Specialist. I craft robust applications with a security-first mindset.</p>
                <div class="hero-cta">
                    <a href="#projects" class="btn btn-primary">View Work</a>
                    <a href="#contact" class="btn btn-outline">Get in Touch</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="code-block-decoration">
<pre><code><span class="keyword">const</span> <span class="variable">profile</span> = {
  <span class="property">name</span>: <span class="string">'John'</span>,
  <span class="property">role</span>: <span class="string">'Security Engineer'</span>,
  <span class="property">skills</span>: [
    <span class="string">'Penetration Testing'</span>,
    <span class="string">'Secure Coding'</span>,
    <span class="string">'React/Next.js'</span>
  ],
  <span class="property">status</span>: <span class="string">'Online'</span>
};</code></pre>
                </div>
            </div>
        </section>

        <section id="projects" class="section container">
            <div class="section-header">
                <span class="section-tag">01. Portfolio</span>
                <h2>Featured Projects</h2>
            </div>
            <div class="projects-grid">
                <?php
                $args = array(
                    'post_type' => 'project',
                    'posts_per_page' => 6,
                );
                $projects_query = new WP_Query( $args );

                if ( $projects_query->have_posts() ) :
                    while ( $projects_query->have_posts() ) : $projects_query->the_post();
                        $github_url = get_post_meta( get_the_ID(), 'github_url', true );
                        $demo_url = get_post_meta( get_the_ID(), 'demo_url', true );
                        $technologies = get_the_terms( get_the_ID(), 'technology' );
                        ?>
                        <article class="project-card">
                            <div class="project-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
                                <?php else : ?>
                                    <div class="placeholder-overlay"></div>
                                <?php endif; ?>
                            </div>
                            <div class="project-content">
                                <?php if ( $technologies && ! is_wp_error( $technologies ) ) : ?>
                                    <div class="project-tech">
                                        <?php foreach ( $technologies as $tech ) : ?>
                                            <span><?php echo esc_html( $tech->name ); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <h3><?php the_title(); ?></h3>
                                <div class="project-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="project-links">
                                    <?php if ( $github_url ) : ?>
                                        <a href="<?php echo esc_url( $github_url ); ?>" target="_blank">GitHub</a>
                                    <?php endif; ?>
                                    <?php if ( $demo_url ) : ?>
                                        <a href="<?php echo esc_url( $demo_url ); ?>" target="_blank">Live Demo</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No projects found. Add some in the WordPress Admin!</p>';
                endif;
                ?>
            </div>
        </section>

        <section id="courses" class="section container">
            <div class="section-header">
                <span class="section-tag">02. Knowledge</span>
                <h2>Courses & Certifications</h2>
            </div>
            <div class="courses-list">
                <?php
                $args = array(
                    'post_type' => 'course',
                    'posts_per_page' => -1, // Show all courses
                );
                $courses_query = new WP_Query( $args );

                if ( $courses_query->have_posts() ) :
                    while ( $courses_query->have_posts() ) : $courses_query->the_post();
                        $provider = get_post_meta( get_the_ID(), 'course_provider', true );
                        $year = get_post_meta( get_the_ID(), 'course_year', true );
                        $cert_url = get_post_meta( get_the_ID(), 'certificate_url', true );
                        $icon = get_post_meta( get_the_ID(), 'course_icon', true );
                        if ( ! $icon ) {
                            $icon = '📜'; // Default icon
                        }
                        ?>
                        <div class="course-item">
                            <div class="course-icon"><?php echo esc_html( $icon ); ?></div>
                            <div class="course-details">
                                <h3><?php the_title(); ?></h3>
                                <p class="course-meta">
                                    <?php 
                                    if ( $provider ) echo esc_html( $provider ); 
                                    if ( $provider && $year ) echo ' • ';
                                    if ( $year ) echo esc_html( $year );
                                    ?>
                                </p>
                            </div>
                            <?php if ( $cert_url ) : ?>
                                <a href="<?php echo esc_url( $cert_url ); ?>" target="_blank" class="btn-text">View Certificate &rarr;</a>
                            <?php endif; ?>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No courses found. Add some in the WordPress Admin!</p>';
                endif;
                ?>
            </div>
        </section>
        <section id="contact" class="section container">
            <div class="section-header">
                <span class="section-tag">03. Connect</span>
                <h2>Get in Touch</h2>
            </div>
            <div class="contact-content">
                <p class="contact-text">
                    I'm currently open to new opportunities and collaborations. 
                    Whether you have a question or just want to say hi, I'll try my best to get back to you!
                </p>
                <form id="contact-form" class="contact-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <!-- Honeypot Field for Spam Protection -->
                    <div class="form-group portfolio-honey" style="display:none;">
                        <label for="portfolio_honey">If you are human, leave this field blank.</label>
                        <input type="text" id="portfolio_honey" name="portfolio_honey">
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                    <div id="form-message" class="form-message"></div>
                </form>
            </div>
        </section>
    </main>

<?php get_footer(); ?>
