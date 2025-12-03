<?php get_header(); ?>

    <main>
        <section id="hero" class="hero-section container">
            <div class="hero-content">
                <div class="code-badge">
                    <span class="comment"><?php echo esc_html( get_theme_mod( 'john_portfolio_code_comment', '// Welcome to my digital fortress' ) ); ?></span>
                </div>
                <h1><?php echo esc_html( get_theme_mod( 'john_portfolio_hero_title', 'Building Secure' ) ); ?> <br> <span class="text-highlight"><?php echo esc_html( get_theme_mod( 'john_portfolio_hero_highlight', 'Digital Experiences' ) ); ?></span></h1>
                <p class="hero-subtitle"><?php echo esc_html( get_theme_mod( 'john_portfolio_hero_subtitle', 'Full Stack Developer & Cybersecurity Specialist. I craft robust applications with a security-first mindset.' ) ); ?></p>
                <div class="hero-cta">
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_hero_cta_url', '#projects' ) ); ?>" class="btn btn-primary"><?php echo esc_html( get_theme_mod( 'john_portfolio_hero_cta_text', 'View Work' ) ); ?></a>
                    <a href="#contact" class="btn btn-outline">Get in Touch</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="code-block-decoration">
<pre><code><span class="keyword">const</span> <span class="variable">profile</span> = {
  <span class="property">name</span>: <span class="string">'<?php echo esc_html( get_theme_mod( 'john_portfolio_code_name', 'John' ) ); ?>'</span>,
  <span class="property">role</span>: <span class="string">'<?php echo esc_html( get_theme_mod( 'john_portfolio_code_role', 'Security Engineer' ) ); ?>'</span>,
  <span class="property">skills</span>: [
<?php
    $skills = get_theme_mod( 'john_portfolio_code_skills', 'Penetration Testing, Secure Coding, React/Next.js' );
    $skills_array = explode( ',', $skills );
    $last_key = array_key_last($skills_array);
    foreach ( $skills_array as $key => $skill ) {
        $comma = ( $key !== $last_key ) ? ',' : '';
        echo '    <span class="string">\'' . esc_html( trim( $skill ) ) . '\'</span>' . $comma . "\n";
    }
?>
  ],
  <span class="property">status</span>: <span class="string">'Online'</span>
};</code></pre>
                </div>
            </div>
        </section>
        
        <section id="skills" class="section container">
            <div class="section-header">
                <span class="section-tag">01. Expertise</span>
                <h2>Technical Skills</h2>
            </div>
            <div class="skills-grid">
                <?php
                $args = array(
                    'post_type' => 'skill',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                );
                $skills_query = new WP_Query( $args );

                if ( $skills_query->have_posts() ) :
                    while ( $skills_query->have_posts() ) : $skills_query->the_post();
                        $skill_level = get_post_meta( get_the_ID(), '_skill_level', true );
                        ?>
                        <div class="skill-card">
                            <div class="skill-icon">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'thumbnail' ); ?>
                                <?php else : ?>
                                    <span class="dashicons dashicons-hammer" style="font-size: 40px; width: 40px; height: 40px; color: var(--accent-blue);"></span>
                                <?php endif; ?>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <?php if ( $skill_level ) : ?>
                                <div class="skill-progress-container">
                                    <div class="skill-progress-bar" style="width: <?php echo esc_attr( $skill_level ); ?>%;"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No skills found. Add some in the WordPress Admin!</p>';
                endif;
                ?>
            </div>
        </section>

        <section id="projects" class="section container">
            <div class="section-header">
                <span class="section-tag">02. Portfolio</span>
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
                <span class="section-tag">03. Knowledge</span>
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

        <section id="experience" class="section container">
            <div class="section-header">
                <span class="section-tag">04. Experience</span>
                <h2>Work History</h2>
            </div>
            <div class="timeline-container">
                <?php
                $args = array(
                    'post_type' => 'experience',
                    'posts_per_page' => -1,
                    'order' => 'DESC',
                );
                $experience_query = new WP_Query( $args );

                if ( $experience_query->have_posts() ) :
                    while ( $experience_query->have_posts() ) : $experience_query->the_post();
                        $company = get_post_meta( get_the_ID(), '_experience_company', true );
                        $job_title = get_post_meta( get_the_ID(), '_experience_job_title', true );
                        $duration = get_post_meta( get_the_ID(), '_experience_duration', true );
                        $location = get_post_meta( get_the_ID(), '_experience_location', true );
                        ?>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h3><?php echo esc_html( $job_title ); ?></h3>
                                    <span class="timeline-company"><?php echo esc_html( $company ); ?></span>
                                </div>
                                <div class="timeline-meta">
                                    <span class="timeline-duration"><?php echo esc_html( $duration ); ?></span>
                                    <?php if ( $location ) : ?>
                                        <span class="timeline-location"> • <?php echo esc_html( $location ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="timeline-description">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No experience added yet.</p>';
                endif;
                ?>
            </div>
        </section>

        <?php if ( get_theme_mod( 'john_portfolio_show_blog', true ) ) : ?>
        <section id="blog" class="section container">
            <div class="section-header">
                <span class="section-tag">05. Insights</span>
                <h2><?php echo esc_html( get_theme_mod( 'john_portfolio_blog_title', 'Latest Articles' ) ); ?></h2>
            </div>
            <div class="blog-grid">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'ignore_sticky_posts' => 1,
                );
                $blog_query = new WP_Query( $args );

                if ( $blog_query->have_posts() ) :
                    while ( $blog_query->have_posts() ) : $blog_query->the_post();
                        ?>
                        <article class="blog-card">
                            <div class="blog-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="placeholder-overlay"></a>
                                <?php endif; ?>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-date"><?php echo get_the_date(); ?></span>
                                </div>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="blog-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn-text">Read Article &rarr;</a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No articles found.</p>';
                endif;
                ?>
            </div>
        </section>
        <?php endif; ?>

        <section id="contact" class="section container">
            <div class="section-header">
                <span class="section-tag">06. Connect</span>
                <h2>Get in Touch</h2>
            </div>
            <div class="contact-content">
                <p class="contact-text">
                    <?php echo nl2br( esc_html( get_theme_mod( 'john_portfolio_contact_text', "I'm currently open to new opportunities and collaborations. Whether you have a question or just want to say hi, I'll try my best to get back to you!" ) ) ); ?>
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
