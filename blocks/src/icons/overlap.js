import {SVG, Path, Rect} from '@wordpress/primitives';

const Overlap = () => {
	return (
		<SVG style={{fill:'red'}} width="24px" height="24px" viewBox="0 0 24 24">
			<Path d="M9,5a7,7,0,1,0,7,7A7,7,0,0,0,9,5ZM9,17a5,5,0,1,1,5-5A5,5,0,0,1,9,17Z"/>
			<Path d="M15,5a7,7,0,1,0,7,7A7,7,0,0,0,15,5Zm0,12a5,5,0,1,1,5-5A5,5,0,0,1,15,17Z"/>
			<Rect width="24" height="24" fill="none"/>
		</SVG>
	);
}

export default Overlap;
