import {InnerBlocks} from "@wordpress/block-editor";
import {__} from "@wordpress/i18n";
import classNames from "classnames";

const Informational = ( props ) => {

	const {
		attributes,
		setAttributes,
		onTitleChange,
		meta,
		setMeta
	} = props;

	const {
		showHideTitle,
		isDismissible,
		noticeTextColor,
		noticeBackgroundColor,
		noticeIconColor,
		noticeAccentColor,
		noticeDisplayType
	} = attributes;

	let TEMPLATE = [
		[
			'core/heading',
			{
				placeholder: __('Courier Notice Title', 'courier-notices' ),
				level: 6,
				onChange: onTitleChange,
			},
		],
		[
			'core/paragraph',
			{
				placeholder: __( 'Share information with your visitors', 'courier-notices' ),
			},
		],
	];

	if ( false === showHideTitle ) {
		TEMPLATE.shift(0);
	}

	return (
		<div data-alert
			className={classNames( 'courier-notice', 'courier_notice', 'alert', 'alert-box', 'courier_type-success' )}
			data-closable={isDismissible ? 'data-closable' : ''}
		>
			<div className="courier-content-wrapper">
				<div className={ classNames('courier-icon' ) }></div>
				<div>
					<InnerBlocks template={TEMPLATE} templateLock="all" />
				</div>
				{ isDismissible && (
					<a href="#" className={classNames('courier-close','close')}>
						&times;
					</a>
				) }
			</div>
		</div>
	);
}

export default Informational;
