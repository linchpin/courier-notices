/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@humanmade/block-editor-components/dist/index.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@humanmade/block-editor-components/dist/index.js ***!
  \***********************************************************************/
/***/ (function(module) {

/*! For license information please see index.js.LICENSE.txt */
!function(e,t){ true?module.exports=t():0}(self,(()=>(()=>{var e={184:(e,t)=>{var n;!function(){"use strict";var o={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var l=typeof n;if("string"===l||"number"===l)e.push(n);else if(Array.isArray(n)){if(n.length){var i=r.apply(null,n);i&&e.push(i)}}else if("object"===l){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var a in n)o.call(n,a)&&n[a]&&e.push(a)}}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(n=function(){return r}.apply(t,[]))||(e.exports=n)}()},703:(e,t,n)=>{"use strict";var o=n(414);function r(){}function l(){}l.resetWarningCache=r,e.exports=function(){function e(e,t,n,r,l,i){if(i!==o){var a=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw a.name="Invariant Violation",a}}function t(){return e}e.isRequired=e;var n={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:l,resetWarningCache:r};return n.PropTypes=n,n}},697:(e,t,n)=>{e.exports=n(703)()},414:e=>{"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"}},t={};function n(o){var r=t[o];if(void 0!==r)return r.exports;var l=t[o]={exports:{}};return e[o](l,l.exports,n),l.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var o in t)n.o(t,o)&&!n.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var o={};return(()=>{"use strict";n.r(o),n.d(o,{ConditionalComponent:()=>t,FetchAllTermSelectControl:()=>m,FileControls:()=>b,GenericServerSideEdit:()=>k,ImageControl:()=>_,InnerBlockSlider:()=>L,InnerBlocksDisplaySingle:()=>I,LinkToolbar:()=>D,PlainTextWithLimit:()=>q,PostPickerButton:()=>X,PostPickerModal:()=>J,PostPickerToolbarButton:()=>K,PostTitleControl:()=>W,PostTypeCheck:()=>$,RichTextWithLimit:()=>H,TermSelector:()=>V,createOptionFromPost:()=>be,createOptionFromTerm:()=>fe,createOptionsFromPosts:()=>ge,createOptionsFromPostsWithHierarchy:()=>ke,createOptionsFromTerms:()=>he,createOptionsFromTermsWithHierarchy:()=>ye,findBlockByName:()=>ce,findInvalidBlock:()=>se,findInvalidBlocks:()=>ue,findValidBlock:()=>de,findValidBlocks:()=>me,getImageDataForSize:()=>h,useActiveBlockStyle:()=>ee,useBlockStyles:()=>te,useDisallowedBlocks:()=>ne,useMeta:()=>oe,usePostThumbnail:()=>re,useRenderAppenderWithBlockLimit:()=>le,useSelectBlock:()=>ie,useSetAttribute:()=>ae,withActiveVariation:()=>ve});const e=window.React;function t(t){const{children:n=null,ComponentFalse:o=(()=>null),ComponentTrue:r=(()=>n),predicate:l,...i}=t,a=l(i)?r:o;return(0,e.createElement)(a,{...i})}const r=window.wp.apiFetch;var l=n.n(r);const i=window.wp.components,a=window.wp.data,c=window.wp.i18n,s=window.wp.url,u={label:"",value:""},d={disabled:!0,label:(0,c.__)("No items found!","block-editor-components"),value:""};const m=function(t){const{defaultOption:n=u,fallbackOption:o=d,taxonomy:r,...m}=t,[p,b]=(0,e.useState)(),[f,g]=(0,e.useState)(),k=(0,a.useSelect)((e=>e("core").getTaxonomy(r)?.rest_base),[r]);return(0,e.useEffect)((()=>{k&&(async()=>{try{const e=await l()({path:(0,s.addQueryArgs)(`/wp/v2/${k}`,{_fields:"id,name",context:"view",per_page:-1})});if(!e?.length)return void g(o?[o]:[]);g([...n?[n]:[],...he(e)])}catch(t){var e;b(null!==(e=t.message)&&void 0!==e?e:(0,c.__)("Unknown error.","block-editor-components"))}})()}),[k,n,o]),p?(0,e.createElement)(i.Notice,{isDismissible:!1,status:"error"},(0,e.createElement)("p",null,p)):f?(0,e.createElement)(i.SelectControl,{...m,options:f}):(0,e.createElement)(i.Spinner,null)},p=window.wp.blockEditor;function b(t){const{value:n,onChange:o,...r}=t;return(0,e.createElement)(p.MediaUploadCheck,null,(0,e.createElement)(p.MediaUpload,{title:(0,c.__)("Select or Upload File","block-editor-components"),...r,multiple:!1,render:({open:t})=>(0,e.createElement)(i.ToolbarGroup,null,(0,e.createElement)(i.ToolbarButton,{icon:"admin-links",label:n?(0,c.__)("Edit file","block-editor-components"):(0,c.__)("Select file","block-editor-components"),onClick:t}),n&&(0,e.createElement)(i.ToolbarButton,{icon:"editor-unlink",label:(0,c.__)("Deselect file","block-editor-components"),onClick:()=>o(null)})),value:n,onSelect:o}))}const f=window.wp.serverSideRender;var g=n.n(f);const k=function({attributes:t,context:n,name:o}){return(0,e.createElement)("div",{...(0,p.useBlockProps)()},(0,e.createElement)(i.Disabled,null,(0,e.createElement)(g(),{attributes:t,block:o,EmptyResponsePlaceholder:()=>(0,e.createElement)("div",{className:`wp-block-${o.replace("/","-")}`},o," ",(0,c.__)("Block rendered as empty.")),urlQueryArgs:"object"==typeof n&&Object.hasOwn(n,"postId")?{post_id:n.postId}:{}})))};function h(e,t){var n;const o=null!==(n=e?.sizes)&&void 0!==n?n:e?.media_details?.sizes,r=o?.[t];return r?{src:r.url||r.source_url,width:r.width,height:r.height}:null}const y=["image"],v=(0,c.__)("Select Image","block-editor-components"),E=(0,c.__)("Select Image","block-editor-components"),S=(0,c.__)("Remove image","block-editor-components"),w=(0,c.__)("Replace Image","block-editor-components");function _(t){const{buttonText:n=v,className:o,help:r,id:l,label:c,modalTitle:s=E,removeButtonText:u=S,replaceButtonText:d=w,size:m,value:b,onChange:f}=t,g=(0,a.useSelect)((e=>{const t=e("core").getMedia(b,{context:"view"});return t?t.alt_text:""}),[b]),k=(0,a.useSelect)((e=>{const t=e("core").getMedia(b,{context:"view"});if(t){if(m){const e=h(t,m);if(e)return e.src}return t.source_url}}),[m,b]);return(0,e.createElement)(i.BaseControl,{className:o,help:r,id:l,label:c},(0,e.createElement)(p.MediaUploadCheck,null,(0,e.createElement)(p.MediaUpload,{allowedTypes:y,render:({open:t})=>(0,e.createElement)("div",null,b?k?(0,e.createElement)(i.Button,{isLink:!0,onClick:t},(0,e.createElement)("img",{alt:g,src:k})):(0,e.createElement)(i.Spinner,null):null,(0,e.createElement)(i.Button,{isSecondary:!0,onClick:t},b?d:n)),title:s,onSelect:f})),(0,e.createElement)("br",null),b?(0,e.createElement)(i.Button,{isDestructive:!0,isLink:!0,onClick:()=>f(null)},u):null)}var T=n(697),C=n.n(T);const B=window.wp.blocks;function x({className:t,allowedBlocks:n,template:o,currentItemIndex:r,parentBlockId:l,renderAppender:i,captureToolbars:a}){const c=(0,e.useRef)(),s=(0,p.useInnerBlocksProps)({id:`inner-block-display-single-${l}`,className:t},{__experimentalCaptureToolbars:a,allowedBlocks:n,orientation:"horizontal",renderAppender:i,template:o,templateLock:!1});return(0,e.useEffect)((()=>{c.current&&(c.current.innerHTML=`#inner-block-display-single-${l} > *:not(:nth-child(${r+1}) ) { display: none; }`)}),[r,c,l]),(0,e.createElement)(e.Fragment,null,(0,e.createElement)("style",{ref:c}),(0,e.createElement)("div",{...s}))}x.defaultProps={currentItemIndex:0,renderAppender:!1,captureToolbars:!0},x.propTypes={parentBlockId:C().string.isRequired,allowedBlocks:C().arrayOf(C().string).isRequired,template:C().array,className:C().string,currentItemIndex:C().number,renderAppender:C().oneOfType([C().bool,C().element])};const I=x;var P=n(184),R=n.n(P);function O({totalPages:t,currentPage:n,setCurrentPage:o,prevEnabled:r,nextEnabled:l,addSlide:a=(()=>{}),addSlideEnabled:c=!1}){return(0,e.createElement)("div",{className:"inner-block-slider__navigation"},(0,e.createElement)(i.IconButton,{disabled:!r,icon:"arrow-left-alt2",isSecondary:!0,isSmall:!0,onClick:()=>{r&&o(n-1)}}),[...Array(t).keys()].map((t=>(0,e.createElement)(i.Button,{key:t+1,"aria-label":`Slide ${t+1}`,className:R()("components-button","is-not-small",{"is-primary":n===t+1,"is-secondary":n!==t+1}),type:"button",onClick:()=>{o(t+1)}},t+1))),(0,e.createElement)(i.IconButton,{disabled:!l,icon:"arrow-right-alt2",isSecondary:!0,isSmall:!0,onClick:()=>{l&&o(n+1)}}),(0,e.createElement)(i.IconButton,{disabled:!c,icon:"plus-alt2",isSecondary:!0,isSmall:!0,onClick:()=>a()}))}O.propTypes={totalPages:C().number.isRequired,currentPage:C().number.isRequired,setCurrentPage:C().func.isRequired,prevEnabled:C().bool.isRequired,nextEnabled:C().bool.isRequired,addSlide:C().func,addSlideEnabled:C().bool};const N=O,M=({parentBlockId:t,allowedBlock:n,template:o,slideLimit:r,currentItemIndex:l,setCurrentItemIndex:i,showNavigation:c})=>{const s=o||[[n]],{slideBlocks:u,selectedBlockId:d,getLowestCommonAncestorWithSelectedBlock:m}=(0,a.useSelect)((e=>{const n=e("core/block-editor");return{slideBlocks:n.getBlock(t).innerBlocks,selectedBlockId:n.getSelectedBlockClientId(),getLowestCommonAncestorWithSelectedBlock:n.getLowestCommonAncestorWithSelectedBlock}})),{selectBlock:p}=(0,a.useDispatch)("core/block-editor"),b=(0,e.useRef)(u.length),{insertBlock:f}=(0,a.useDispatch)("core/block-editor");return(0,e.useEffect)((()=>{if(u.length>b.current){const e=u.length-1;i(e),p(u[e].clientId)}else if(u.length<b.current&&l+1>u.length){const e=u.length-1;i(e),p(u[e].clientId)}b.current=u.length}),[u.length,l,b,i,p,u]),(0,e.useEffect)((()=>{const e=u.findIndex((e=>m(e.clientId)===e.clientId));e>=0&&i(e)}),[d,u,i,m]),(0,e.createElement)("div",{className:"inner-block-slider"},(0,e.createElement)(I,{allowedBlocks:[n],className:"slides",currentItemIndex:l,parentBlockId:t,template:s}),c&&(0,e.createElement)(N,{addSlide:()=>{const e=(0,B.createBlock)(n);f(e,void 0,t)},addSlideEnabled:u.length<r,currentPage:l+1,nextEnabled:l+1<u.length,prevEnabled:l+1>1,setCurrentPage:e=>{i(e-1),p(u[e-1].clientId)},totalPages:u.length}))};M.defaultProps={slideLimit:10,template:null,showNavigation:!0},M.propTypes={parentBlockId:C().string.isRequired,allowedBlock:C().string.isRequired,template:C().array,showNavigation:C().bool,currentItemIndex:C().number.isRequired,setCurrentItemIndex:C().func.isRequired};const A=M,F=t=>{const[n,o]=(0,e.useState)(0);return(0,e.createElement)(A,{...t,currentItemIndex:n,setCurrentItemIndex:o})};F.Controlled=A;const L=F;function D(t){const{onChange:n,opensInNewTab:o,url:r}=t,[l,a]=(0,e.useState)(!1),s=(0,e.useMemo)((()=>[{icon:"admin-links",title:(0,c.__)("Link","block-editor-components"),isActive:r?.length>0,onClick:()=>a(!l)}]),[a,l,r]),u=(0,e.useMemo)((()=>({url:r,opensInNewTab:o})),[o,r]);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.ToolbarGroup,{controls:s}),l&&(0,e.createElement)(i.Popover,null,(0,e.createElement)(p.__experimentalLinkControl,{forceIsEditingLink:l,opensInNewTab:o,value:u,onChange:n})))}function q(t){const{className:n,limit:o=0,onChange:r,...l}=t,[i,a]=(0,e.useState)(o&&t.value?.length>o);return(0,e.createElement)(p.PlainText,{className:`${n} limit-text ${i?"invalid":""}`.trim(),onChange:e=>{o&&e.length>o?i||a(!0):(i&&a(!1),r(e))},...l})}const j=/[\r\n]+/g;function W(t){const{editPost:n}=(0,a.useDispatch)("core/editor"),o=(0,a.useSelect)((e=>e("core/editor").getEditedPostAttribute("title")),[]),r=(0,e.useCallback)((e=>n({title:e.replace(j," ")})),[n]);return(0,e.createElement)(p.RichText,{...t,allowedFormats:[],value:o,onChange:r})}function $(e){var t;const{postType:n}=e;return(0,a.useSelect)((e=>e("core/editor").getCurrentPostType()),[])===n?e.children:null!==(t=e.fallback)&&void 0!==t?t:null}const U=window.wp.dom,z=e=>{const t=document.createRange();t.selectNodeContents(e),t.collapse(!1);const n=window.getSelection();n.removeAllRanges(),n.addRange(t)};function H(t){const{className:n,limit:o=0,onChange:r,...l}=t,i=(0,e.useRef)(),[a,c]=(0,e.useState)(o&&t.value?.length>o),[s,u]=(0,e.useState)(!1);return(0,e.createElement)(p.RichText,{ref:i,className:`${n} limit-text ${a?"invalid":""}`.trim(),onChange:e=>{if(o&&(0,U.__unstableStripHTML)(e).length>o)return u(!1),i.current.innerHTML=t.value,z(i.current),void(a||c(!0));s&&a&&c(!1),u(!0),r(e)},...l})}const V=function(t){const{taxonomy:n,value:o=[],onChange:r}=t,l=(0,a.useSelect)((e=>e("core").getTaxonomy(n)),[n]),{taxonomyTermsById:s,taxonomyTermsByTitle:u}=(0,a.useSelect)((e=>{var t;const o=null!==(t=e("core").getEntityRecords("taxonomy",n,{per_page:100}))&&void 0!==t?t:[],r=function(e){return e?e.reduce(((e,t)=>(e[t.id]=t.name,e)),{}):[]}(o),l=function(e){return e?e.reduce(((e,t)=>(e[t.name]=t.id,e)),{}):[]}(o);return{taxonomyTermsById:r,taxonomyTermsByTitle:l}}),[n]),d=o.map((e=>s[e])).filter(Boolean);return(0,e.createElement)(i.FormTokenField,{label:(0,c.sprintf)((0,c.__)("Filter by %s","block-editor-components"),l?l.labels.singular_name:""),suggestions:Object.values(s),value:d,onChange:e=>{r(e.map((e=>u[e])))}})},G=window.wp.element;function Q(t){const{postType:n,queryArgs:o,onChange:r,values:l=[],isSortable:s=!1}=t,u=(0,a.useSelect)((e=>{var t;return null!==(t=e("core").getEntityRecords("postType",n,o))&&void 0!==t?t:[]}),[n,o]),d=(0,a.useSelect)((e=>e("core/data").isResolving("core","getEntityRecords",["postType",n,o])));return(0,e.createElement)("div",{style:{marginTop:-24,paddingTop:24,paddingLeft:4,marginLeft:-4}},d&&(0,e.createElement)(i.Spinner,null)||u.length<1&&(0,e.createElement)(i.Notice,{isDismissible:!1},(0,c.__)("No results found","block-editor-components"))||u.map((t=>(0,e.createElement)("div",{style:{display:"grid",gridTemplateColumns:"1fr auto",marginRight:-2,paddingRight:2}},(0,e.createElement)(i.CheckboxControl,{key:t.id,checked:l.includes(t.id),label:t.title?.rendered||(0,c.__)("(No title)","block-editor-components"),onChange:e=>{r(e?[...l,t.id]:l.filter((e=>e!==t.id)))}}),s&&(0,e.createElement)(i.ButtonGroup,null,(0,e.createElement)(i.Button,{icon:"arrow-up-alt2",iconSize:12,isSmall:!0,label:(0,c.__)("Move up","block-editor-components"),variant:"secondary",onClick:()=>(e=>{const t=l.indexOf(e);-1!==t&&0!==t&&r([...l.slice(0,t-1),l[t],l[t-1],...l.slice(t+1)])})(t.id)}),(0,e.createElement)(i.Button,{icon:"arrow-down-alt2",iconSize:12,isSmall:!0,label:(0,c.__)("Move down","block-editor-components"),variant:"secondary",onClick:()=>(e=>{const t=l.indexOf(e);-1!==t&&t!==l.length-1&&r([...l.slice(0,t),l[t+1],l[t],...l.slice(t+2)])})(t.id)}))))))}function Y(t){const{postType:n,onChange:o,values:r,taxonomies:l}=t,[s,u]=(0,G.useState)(""),d=(0,a.useSelect)((e=>l.map((t=>e("core").getTaxonomy(t)))),[l]),[m,p]=(0,G.useState)([]),b=(0,e.useCallback)(((e,t)=>{const n=d.find((t=>t&&t.slug===e));n&&p({...m,[`${n.rest_base}`]:t})}),[m,d]);(0,e.useEffect)((()=>{d.forEach((e=>{e&&!m[e.rest_base]&&b(e.rest_base,[])}))}),[d,b,m]);const f={search:s||void 0,per_page:30,...m,context:"view"};return(0,e.createElement)(i.Flex,{align:"flex-start",style:{gap:24}},(0,e.createElement)(i.FlexItem,{style:{width:"35%"}},(0,e.createElement)(i.SearchControl,{label:(0,c.__)("Search Posts","block-editor-components"),style:{marginBottom:24},value:s,onChange:e=>u(e)}),l.map((t=>{const n=d.find((e=>e&&e.slug===t));return n?(0,e.createElement)(V,{taxonomy:t,value:m[n.rest_base],onChange:e=>b(t,e)}):null}))),(0,e.createElement)(i.FlexItem,{style:{width:"65%"}},(0,e.createElement)(Q,{postType:n,queryArgs:f,values:r,onChange:o})))}function J(t){const{title:n,postType:o="post",taxonomies:r=[],values:l=[],onChange:a,setModalOpen:s}=t;return(0,e.createElement)(i.Modal,{style:{width:"800px",maxWidth:"100%"},title:n,onRequestClose:()=>s(!1)},(0,e.createElement)("div",{style:{marginTop:-16}},(0,e.createElement)(i.TabPanel,{tabs:[{name:"browse",title:(0,c.__)("Browse Posts","block-editor-components"),content:()=>(0,e.createElement)(e.Fragment,null,"Foo")},{name:"selection",title:(0,c.__)("Current Selection","block-editor-components")}]},(t=>(0,e.createElement)("div",{style:{marginTop:"calc( var(--wp-admin-border-width-focus) * -1 )",borderStyle:"none",borderTop:"var( --wp-admin-border-width-focus ) solid #ddd",paddingTop:24}},"browse"===t.name&&(0,e.createElement)(Y,{postType:o,taxonomies:r,values:l,onChange:a}),"selection"===t.name&&(0,e.createElement)(Q,{isSortable:!0,postType:o,queryArgs:{include:l,orderby:"include",per_page:l.length},values:l,onChange:a}))))))}function K(t){const{title:n=(0,c.__)("Select posts","block-editor-components"),icon:o="edit"}=t,[r,l]=(0,G.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.ToolbarButton,{icon:o,label:n,onClick:()=>l(!0)},n),r&&(0,e.createElement)(J,{...t,setModalOpen:l,title:n}))}function X(t){const{title:n=(0,c.__)("Select posts","block-editor-components")}=t,[o,r]=(0,G.useState)(!1);return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(i.Button,{variant:"primary",onClick:()=>r(!0)},n),o&&(0,e.createElement)(J,{...t,setModalOpen:r,title:n}))}const Z=/^is-style-/;function ee(t){const{blockName:n,className:o}=(0,a.useSelect)((e=>{var n,o;const r=e("core/block-editor").getBlock(t);return{blockName:null!==(n=r?.name)&&void 0!==n?n:"",className:null!==(o=r?.attributes?.className)&&void 0!==o?o:""}}),[t]),{blockStyles:r,defaultStyle:l}=te(n),i=(0,e.useMemo)((()=>r.map((({name:e})=>e))),[r]),c=(0,e.useMemo)((()=>function(e=""){return e.trim().replace(/\s+/," ").split(" ").map((e=>Z.test(e)?e.replace(Z,""):"")).filter(Boolean)}(o)),[o]);return(0,e.useMemo)((()=>{var e;return null!==(e=c.find((e=>i.includes(e))))&&void 0!==e?e:l}),[i,c,l])}function te(t){const n=(0,a.useSelect)((e=>e("core/blocks").getBlockStyles(t)),[t]);return(0,e.useMemo)((()=>{var e;return{blockStyles:n,defaultStyle:null!==(e=n.find((({isDefault:e})=>e))?.name)&&void 0!==e?e:""}}),[n])}function ne(t){return(0,e.useMemo)((()=>{const e=(0,B.getBlockTypes)();return e?.length?e.filter((({name:e,parent:n})=>!n&&!t.includes(e))).map((({name:e})=>e)):[]}),[t])}function oe(t,n){var o;const{editPost:r}=(0,a.useDispatch)("core/editor"),l=(0,a.useSelect)((e=>e("core/editor").getEditedPostAttribute("meta"))),i=(0,e.useCallback)((e=>r({meta:{[t]:e}})),[r,t]);return[null!==(o=l?.[t])&&void 0!==o?o:n,i]}function re(){const{editPost:e}=(0,a.useDispatch)("core/editor"),t=(0,a.useSelect)((e=>e("core/editor").getEditedPostAttribute("featured_media"))),n=(0,a.useSelect)((e=>t?e("core").getMedia(t):null),[t]),o=(0,G.useCallback)((t=>{e({featured_media:t})}),[e]);return{postThumbnail:n,postThumbnailId:t,setPostThumbnail:o}}function le(e,t,n){return(0,a.useSelect)((o=>{const{innerBlocks:r}=o("core/block-editor").getBlock(e);return r?.length<t&&n}),[])}function ie(){const{selectBlock:t}=(0,a.useDispatch)("core/block-editor");return(0,e.useCallback)((e=>{const n=document.getElementById(`block-${e}`);n&&(t(e),setTimeout((()=>n.scrollIntoView({behavior:"smooth"})),200))}),[t])}function ae(t,n,o){return(0,e.useCallback)(((e=o)=>n({[t]:e})),[t,o,n])}function ce(e){const{getBlocks:t}=(0,a.select)("core/block-editor");return t().find((({name:t})=>t===e))}function se(e,t){return e.find((e=>!t(e)))}function ue(e,t){return e.filter((e=>!t(e)))}function de(e,t){return e.find((e=>t(e)))}function me(e,t){return e.filter((e=>t(e)))}const pe=window.wp.htmlEntities;function be(e,t=""){const{id:n,title:o}=e;return{label:t+(0,pe.decodeEntities)(o.rendered||(0,c.sprintf)((0,c.__)("#%d (no title)","block-editor-components"),n)),value:n}}function fe(e,t=""){const{id:n,name:o}=e;return{label:t+(0,pe.decodeEntities)(o||(0,c.sprintf)((0,c.__)("#%d (no name)","block-editor-components"),n)),value:n}}function ge(e){return e.map((e=>be(e)))}function ke(e,t="\u2014 ",n=0){return e.map((({children:e=[],...o})=>[be(o,t.repeat(n)),...ke(e,t,n+1)])).flat()}function he(e){return e.map((e=>fe(e)))}function ye(e,t="\u2014 ",n=0){return e.map((({children:e=[],...o})=>[fe(o,t.repeat(n)),...ye(e,t,n+1)])).flat()}function ve(e,...t){if(e.variations?.length){const n=function(e){return(t,n)=>e.every((e=>t[e]===n[e]))}(t);e.variations=e.variations.map((e=>(e.isActive=n,e)))}return e}})(),o})()));

