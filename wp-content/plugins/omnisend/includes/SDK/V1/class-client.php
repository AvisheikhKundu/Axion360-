<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\SDK\V1;

defined( 'ABSPATH' ) || die( 'no direct access' );

/**
 * Client to interact with Omnisend.
 *
 */
interface Client {

	/**
	 * Create a contact in Omnisend. For it to succeed ensure that provided contact at least have email or phone number.
	 *
	 * @param Contact $contact
	 *
	 * @return CreateContactResponse
	 * @deprecated Use save_contact() instead.
	 */
	public function create_contact( $contact ): CreateContactResponse;

	/**
	 * Send customer event to Omnisend. Customer events are used to track customer behavior and trigger automations based on that behavior.
	 *
	 * @param Event $event
	 *
	 * @return SendCustomerEventResponse
	 */
	public function send_customer_event( $event ): SendCustomerEventResponse;

	/**
	 * Save a contact in Omnisend.
	 * @param Contact $contact
	 *
	 * @return SaveContactResponse
	 */
	public function save_contact( Contact $contact ): SaveContactResponse;

	/**
	 * Get contact in Omnisend by Email.
	 *
	 * @param string $email
	 *
	 * @return GetContactResponse
	 */
	public function get_contact_by_email( string $email ): GetContactResponse;
}
