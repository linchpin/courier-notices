/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * Using a plethora of component from the BlockEditor library within
 * our component
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 * @see https://developer.wordpress.org/block-editor/components/
 */
import {
	InnerBlocks,
	BlockControls,
	InspectorControls,
	PanelColorSettings,
	useBlockProps,
} from '@wordpress/block-editor';

import {
	useSelect,
	useDispatch
} from '@wordpress/data';

import { useCallback } from '@wordpress/element';
import { useEntityProp } from '@wordpress/core-data';
import { __experimentalUseEntityRecords as useEntityRecords } from '@wordpress/core-data';
import { PluginPostStatusInfo } from '@wordpress/edit-post';

/**
 * Using a plethora of component from the BlockEditor library within
 * our component
 *
 * @see https://developer.wordpress.org/block-editor/components/
 */
import {
	Toolbar,
	ToolbarGroup,
	CheckboxControl, Spinner
} from '@wordpress/components';

import InformationalNoticeURL, {
	ReactComponent as InformationalNotice,
} from '../../icons/courier-informational.svg';
import FlyoutNoticeURL, {
	ReactComponent as FlyoutNotice,
} from '../../icons/courier-flyout.svg';
import ModalNoticeURL, {
	ReactComponent as ModalNotice,
} from '../../icons/courier-modal.svg';

import { updateMeta } from '../../helpers/utilities';

// Internal Components
import CourierNoticesBlockPanel from '../../components/block-panel';
import TypeControls from '../../components/type-controls';
import NoticeStyle from '../../components/notice-style';

import {setStyleOrClass} from '../../helpers/utilities';
import {useRef} from "@wordpress/element";

const DEFAULT_STATE = {
	style: 'informational',
	type: 'info',
};

const actions = {
	setType( item, _type ) {
		return {
			type: 'SET_TYPE',
			item,
			courier_type,
		};
	},

	setStyle( item, courier_style ) {
		return {
			type: 'SET_STYLE',
			item,
			courier_style,
		};
	},

	setStyles( styles ) {
		return {
			type: 'SET_STYLES',
			styles,
		};
	},

	fetchFromAPI( path ) {
		return {
			type: 'FETCH_FROM_API',
			path,
		};
	},
};

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @since 1.6.0
 *
 * @param attributes
 * @param setAttributes
 * @param className
 * @param isSelected The isSelected property is an object that communicates whether the block is currently selected.
 * @param postType
 * @param postId
 * @param props
 * @returns {WPElement}
 * @constructor
 */
const Edit = ( {
	  attributes,
	  setAttributes,
	  className,
	  isSelected,
	  context: { postType, postId },
	  props
	} ) => {

//	const { courier_notice, isResolving } = useEntityRecords( 'postType', postType, { ID:postId } );

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const getTerms = ( select, taxonomy ) => {
		const terms = select( 'core' ).getEntityRecords(
			'taxonomy',
			taxonomy,
			{
				per_page: 100,
				show_empty: true,
			}
		);

		return terms ?? [];
	}

	/**
	 * Get all the taxonomy terms associated with our courier_notice.
	 *
	 * @since 1.6.0
	 */
	const { styles, types, placements } = useSelect( ( select ) => {
		return {
			styles     : getTerms( select, 'courier_style' ),
			types      : getTerms( select, 'courier_type' ),
			placements : getTerms( select, 'courier_placement' ),
		};
	} );

	/**
	 * Get the selected terms currently associated with this specific notice
	 *
	 * @since 1.6.0
	 */
	const courier_style = useSelect((select) => {
		return select('core').getEntityRecords( 'taxonomy', 'courier_style' );
	});

	// Simplify access to attributes
	const { content, alignment, showHideTitle, noticeTitle, icon, style, type } = attributes;
/*
	const {
		textColor,
		setTextColor,
		backgroundColor,
		setBackgroundColor,
		iconColor,
		setIconColor,
		accentColor,
		setAccentColor,
	} = props;  // Props received from withColors

	let customization = {
		textClass : undefined,
		textStyles : {},
		backgroundClass : undefined,
		backgroundStyles : {},
		iconClass : undefined,
		iconStyles : {},
		accentClass : undefined,
		accentStyles : {},
	}

	customization.textClass        = setStyleOrClass( textColor, 'class' );
	customization.textStyles       = setStyleOrClass( textColor, 'style' );
	customization.backgroundClass  = setStyleOrClass( backgroundColor, 'class' );
	customization.backgroundStyles = setStyleOrClass( backgroundColor, 'style' );
	customization.iconClass        = setStyleOrClass( iconColor, 'class' );
	customization.iconStyles       = setStyleOrClass( iconColor, 'style' );
	customization.accentClass      = setStyleOrClass( accentColor, 'class' );
	customization.accentStyles     = setStyleOrClass( accentColor, 'style' ); */

	const { editPost } = useDispatch( 'core/editor' );

	const ref        = useRef();
	const blockProps = useBlockProps( { ref } );

	/*
	const onTitleChange = useCallback(
		( title ) => {
			courier_notice.edit( { title } );
		},
		[ courier_notice.edit ]
	); */

//	console.log( courier_notice );

	if ( ! courier_style || ! styles || ! types || ! placements ) {
		return (
			<div>
				<Spinner />{__( 'Loading Notice Details', 'courier-notices' )}
			</div>
		)
	}

	// Change the post title after we edit our courier notice title
	const onChangeContent = (newContent) => {
		setAttributes({ content: newContent });
	};

	/**
	 * When we change the title of our block be sure to change the title of our post
	 *
	 * @param newTitle
	 */
	const onChangeTitle = (newTitle) => {
		setAttributes({ title: newTitle });
		editPost({ title: newTitle });
	};

	return (
		<div { ...useBlockProps() }>
			{
				<BlockControls>
					<Toolbar label={__('Notice Styles', 'courier-notices')}>
						<ToolbarGroup>
							<TypeControls
								meta={meta}
								setMeta={setMeta}
								styles={styles}
								postType={postType}
								postId={postId}
							/>
						</ToolbarGroup>
					</Toolbar>
				</BlockControls>
			}
			<PluginPostStatusInfo>
				<CheckboxControl
					className="courier-notices-is-dismissible"
					label={__('Is Dismissible?', 'courier-notices')}
					checked={meta._courier_dismissible}
					onChange={ ( value ) => updateMeta( '_courier_dismissible', value ) }
					help={__('Allow this notice to be dismissed by users', 'courier-notices')}
				/>
			</PluginPostStatusInfo>
			<InspectorControls>
				{ attributes &&
				<CourierNoticesBlockPanel
					attributes={attributes}
					setAttributes={setAttributes}
					meta={meta}
					setMeta={setMeta}
					postId={postId}
					postType={postType}
				/>
				}
			</InspectorControls>
			<NoticeStyle
				attributes={attributes}
				setAttributes={setAttributes}
				meta={meta}
				setMeta={setMeta}
			/>
		</div>
	);
}

export default Edit;
