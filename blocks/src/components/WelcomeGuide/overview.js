import { __, sprintf } from '@wordpress/i18n';

const Overview = () => {
	return(
		<div style={{
			'margin': '10px',
			'max-width': '500px'
		}}>
			<h4>{__('Welcome to Adspiration', 'adspiration')}</h4>
			<p>{__('This Linchpin plugin is designed to extend the capabilities of the WordPress block editor by enabling the creation of responsive and reusable banners. These banners can be dynamically injected into various pages using the included Banner Block.', 'adspiration' )}</p>
			<p>{__('We find our plugin is perfect for developers and content creators looking to enhance their website with customizable and engaging banner content that can be easily managed and deployed across your site.', 'adspiration' )}</p>
		</div>
	)
}

export default Overview;
