import { Card, CardBody, Flex } from '@wordpress/components';

const AppsListNotice = () => {
	return (
		<Card isBorderless={true} size="large">
			<CardBody isBorderless={true}>
				<Flex direction="column">
					<div className="omnisend-wp-h1">Omnisend Add-Ons</div>
					<div className="omnisend-apps-list-notice-text omnisend-wp-text-body">
						You can expand the possibilities of Omnisend by integrating it with additional add-ons.
					</div>
				</Flex>
			</CardBody>
		</Card>
	);
};

export default AppsListNotice;
