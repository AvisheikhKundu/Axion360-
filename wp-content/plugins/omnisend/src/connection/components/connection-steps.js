import { useState } from '@wordpress/element';
import { Button, Flex, FlexItem, TextControl } from '@wordpress/components';

const ConnectionSteps = ({ onSubmit }) => {
	const [apiKey, setApiKey] = useState('');

	const navigateToExternalUrl = (url) => {
		window.open(url, '_blank').focus();
	};

	return (
		<>
			<div className="omnisend-spacing-mv-8">
				<div className="omnisend-spacing-mb-4">
					<div className="omnisend-wp-text-list">1. Create Omnisend account</div>
				</div>
				<Button
					variant="secondary"
					onClick={() => navigateToExternalUrl('https://app.omnisend.com/registrationv2?utm_source=wordpress_plugin&utm_content=connect_store')}
				>
					Go to Omnisend
				</Button>
			</div>
			<hr className="omnisend-divider" />
			<div className="omnisend-spacing-mv-8">
				<div className="omnisend-spacing-mb-4">
					<div className="omnisend-wp-text-list">2. Go to API keys section and create API key</div>
				</div>
				<Button
					variant="secondary"
					onClick={() => navigateToExternalUrl('https://app.omnisend.com/apps/connect-store/wordpress?source=wordpress%20plugin&utm_content=api_keys')}
				>
					Go to API keys
				</Button>
			</div>
			<hr className="omnisend-divider" />
			<div className="omnisend-spacing-mv-8">
				<div className="omnisend-spacing-mb-4">
					<div className="omnisend-wp-text-list">3. Paste created API key here:</div>
				</div>
				<Flex align={"'start'"} gap={4} wrap="true">
					<FlexItem display="flex" className="omnisend-connection-input-wrap">
						<TextControl
							value={apiKey}
							className="omnisend-connection-input"
							onChange={(nextValue) => setApiKey(nextValue ?? '')}
						/>
					</FlexItem>
					<FlexItem>
						<Button disabled={!apiKey} variant="primary" size="compact" type="submit" onClick={() => onSubmit(apiKey)}>
							Connect Omnisend
						</Button>
					</FlexItem>
				</Flex>
			</div>
		</>
	);
};

export default ConnectionSteps;
