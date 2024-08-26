import { InspectorControls } from '@wordpress/block-editor';
import { addFilter } from '@wordpress/hooks';
import { PanelBody } from "@wordpress/components";
import CourierNoticeFilter from './filter';

export const withCourierNoticesQueryControls = ( BlockEdit ) => ( props ) => {

  const isCourierNoticesQueryLoopVariation = ( props ) => {
    const {
      attributes: {
        namespace,
      },
    } = props;

    return (
      namespace === 'courier/courier-notices'
    );
  }

  return isCourierNoticesQueryLoopVariation( props ) ? (
    <>
      <BlockEdit { ...props } />
      <InspectorControls>
        <PanelBody>
          <CourierNoticeFilter {...props } />
        </PanelBody>
      </InspectorControls>
    </>
  ) : (
      <BlockEdit { ...props } />
  );
};

addFilter( 'editor.BlockEdit', 'core/query', withCourierNoticesQueryControls );