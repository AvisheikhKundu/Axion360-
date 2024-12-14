import { render } from "@wordpress/element";
import ConnectionPageLayout from "./components/connection-page-layout";

render(
	<ConnectionPageLayout />,
	document.getElementById( "omnisend-connection" ),
);
