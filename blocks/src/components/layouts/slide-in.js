import {InnerBlocks} from "@wordpress/block-editor";

const SlideIn = (props ) => {

	const { attributes, setAttributes } = props;
	const isDismissible = false;

	return (
		<div
			className='courier-notices-slide-in'
			data-closable={isDismissible ? 'data-closable' : ''}
		>
			<div>I am a big slide in</div>
		</div>
	);
}

export default SlideIn;
