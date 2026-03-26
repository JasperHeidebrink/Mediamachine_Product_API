<?php
/**
 * Flash Messages helper class
 */

namespace MMProductApi\Admin;

class FlashMessages {
	/**
	 * @var string[]
	 */
	protected $classes = [ 'error', 'updated' ];

	/**
	 * @var array
	 */
	static protected $messages = [];

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! session_id() ) {
			session_start();
		}
		add_action( 'admin_notices', [ $this, 'show_flash_message' ] );
	}

	/**
	 * End session on logout and login
	 *
	 * @return void
	 */
	public function clear_session(): void {
		unset( $_SESSION['flash_messages'] );
	}

	/**
	 * Set messages in array
	 *
	 * @param array $messages Array of messages to set
	 *
	 * @return void
	 */
	public function set_flash_messages( string $messages, string $type = 'error' ): void {
		if ( isset( $_SESSION['flash_messages'][ $type ] ) ) {
			$_SESSION['flash_messages'] = [ $type => $messages ];

			return;
		}

		$_SESSION['flash_messages'][ $type ][] = $messages;
	}

	/**
	 * Get messages
	 *
	 * @return array
	 */
	public function get_flash_messages(): array {
		if ( isset( $_SESSION['flash_messages'] ) ) {
			return $_SESSION['flash_messages'];
		}

		return [];
	}

	/**
	 * Queue flash messages
	 *
	 * @param string $name Name of message. updated or error
	 * @param string $message Message body
	 *
	 * @return $this
	 */
	public function queue_flash_message( string $name, array $message ): self {
		$messages      = [];
		$classes       = apply_filters( 'flashmessage_classes', $this->classes );
		$default_class = apply_filters( 'flashmessages_default_class', 'updated' );

		$class = $name;
		if ( ! in_array( $name, $classes ) ) {
			$class = $default_class;
		}

		$messages[ $class ][] = $message;

		$this->set_flash_messages( $messages );

		return $this;
	}

	/**
	 * Get flash message
	 *
	 * @return mixed
	 */
    public function show_flash_message(): void
    {
        $messages = $this->get_flash_messages();

        if ( ! is_array($messages) ) {
            $this->display_flash_message_html([$messages], 'error');
            $this->clear_session();
        }

        foreach ($messages as $class => $message) {
            if ( ! is_array($message) ) {
                $message = [$message];
            }
            $this->display_flash_message_html($message, $class);
        }

        $this->clear_session();
    }

	/**
	 * Display message HTML
	 *
	 * @param array  $messages Array of messages
	 * @param string $class Message CSS class
	 *
	 * @return void
	 */
	private function display_flash_message_html( array $messages, string $class ): void {
		foreach ( $messages as $message ) {
			$message_html = "<div id=\"message\" class=\"{$class}\"><p>{$message}</p></div>";

			echo apply_filters( 'flashmessage_html', $message_html, $message, $class );
		}
	}
}
