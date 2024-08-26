import { Tooltip, Icon } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

// Internal Components
import Overlap from '../../../icons/overlap';

const WarningTooltip = ( { hasDuplicateAttributes } ) => {

	if ( ! hasDuplicateAttributes ) {
		return null;
	}

	return (
		<span className="warning-indicator"><Icon style={{fill:'red'}} icon={<Overlap />} size={24} /></span>
	);
};

export default WarningTooltip;
