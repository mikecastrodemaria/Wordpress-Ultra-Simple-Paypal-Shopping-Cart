(()=>{"use strict";var e,t={786:()=>{const e=window.React,t=window.wp.blocks,r=window.wp.i18n,a=window.wp.blockEditor,o=window.wp.components;function l(e){let t="";for(let r=0;r<e.length;r++){if("<"===e[r])for(;">"!==e[r]&&r<e.length;)r++;["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0","é","è",",","ç","à","ù",".","ô","î","ê","û","ö","ï","ë","ü","â","ä","€","$","£","¥","%","&","Â","Ä","Ê","Ë","Î","Ï","Ö","Ô","Û","Ü","Ù","À","Ç","É","È","Æ","Œ","œ","Å","Ø","Þ","ð","Ý","ý","þ","ÿ","ß","Ÿ","Š","š","Ž","ž"," "].includes(e[r])&&(t+=e[r])}return t}const n=JSON.parse('{"UU":"create-block/simple-add-cart-block"}'),c=(0,e.createElement)("svg",{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg","aria-hidden":"true",focusable:"false"},(0,e.createElement)("path",{d:"M4.75 4a.75.75 0 0 0-.75.75v7.826c0 .2.08.39.22.53l6.72 6.716a2.313 2.313 0 0 0 3.276-.001l5.61-5.611-.531-.53.532.528a2.315 2.315 0 0 0 0-3.264L13.104 4.22a.75.75 0 0 0-.53-.22H4.75ZM19 12.576a.815.815 0 0 1-.236.574l-5.61 5.611a.814.814 0 0 1-1.153 0L5.5 12.264V5.5h6.763l6.5 6.502a.816.816 0 0 1 .237.574ZM8.75 9.75a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"}));(0,t.registerBlockType)(n.UU,{icon:c,edit:function({attributes:t,setAttributes:n}){const{productName:c,productPrice:i}=t;return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",{...(0,a.useBlockProps)()},(0,e.createElement)("div",{id:"productInfo",style:{justifyContent:"space-between",display:"flex"}},(0,e.createElement)(o.TextControl,{className:"productTag",label:(0,r.__)("Product name","wp-ultra-simple-paypal-shopping-cart"),value:c||"",onChange:e=>n({productName:l(e)}),style:{marginRight:"10px"}}),(0,e.createElement)(o.TextControl,{className:"productTag",label:(0,r.__)("Product price","wp-ultra-simple-paypal-shopping-cart"),value:i||"",onChange:e=>n({productPrice:l(e)})}))))}})}},r={};function a(e){var o=r[e];if(void 0!==o)return o.exports;var l=r[e]={exports:{}};return t[e](l,l.exports,a),l.exports}a.m=t,e=[],a.O=(t,r,o,l)=>{if(!r){var n=1/0;for(s=0;s<e.length;s++){for(var[r,o,l]=e[s],c=!0,i=0;i<r.length;i++)(!1&l||n>=l)&&Object.keys(a.O).every((e=>a.O[e](r[i])))?r.splice(i--,1):(c=!1,l<n&&(n=l));if(c){e.splice(s--,1);var p=o();void 0!==p&&(t=p)}}return t}l=l||0;for(var s=e.length;s>0&&e[s-1][2]>l;s--)e[s]=e[s-1];e[s]=[r,o,l]},a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={57:0,350:0};a.O.j=t=>0===e[t];var t=(t,r)=>{var o,l,[n,c,i]=r,p=0;if(n.some((t=>0!==e[t]))){for(o in c)a.o(c,o)&&(a.m[o]=c[o]);if(i)var s=i(a)}for(t&&t(r);p<n.length;p++)l=n[p],a.o(e,l)&&e[l]&&e[l][0](),e[l]=0;return a.O(s)},r=globalThis.webpackChunkcopyright_date_block=globalThis.webpackChunkcopyright_date_block||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})();var o=a.O(void 0,[350],(()=>a(786)));o=a.O(o)})();