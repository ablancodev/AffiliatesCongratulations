<?php
/**
 * AffiliatesCongratulations.php
 *
 * Copyright (c) 2014 Antonio Blanco http://www.eggemplo.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author eggemplo	
 * @package AffiliatesCongratulations
 * @since AffiliatesCongratulations 1.0.0
 *
 * Plugin Name: Affiliates Congratulations
 * Plugin URI: http://www.eggemplo.com
 * Description: Affiliates Congratulations.
 * Version: 1.0
 * Author: eggemplo
 * Author URI: http://www.eggemplo.com
 * License: GPLv3
 */
class AffiliatesCongratulations_Plugin {
	
	public static $status = AFFILIATES_REFERRAL_STATUS_ACCEPTED;
	public static $limit = 199;
	
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
	}
	public static function wp_init() {

		add_action( 'affiliates_referral', array( __CLASS__, 'affiliates_referral' ), 10, 2 );

	}

	public static function affiliates_referral ( $referral_id, $data ) {
		$affiliate_id = $data['affiliate_id'];
		$user_id = affiliates_get_affiliate_user( $affiliate_id );

		$referrals = affiliates_get_affiliate_referrals( $affiliate_id, null, null, self::$status );

		if ( isset( $referrals ) && ( $referrals > self::$limit )  && ( $referrals <= ( self::$limit + 1 ) )  && ( $user_id ) ) {

			$user_info = get_userdata( $user_id );
			$user_email = $user_info->user_email;
			$headers = 'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) .'>' . "\r\n";
			$to = $user_email;
			$subject = 'Congratulations';
			$message = 'Congratulations, you have got ' . $referrals . ' referrals.';
			@wp_mail( $to, $subject, $message, $headers );

		}
	}
}
AffiliatesCongratulations_Plugin::init();
