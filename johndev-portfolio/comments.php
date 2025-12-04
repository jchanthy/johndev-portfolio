<?php
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $john_portfolio_comment_count = get_comments_number();
            if ( '1' === $john_portfolio_comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'johndev-portfolio' ),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf( 
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $john_portfolio_comment_count, 'comments title', 'johndev-portfolio' ) ),
                    number_format_i18n( $john_portfolio_comment_count ),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 50,
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'johndev-portfolio' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form( array(
        'title_reply' => __( 'Leave a Comment', 'johndev-portfolio' ),
        'label_submit' => __( 'Post Comment', 'johndev-portfolio' ),
        'class_submit' => 'submit btn btn-primary',
    ) );
    ?>

</div><!-- #comments -->
