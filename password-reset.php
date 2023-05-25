<?php
/*
Plugin Name: BuddyBoss Remove Confirm Password
Description: This is a simple plugin that removes the confirm password step
Version: 1.0
Author: PB Digital
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/pbdigital/BuddyBoss-Confirm-Password-Remover',
	__FILE__,
	'pbd-password-reset'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('stable-branch-name');
function enqueue_custom_login_script() {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action === 'rp' || $action === 'resetpass') {
        wp_register_script( 'custom-login-script', false );
        wp_enqueue_script( 'custom-login-script' );

        $inline_script = "
            jQuery(document).ready(function($) {
                $('.user-bs-pass2-wrap').hide();
                
                function updatePass2() {
                    $('#pass2, #bs-pass2').val($('#pass1').val());
                }

                $('#pass1').on('input propertychange', updatePass2);

                $('.wp-generate-pw').on('click', function() {
                    setTimeout(updatePass2, 200);
                });

                $('#resetpassform').on('submit', updatePass2);
            });
        ";

        wp_add_inline_script( 'custom-login-script', $inline_script);
    }
}

add_action( 'login_enqueue_scripts', 'enqueue_custom_login_script' );

?>
