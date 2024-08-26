import { __, sprintf } from '@wordpress/i18n';

import LogoMark from '../../components/logo-mark';
import { registerBlockVariation } from "@wordpress/blocks";

import './controls'; // Load our controls

const VARIATION_NAME = 'courier/courier-notices';

registerBlockVariation( 'core/query', {
    name: VARIATION_NAME,
    title: __( 'Courier Notices', 'courier-notices' ),
    description: __( 'Displays the Child pages of a proposal.', 'courier-notices' ),
    isActive: ( { namespace, query } ) => {
      return (
        namespace === VARIATION_NAME
        && query.postType === 'courier_notice'
      );
    },
    isCourierNoticesQueryLoopVariation: ( { namespace, query } ) => {
      return (
        namespace === VARIATION_NAME
      );
    },
    icon: LogoMark,
    attributes: {
      namespace: VARIATION_NAME,
      query: {
        perPage: 100,
        pages: 0,
        paged: 1,
        offset: 0,
        postType: 'courier_notice',
        orderBy: 'menu_order',
        order: 'asc',
        author: '',
        search: '',
        exclude: [],
        sticky: '',
        inherit: false,
      },
      parentID: null,
    },
    scope: [ 'inserter' ],
    innerBlocks : [
      [
        'core/post-template',
        {},
        [ [ 'core/post-content' ] ],
      ],
    ],
    allowedControls: [ 'postType', 'search', 'taxQuery', 'filters' ],
	allowedBlocks: [
		"courier/courier-notices"
	],
  }
);
