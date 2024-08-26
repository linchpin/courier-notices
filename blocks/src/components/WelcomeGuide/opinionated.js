import { __, sprintf } from '@wordpress/i18n';

const Opinionated = () => {
	return(
		<div style={{
			'margin': '10px',
			'max-width': '700px'
		}}>
			<h4>{__('An Opinionated Responsive Approach', 'adspiration')}</h4>
			<p>{__('By default Adspiration banners are expecting to be responsive and allow for scaling to any viewport/device regardless of size', 'adspiration' )}</p>
			<p>{__('Realistically this can be an difficult based on art direction/design and use case...', 'adspiration' )}</p>
			<p>{__('Adspiration provides the ability to hide banners individuals based on different breakpoints', 'adspiration' )}</p>
		</div>
	)
}

export default Opinionated;
