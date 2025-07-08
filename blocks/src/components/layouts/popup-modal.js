import {InnerBlocks} from "@wordpress/block-editor";
import {__} from "@wordpress/i18n";

const PopupModal = ( props ) => {

	const { attributes, setAttributes, onTitleChange } = props;
	const { showHideTitle, isDismissible } = attributes;

	let TEMPLATE = [
		[
			'core/heading',
			{
				placeholder: __('Courier Notice Title', 'courier-notices' ),
				level: 6,
				//	className: noticeTextColor,
				onChange: onTitleChange,
			},
		],
		[
			'core/paragraph',
			{
				placeholder: __(
					__( 'Share information with your visitors', 'courier-notices' ),
					'courier-notices'
				),
				//	className: noticeTextColor,
			},
		],
	];

	if ( false === showHideTitle ) {
		TEMPLATE.shift(0);
	}

	return (
		<div
			className="courier-notices-modal"
			data-closable={isDismissible ? 'data-closable' : ''}
		>
			<div>I am a big ol popup</div>
			<div className="courier-content-wrapper">
				<div>
					<InnerBlocks template={TEMPLATE} templateLock="all" />
				</div>
				{ isDismissible && (
					<a href="#" className="courier-close close">
						&times;
					</a>
				) }
			</div>
		</div>
	);
}

export default PopupModal;
