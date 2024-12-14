import { Flex, Button } from '@wordpress/components';

const ConnectedPageLayout = () => {
	const navigateToExternalUrl = (url) => {
		window.open(url, '_blank').focus();
	};

	return (
		<>
			<Flex
				className="omnisend-page-layout"
				justify="center"
				direction="column"
				align="start"
			>
				<img
					className="omnisend-connected-page-logo"
					src="/wp-content/plugins/omnisend/assets/img/omnisend-logo.svg"
					alt="logo"
					border="0"
				/>
				<div className="omnisend-spacing-mb-4">
					<h1 className="omnisend-wp-h1">
						You are connected to Omnisend
					</h1>
				</div>
				<div className="omnisend-spacing-mb-8">
					<div className="omnisend-wp-text-body omnisend-connected-page-text">
						Head to Omnisend to continue with next steps for getting
						your account up and running or explore our add-ons.
					</div>
				</div>
				<Flex justify="start" gap={4}>
					<Button
						variant="primary"
						onClick={() =>
							navigateToExternalUrl('https://app.omnisend.com')
						}
					>
						Go to Omnisend
					</Button>
					<p>or</p>
					<Button
						variant="secondary"
						onClick={() => {
							window.location.href =
								'admin.php?page=omnisend-app-market';
						}}
					>
						Explore Omnisend add-ons
					</Button>
				</Flex>
			</Flex>
		</>
	);
};

export default ConnectedPageLayout;
