import { Guide } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { useDispatch, useSelect } from "@wordpress/data";
import { store as preferencesStore } from '@wordpress/preferences';
import { __ } from '@wordpress/i18n';
/**
 * Internal Components
 */
import Overview from './overview';
import Opinionated from './opinionated';
import Freedom from './freedom';

/**
 * Show a welcome guide if the user has not used Adspiration before.
 *
 * This is useful as the block is optionated on how it _should_ be used
 * and it can be confusing if you haven't used the philosophy before.
 *
 * @param props
 * @constructor
 */
const WelcomeGuide = (props ) => {
	const { toggle, set } = useDispatch( preferencesStore );

	const isOpen = useSelect( ( select ) => {
		let isGuideActive = select( preferencesStore ).get(
			'linchpin/adspiration',
			'welcomeGuide'
		);

		if ( isGuideActive === null || isGuideActive === undefined ) {
			isGuideActive = true;
		}

		return isGuideActive;
	}, [] );

	if ( ! isOpen ) {
		return null;
	}

	return (
		<Guide
			onFinish={ () => toggle( 'linchpin/adspiration', 'welcomeGuide' ) }
			pages={ [
				{
					content: <Overview />,
					next:__( 'Get Started', 'adspiration' )
				},
				{
					content: <Opinionated />
				},
				{
					content: <Freedom />
				}
			] }
		/>
	)
}

export default WelcomeGuide;
