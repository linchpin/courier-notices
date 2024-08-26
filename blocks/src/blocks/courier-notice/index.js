import { InnerBlocks, RichText, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { registerBlockType, createBlock } from "@wordpress/blocks";
import { dispatch, useDispatch, useSelect } from "@wordpress/data";
import { useState } from '@wordpress/element';
import classNames from 'classnames';

import metadata from './block.json';
import Edit from './edit';

import {
	CardHeader,
	Flex,
	FlexBlock,
	FlexItem,
	Icon,
	ToggleControl,
	Toolbar,
	ToolbarButton,
	ToolbarDropdownMenu,
	TabPanel,
	ToolbarGroup
} from "@wordpress/components";
import {__, sprintf} from "@wordpress/i18n";
import {addCard as AddCardIcon, seen as SeenIcon} from "@wordpress/icons";
import {Path, SVG} from "@wordpress/primitives";
import _ from "lodash";

registerBlockType(
	metadata, {
	edit: Edit,
	save(props) {
		const blockProps = useBlockProps.save();

		return (
			<div { ...blockProps }>
				<InnerBlocks.Content />
			</div>
		);
	},
} );
