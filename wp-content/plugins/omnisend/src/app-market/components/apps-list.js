import {
	Button,
	Card,
	CardHeader,
	CardBody,
	CardFooter,
	Flex,
} from '@wordpress/components';

const AppsList = ({ apps, categoryName, categoryDescription }) => {
	const navigateToPluginPage = (url) => {
		window.open(url, '_blank').focus();
	};

	return (
		<>
			<div className="omnisend-spacing-mb-8">
				{categoryName && (
					<div className="omnisend-wp-h2">{categoryName}</div>
				)}
				{categoryDescription && <div>{categoryDescription}</div>}
			</div>
			<Flex
				gap={6}
				wrap={true}
				justify="start"
				className="omnisend-apps-list-container"
			>
				{apps &&
					apps.map((app) => (
						<Card
							key={app.slug}
							size={'medium'}
							isBorderless={true}
							backgroundSize={50}
							className="omnisend-apps-list-card"
						>
							<Flex direction="column">
								<CardHeader isBorderless="true">
									<Flex direction="column">
										<img
											alt={app.name}
											className="omnisend-apps-list-card-logo"
											src={app.logo}
										/>
										<div className="omnisend-wp-h4">
											{app.name}
										</div>

										<div className="omnisend-wp-text-mini">
											by {app.created_by}
										</div>
									</Flex>
								</CardHeader>
								<CardBody className="omnisend-apps-list-card-description-container">
									<div className="omnisend-wp-text-body">
										{app.description}
									</div>
								</CardBody>
								<CardFooter isBorderless={true}>
									<Button
										variant="primary"
										onClick={() =>
											navigateToPluginPage(app.url)
										}
									>
										Add this add-on
									</Button>
								</CardFooter>
							</Flex>
						</Card>
					))}
			</Flex>
		</>
	);
};

export default AppsList;
