import ConnectionLogos from './connection-logos';
import ConnectionFeatures from './connection-features';
import ConnectionSteps from './connection-steps';
import { Notice, Flex, Spinner } from '@wordpress/components';
import { useState } from '@wordpress/element';

const ConnectionPageLayout = () =>
{
	const [ error, setError ] = useState( null );
	const [ loading, setLoading ] = useState( null );

	const handleSiteConnection = ( apiKey ) =>
	{
		setLoading( true );
		const formData = new FormData();
		formData.append( 'api_key', apiKey );
		formData.append( 'action_nonce', omnisend_connection.action_nonce );

		fetch( omnisend_connection.site_url + '/wp-json/omnisend/v1/connect', {
			method: 'POST',
			body: formData,
			headers: {
				'x-wp-nonce': omnisend_connection.nonce,
			}
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) =>
			{
				if ( data.success )
				{
					location.reload();
				}
				if ( data.error )
				{
					setError( data.error );
					setLoading( false );
				}
			} )
			.catch( ( e ) =>
			{
				setError( e.message || e );
				setLoading( false );
			} );
	};

	if ( loading )
	{
		return (
			<Flex justify="center">
				<div className="omnisend-spacing-mt-6">
					<Spinner />
				</div>
			</Flex>
		);
	}

	return (
		<>
			<div className="omnisend-page-layout">
				{error && (
					<div className="omnisend-spacing-mb-8">
						<Notice className='omnisend-notice' status="error">{error}</Notice>
					</div>
				)}
				<ConnectionLogos />
				<div className="omnisend-spacing-mv-8">
					<div className="omnisend-h1">Connect Omnisend plugin</div>
				</div>
				<ConnectionFeatures />
				<div className="omnisend-spacing-mv-16">
					<hr className="omnisend-divider" />
				</div>
				<div className="omnisend-h1">Steps to connect to Omnisend:</div>
				<ConnectionSteps onSubmit={handleSiteConnection} />
			</div>
		</>
	);
};

export default ConnectionPageLayout;
