import {
  Spinner,
} from "@wordpress/components";
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

const CourierNoticeFilter = ( { attributes, setAttributes }  ) => {

  const {
    query,
  } = attributes;

  const postId = useSelect( ( select ) => select( 'core/editor' ).getCurrentPostId() );

  if ( ! postId ) {
    return <div><Spinner /></div>;
  }

  useEffect(() => {
    setAttributes( {
      query: {
        ...query,
        parents: [postId],
      },
      parentId: postId
    } );
  }, [ postId ] );

	return (
    <>
      {JSON.stringify(attributes)}
    </>
  );
}
export default CourierNoticeFilter;
