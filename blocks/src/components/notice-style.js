import _ from 'lodash';

// Internal Components
import Informational from './layouts/informational';
import PopupModal from './layouts/popup-modal';
import SlideIn from './layouts/slide-in';

const NoticeStyle = (props) => {

	const { attributes, setAttributes, onTitleChange, styles, customization } = props;
	const { style } = attributes;

	let activeStyle = {
		slug: 'informational'
	};

	if ( styles && styles.length > 0  ) {
		activeStyle = _.find( styles, (obj) => obj.id === style );
	}

	return(
		<>
			{ 'informational' === activeStyle.slug && <Informational {...props} /> }
			{ 'popup-modal' === activeStyle.slug && <PopupModal {...props} /> }
			{ 'slide-in' === activeStyle.slug && <SlideIn {...props} /> }
		</>
	);
}

export default NoticeStyle;