/***/ }),

/***/ "./node_modules/@wordpress/icons/build-module/library/add-card.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/add-card.js ***!
  \************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);
/**
 * WordPress dependencies
 */


const addCard = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, {
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
    d: "M18.5 5.5V8H20V5.5h2.5V4H20V1.5h-1.5V4H16v1.5h2.5zM12 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-6h-1.5v6a.5.5 0 01-.5.5H6a.5.5 0 01-.5-.5V6a.5.5 0 01.5-.5h6V4z"
  })
});
/* harmony default export */ __webpack_exports__["default"] = (addCard);
//# sourceMappingURL=add-card.js.map

/***/ }),

/***/ "./node_modules/@wordpress/icons/build-module/library/seen.js":
/*!********************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/seen.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);
/**
 * WordPress dependencies
 */


const seen = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, {
  viewBox: "0 0 24 24",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
    d: "M3.99961 13C4.67043 13.3354 4.6703 13.3357 4.67017 13.3359L4.67298 13.3305C4.67621 13.3242 4.68184 13.3135 4.68988 13.2985C4.70595 13.2686 4.7316 13.2218 4.76695 13.1608C4.8377 13.0385 4.94692 12.8592 5.09541 12.6419C5.39312 12.2062 5.84436 11.624 6.45435 11.0431C7.67308 9.88241 9.49719 8.75 11.9996 8.75C14.502 8.75 16.3261 9.88241 17.5449 11.0431C18.1549 11.624 18.6061 12.2062 18.9038 12.6419C19.0523 12.8592 19.1615 13.0385 19.2323 13.1608C19.2676 13.2218 19.2933 13.2686 19.3093 13.2985C19.3174 13.3135 19.323 13.3242 19.3262 13.3305L19.3291 13.3359C19.3289 13.3357 19.3288 13.3354 19.9996 13C20.6704 12.6646 20.6703 12.6643 20.6701 12.664L20.6697 12.6632L20.6688 12.6614L20.6662 12.6563L20.6583 12.6408C20.6517 12.6282 20.6427 12.6108 20.631 12.5892C20.6078 12.5459 20.5744 12.4852 20.5306 12.4096C20.4432 12.2584 20.3141 12.0471 20.1423 11.7956C19.7994 11.2938 19.2819 10.626 18.5794 9.9569C17.1731 8.61759 14.9972 7.25 11.9996 7.25C9.00203 7.25 6.82614 8.61759 5.41987 9.9569C4.71736 10.626 4.19984 11.2938 3.85694 11.7956C3.68511 12.0471 3.55605 12.2584 3.4686 12.4096C3.42484 12.4852 3.39142 12.5459 3.36818 12.5892C3.35656 12.6108 3.34748 12.6282 3.34092 12.6408L3.33297 12.6563L3.33041 12.6614L3.32948 12.6632L3.32911 12.664C3.32894 12.6643 3.32879 12.6646 3.99961 13ZM11.9996 16C13.9326 16 15.4996 14.433 15.4996 12.5C15.4996 10.567 13.9326 9 11.9996 9C10.0666 9 8.49961 10.567 8.49961 12.5C8.49961 14.433 10.0666 16 11.9996 16Z"
  })
});
/* harmony default export */ __webpack_exports__["default"] = (seen);
//# sourceMappingURL=seen.js.map

