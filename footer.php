    </div><!-- #content -->
    
    <footer id="colophon" class="site-footer">
        <div class="footer-content">
            
            <div class="footer-branding">
                <h2 class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </h2>
                <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
                    ?>
                    <p class="tagline"><?php echo $description; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="footer-legal-column">
                <h3 class="footer-heading"><?php _e( 'Legal', 'langgam-fikir' ); ?></h3>
                <nav class="footer-legal-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer-legal',
                            'menu_id'        => 'footer-legal-menu',
                            'container'      => false,
                            'fallback_cb'    => 'lf_footer_legal_fallback',
                        )
                    );
                    ?>
                </nav>
            </div>
            
            <div class="footer-contact-column" itemscope itemtype="https://schema.org/Organization">
                <h3 class="footer-heading"><?php _e( 'Contact', 'langgam-fikir' ); ?></h3>
                <div class="footer-contact-info">
                    <?php
                    // Get contact information from theme customizer
                    $company_name = get_theme_mod( 'company_name', get_bloginfo( 'name' ) );
                    $address = get_theme_mod( 'company_address', '' );
                    $phone = get_theme_mod( 'company_phone', '' );
                    $email = get_theme_mod( 'company_email', '' );
                    ?>
                    
                    <meta itemprop="name" content="<?php echo esc_attr( $company_name ); ?>">
                    <meta itemprop="url" content="<?php echo esc_url( home_url( '/' ) ); ?>">
                    
                    <?php if ( $address ) : ?>
                        <div class="contact-item" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span itemprop="streetAddress"><?php echo esc_html( $address ); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $phone ) : ?>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $phone ) ); ?>" itemprop="telephone"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $email ) : ?>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m2 7 8.97 5.7a1.94 1.94 0 0 0 2.06 0L22 7"/></svg>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>" itemprop="email"><?php echo esc_html( $email ); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <p class="copyright">
                    &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'All rights reserved.', 'langgam-fikir' ); ?>
                </p>
                <p class="company-info">
                    <?php 
                    $legal_entity = get_theme_mod( 'legal_entity_name', 'Langgam Fikir Enterprise' );
                    $reg_number = get_theme_mod( 'registration_number', '' );
                    
                    if ( $reg_number ) {
                        printf( 
                            __( '%s is a registered enterprise in Malaysia (Reg. No.: %s).', 'langgam-fikir' ),
                            esc_html( $legal_entity ),
                            esc_html( $reg_number )
                        );
                    } else {
                        printf( 
                            __( '%s is a registered enterprise in Malaysia.', 'langgam-fikir' ),
                            esc_html( $legal_entity )
                        );
                    }
                    ?>
                </p>
            </div>
        </div>
    </footer>
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
