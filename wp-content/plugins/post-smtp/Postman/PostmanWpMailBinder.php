<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (! class_exists ( 'PostmanWpMailBinder' )) {
	class PostmanWpMailBinder {
		private $logger;
		public $bound;
		private $bindError;
		
		/**
		 * private singleton constructor
		 */
		private function __construct() {
			$this->logger = new PostmanLogger ( get_class ( $this ) );
			
			// load the dependencies
			require_once 'PostmanWpMail.php';
			require_once 'PostmanOptions.php';
			require_once 'PostmanPreRequisitesCheck.php';
			
			// register the bind status hook
			add_filter ( 'postman_wp_mail_bind_status', array (
					$this,
					'postman_wp_mail_bind_status' 
			) );
		}
		
		/**
		 * Return the Singleton instance
		 *
		 * @return PostmanWpMailBinder
		 */
		public static function getInstance() {
			static $inst = null;
			if ($inst === null) {
				$inst = new PostmanWpMailBinder ();
			}
			return $inst;
		}
		
		/**
		 * Returns the bind result
		 */
		public function postman_wp_mail_bind_status() {
			$result = array (
					'bound' => $this->bound,
					'bind_error' => $this->bindError 
			);
			return $result;
		}
		
		/**
		 * Important: bind() may be called multiple times
		 *
		 * Replace wp_mail() after making sure:
		 * 1) the plugin has not already bound to wp_mail and
		 * 2) wp_mail is available for use
		 * 3) the plugin is properly configured.
		 * 4) the plugin's prerequisites are met.
		 */
		function bind() {
			if (! $this->bound) {
				$ready = true;
				if (function_exists ( 'wp_mail' )) {
					// If the function exists, it's probably because another plugin has
					// replaced the pluggable function first, and we set an error flag.
					// this is an error message because it is a Bind error
					if ($this->logger->isTrace ()) {
						$this->logger->trace ( 'wp_mail is already bound, Postman can not use it' );
					}
					$this->bindError = true;
					$ready = false;
				}
				if (! PostmanPreRequisitesCheck::isReady ()) {
					// this is a debug message because it is not up to the Binder to report transport errors
					if ($this->logger->isTrace ()) {
						$this->logger->trace ( 'Prerequisite check failed' );
					}
					$ready = false;
				}
				if ($ready) {
					if ($this->logger->isTrace ()) {
						$this->logger->trace ( 'Binding to wp_mail()' );
					}
					$this->replacePluggableFunctionWpMail ();
				}
			}
		}
		
		/**
		 * The code to replace the pluggable wp_mail()
		 *
		 * If the function does not exist, then the replacement was successful
		 * and we set a success flag.
		 */
		private function replacePluggableFunctionWpMail() {
			/**
			 * The Postman drop-in replacement for the WordPress wp_mail() function
			 *
			 * @param string|array $to
			 *        	Array or comma-separated list of email addresses to send message.
			 * @param string $subject
			 *        	Email subject
			 * @param string $message
			 *        	Message contents
			 * @param string|array $headers
			 *        	Optional. Additional headers.
			 * @param string|array $attachments
			 *        	Optional. Files to attach.
			 * @since 2.0.25 @action `wp_mail_succeeded` added.
			 * @return bool Whether the email contents were sent successfully.
			 */
			function wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {
				// create an instance of PostmanWpMail to send the message
				$postmanWpMail = new PostmanWpMail ();
				// send the mail
				
				$atts = compact( 'to', 'subject', 'message', 'headers', 'attachments' );

				/**
				 * Filters whether to preempt sending an email.
				 *
				 * Returning a non-null value will short-circuit {@see wp_mail()}, returning
				 * that value instead. A boolean return value should be used to indicate whether
				 * the email was successfully sent.
				 *
				 * @since 2.9.8
				 *
				 * @param null|bool $return Short-circuit return value.
				 * @param array     $atts {
				 *     Array of the `wp_mail()` arguments.
				 *
				 *     @type string|string[] $to          Array or comma-separated list of email addresses to send message.
				 *     @type string          $subject     Email subject.
				 *     @type string          $message     Message contents.
				 *     @type string|string[] $headers     Additional headers.
				 *     @type string|string[] $attachments Paths to files to attach.
				 * }
				 */
				$pre_wp_mail = apply_filters( 'pre_wp_mail', null, $atts );

				if ( null !== $pre_wp_mail ) {

					return $pre_wp_mail;

				}
				
				$result = $postmanWpMail->send ( $to, $subject, $message, $headers, $attachments );
				
				if( $result ) {
					do_action( 'wp_mail_succeeded', $atts );
				} 

				// return the result
				return $result;
			}
			$this->logger->debug ( 'Bound to wp_mail()' );
			$this->bound = true;
		}
		public function isBound() {
			return $this->bound;
		}
		public function isUnboundDueToException() {
			return $this->bindError;
		}
	}
}