<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\Internal;

use Omnisend\SDK\V1\Contact;

class ContactFactory {

	/**
	 * Create a Contact object from an array of contact data.
	 *
	 * @param array $contact_data
	 * @return Contact
	 */
	public static function create_contact( array $contact_data ): Contact {
		$contact = new Contact();

		if ( isset( $contact_data['contactID'] ) ) {
			$contact->set_id( $contact_data['contactID'] );
		}

		if ( isset( $contact_data['firstName'] ) ) {
			$contact->set_first_name( $contact_data['firstName'] );
		}

		if ( isset( $contact_data['email'] ) ) {
			$contact->set_email( $contact_data['email'] );
		}

		if ( isset( $contact_data['lastName'] ) ) {
			$contact->set_last_name( $contact_data['lastName'] );
		}

		if ( isset( $contact_data['country'] ) ) {
			$contact->set_country( $contact_data['country'] );
		}

		if ( isset( $contact_data['address'] ) ) {
			$contact->set_address( $contact_data['address'] );
		}

		if ( isset( $contact_data['city'] ) ) {
			$contact->set_city( $contact_data['city'] );
		}

		if ( isset( $contact_data['state'] ) ) {
			$contact->set_state( $contact_data['state'] );
		}

		if ( isset( $contact_data['postalCode'] ) ) {
			$contact->set_postal_code( $contact_data['postalCode'] );
		}

		if ( isset( $contact_data['phone'] ) ) {
			$contact->set_phone( $contact_data['phone'][0] );
		}

		if ( isset( $contact_data['birthdate'] ) ) {
			$contact->set_birthday( $contact_data['birthdate'] );
		}

		if ( isset( $contact_data['gender'] ) ) {
			$contact->set_gender( $contact_data['gender'] );
		}

		if ( isset( $contact_data['tags'] ) ) {
			foreach ( $contact_data['tags'] as $tag ) {
				$contact->add_tag( $tag );
			}
		}

		if ( isset( $contact_data['customProperties'] ) ) {
			foreach ( $contact_data['customProperties'] as $key => $value ) {
				$contact->add_custom_property( $key, $value, false );
			}
		}

		if ( isset( $contact_data['identifiers'] ) ) {
			foreach ( $contact_data['identifiers'] as $single_consent ) {
				if ( isset( $single_consent['channels']['sms']['status'] ) ) {
					if ( $single_consent['channels']['sms']['status'] == 'subscribed' ) {
						$contact->set_phone_subscriber();
					} elseif ( $single_consent['channels']['sms']['status'] == 'unsubscribed' ) {
						$contact->set_phone_unsubscriber();
					}
				}

				if ( isset( $single_consent['channels']['email']['status'] ) ) {
					if ( $single_consent['channels']['email']['status'] == 'subscribed' ) {
						$contact->set_email_subscriber();
					} elseif ( $single_consent['channels']['email']['status'] == 'unsubscribed' ) {
						$contact->set_email_unsubscriber();
					}
				}
			}
		}

		return $contact;
	}
}