/***/ }),

/***/ "./src/blocks/courier-notice/components/warning-tooltip.js":
/*!*****************************************************************!*\
  !*** ./src/blocks/courier-notice/components/warning-tooltip.js ***!
  \*****************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _icons_overlap__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../icons/overlap */ "./src/icons/overlap.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);





// Internal Components


const WarningTooltip = ({
  hasDuplicateAttributes
}) => {
  if (!hasDuplicateAttributes) {
    return null;
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
    className: "warning-indicator",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Icon, {
      style: {
        fill: 'red'
      },
      icon: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_icons_overlap__WEBPACK_IMPORTED_MODULE_4__["default"], {}),
      size: 24
    })
  });
};
/* harmony default export */ __webpack_exports__["default"] = (WarningTooltip);

/***/ }),

/***/ "./src/blocks/courier-notice/edit.js":
/*!*******************************************!*\
  !*** ./src/blocks/courier-notice/edit.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _humanmade_block_editor_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @humanmade/block-editor-components */ "./node_modules/@humanmade/block-editor-components/dist/index.js");
/* harmony import */ var _humanmade_block_editor_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_humanmade_block_editor_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_notices__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/notices */ "@wordpress/notices");
/* harmony import */ var _wordpress_notices__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_notices__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/add-card.js");
/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @wordpress/icons */ "./node_modules/@wordpress/icons/build-module/library/seen.js");
/* harmony import */ var _wordpress_preferences__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/preferences */ "@wordpress/preferences");
/* harmony import */ var _wordpress_preferences__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_preferences__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _components_warning_tooltip__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./components/warning-tooltip */ "./src/blocks/courier-notice/components/warning-tooltip.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__);













