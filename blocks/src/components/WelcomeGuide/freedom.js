import { __, sprintf } from '@wordpress/i18n';

const Freedom = () => {
	return(
		<div style={{
			'margin': '10px',
			'max-width': '500px'
		}}>
			<h4>{__('Flexibility to show content how you want', 'adspiration' )}</h4>
			<p>{__('This allow you to easily display contextually designed banners at any view port.', 'adspiration' )}</p>
		</div>
	)
}

export default Freedom;
