<?php
/**
 * Page: Request
 * 
 * Only displays the page content if the user is logged in.
 * Otherwise, displays a button pointing to the login page. 
 * Intended to be used to allow users to self-register as contributors,
 * then fill out a form to request acccess to a specific site with a specific role.
 *
 * @package pitchfork
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$site = get_site();
// do_action('qm/debug', $site);
?>

	<main id="skip-to-content" <?php post_class(); ?>>

		<?php

		while ( have_posts() ) {

			the_post();
            ?>

            <?php
            if ( ! is_user_logged_in() ) {
                ?>
                <div class="uds-hero-sm alignfull has-btn-row ">
                    <h1>Need access to a site?</h1>
                    <div class="content">
                        <p>Let's get you logged in first.</p>
                    </div>
                </div>
                <section id="login-button" class="container">
                    <div class="row">
                        <div class="col-md-8 pr-4">
                            <p class="lead">Clicking the login button will take you to an ASU Single Sign-on page where you'll need to enter your ASURite and password.</p>
                            <p>After you login, complete the following steps to request access to a specific website.</p>
        
                            <ol class="uds-list uds-steplist uds-steplist-maroon" style="margin-bottom:0;">
                                <li>You will need to fill out a form telling us which site you need access to.</li>
                                <li>Your request will be processed within 24 hours.</li>
                                <li>User accounts that are created with this process and that have no additional roles assigned will be perodically deleted.</li>
                            </ol>
                            
                        </div> 
                        <div class="col-md-4">
                            <a class="btn btn-maroon mb-4" href="/wp-login.php">Register as a user</a>
                            <h4>Installation: <span class="highlight-gold"><?php echo $site->domain; ?></span></h4>
                            <p>This is a user request portal for multiple websites related to ASU Engineering.</p>
                            <h4>Other servers</h4>
                            <ul>
                                <li><a href="https://prod-pitchfork.fsewp.asu.edu">prod-pitchfork</a></li>
                                <li><a href="https://prod-asudivi.fsewp.asu.edu">prod-asudivi</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <?php
            
            } else {

                the_content();

                // Display the edit post button to logged in users.
                echo '<footer class="entry-footer"><div class="container"><div class="row"><div class="col-md-12">';
                edit_post_link( __( 'Edit', 'pitchfork' ), '<span class="edit-link">', '</span>' );
                echo '</div></div></div></footer><!-- end .entry-footer -->';

            }

		}

		?>

	</main><!-- #main -->
	
<?php
get_footer();