// Internal


const TAB_LIMIT = 5;
const ALLOWED_BLOCK = 'courier/courier-notice';

/**
 * Provide an interface for editing the block.
 *
 * @param {Object} props Props
 * @return {Element} Formatted blocks.
 */
const Edit = props => {
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => select('core/editor').getCurrentPostType());
  const {
    toggle,
    set
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useDispatch)(_wordpress_preferences__WEBPACK_IMPORTED_MODULE_10__.store);
  const {
    clientId,
    attributes,
    setAttributes
  } = props;
  const notices = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => select(_wordpress_notices__WEBPACK_IMPORTED_MODULE_4__.store).getNotices());
  const {
    allowPromoOverride
  } = attributes;
  const {
    createWarningNotice,
    removeNotice
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useDispatch)(_wordpress_notices__WEBPACK_IMPORTED_MODULE_4__.store);
  const innerBlocks = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useSelect)(select => {
    return select('core/block-editor').getBlock(clientId)?.innerBlocks || [];
  }, [clientId]);
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.useBlockProps)({
    className: 'courier-notice-container'
  });
  const {
    updateBlockAttributes
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_3__.useDispatch)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.store.name);
  const [currentItemIndex, setCurrentItemIndex] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(0);
  const [overlaps, setOverlaps] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(0);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsxs)("div", {
    ...blockProps,
    children: [postType === 'courier_notice' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.CardHeader, {
      as: 'div',
      size: 'small',
      style: {
        padding: '0 16px'
      },
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.Flex, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.FlexItem, {
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.Toolbar, {
            id: "adspiration-banner-toolbar",
            variant: "unstyled",
            label: "Options",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.ToolbarGroup, {
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.ToolbarButton, {
                icon: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.Icon, {
                  icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_13__["default"]
                }),
                text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Add Alternative Size', 'courier-notices'),
                onClick: () => {
                  onAddBanner(clientId);
                }
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.ToolbarButton, {
                icon: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_8__.SVG, {
                  viewBox: "0 0 24 24",
                  xmlns: "http://www.w3.org/2000/svg",
                  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_8__.Path, {
                    d: "m9.99609 14v-.2251l.00391.0001v6.225h1.5v-14.5h2.5v14.5h1.5v-14.5h3v-1.5h-8.50391c-2.76142 0-5 2.23858-5 5 0 2.7614 2.23858 5 5 5z"
                  })
                }),
                text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Help', 'adspriation'),
                onClick: event => {}
              })]
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.ToolbarDropdownMenu, {
              controls: [],
              icon: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.Icon, {
                icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_14__["default"]
              }),
              label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Select Banner Visiblity", 'courier-notices'),
              title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)("Banner Visiblity", 'courier-notices')
            })]
          })
        })
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Courier Notice Information', 'courier-notices'),
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.SelectControl, {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__.TextControl, {
          label: "Banner Area Container ID",
          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Provide a custom ID on the container if needed', 'courier-notices'),
          value: attributes.tablistContainerId,
          onChange: tablistContainerId => setAttributes({
            tablistContainerId
          })
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_12__.jsx)(_humanmade_block_editor_components__WEBPACK_IMPORTED_MODULE_1__.InnerBlockSlider.Controlled, {
      className: 'hm-tabs__content',
      slideLimit: TAB_LIMIT,
      parentBlockId: clientId,
      currentItemIndex: currentItemIndex,
      setCurrentItemIndex: setCurrentItemIndex,
      showNavigation: false
    })]
  });
};
/* harmony default export */ __webpack_exports__["default"] = (Edit);

/***/ }),

/***/ "./src/icons/overlap.js":
/*!******************************!*\
  !*** ./src/icons/overlap.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);


const Overlap = () => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsxs)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.SVG, {
    style: {
      fill: 'red'
    },
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
      d: "M9,5a7,7,0,1,0,7,7A7,7,0,0,0,9,5ZM9,17a5,5,0,1,1,5-5A5,5,0,0,1,9,17Z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Path, {
      d: "M15,5a7,7,0,1,0,7,7A7,7,0,0,0,15,5Zm0,12a5,5,0,1,1,5-5A5,5,0,0,1,15,17Z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_0__.Rect, {
      width: "24",
      height: "24",
      fill: "none"
    })]
  });
};
/* harmony default export */ __webpack_exports__["default"] = (Overlap);

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ (function(module) {

"use strict";
module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/notices":
/*!*********************************!*\
  !*** external ["wp","notices"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["notices"];

/***/ }),

/***/ "@wordpress/preferences":
/*!*************************************!*\
  !*** external ["wp","preferences"] ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["preferences"];

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["primitives"];

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = '';

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (arg) {
				classes = appendClass(classes, parseValue(arg));
			}
		}

		return classes;
	}

	function parseValue (arg) {
		if (typeof arg === 'string' || typeof arg === 'number') {
			return arg;
		}

		if (typeof arg !== 'object') {
			return '';
		}

		if (Array.isArray(arg)) {
			return classNames.apply(null, arg);
		}

		if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
			return arg.toString();
		}

		var classes = '';

		for (var key in arg) {
			if (hasOwn.call(arg, key) && arg[key]) {
				classes = appendClass(classes, key);
			}
		}

		return classes;
	}

	function appendClass (value, newClass) {
		if (!newClass) {
			return value;
		}
	
		if (value) {
			return value + ' ' + newClass;
		}
	
		return value + newClass;
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./src/blocks/courier-notice/block.json":
/*!**********************************************!*\
  !*** ./src/blocks/courier-notice/block.json ***!
  \**********************************************/
/***/ (function(module) {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://json.schemastore.org/block.json","apiVersion":2,"name":"courier/courier-notice","version":"2.0.0","title":"Courier Notice","category":"layout","icon":"megaphone","description":"A block used to display a notice to the user.","supports":{"anchor":false,"html":false,"reusable":true,"inserter":false,"multiple":true,"className":true,"customClassName":true,"align":["wide","full"],"interactivity":true},"attributes":{},"textdomain":"courier-notices","editorScript":"file:./index.js","editorStyle":"file:./index.css","viewScriptModule":"file:./view.js"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!********************************************!*\
  !*** ./src/blocks/courier-notice/index.js ***!
  \********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./block.json */ "./src/blocks/courier-notice/block.json");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./edit */ "./src/blocks/courier-notice/edit.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/primitives */ "@wordpress/primitives");
/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_11__);













(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_5__, {
  edit: _edit__WEBPACK_IMPORTED_MODULE_6__["default"],
  save(props) {
    const blockProps = _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useBlockProps.save();
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_11__.jsx)("div", {
      ...blockProps,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_11__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.InnerBlocks.Content, {})
    });
  }
});
}();
/******/ })()
;
//# sourceMappingURL=index.js.map