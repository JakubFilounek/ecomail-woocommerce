(window.webpackJsonp_wpify_custom_fields=window.webpackJsonp_wpify_custom_fields||[]).push([[22],{138:function(e,t,n){"use strict";n.r(t);var r=n(10),o=n.n(r),a=n(0),i=n(1),c=n.n(i),s=n(2),u=n.n(s),l=n(7),p=n(9),f=n.n(p),b=n(23),O=n(6),g=n(66),y=n(3),d=n(55),m=n(5),v=n.n(m);function j(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function h(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?j(Object(n),!0).forEach((function(t){f()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):j(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var x=function(e){var t,n=e.id,r=e.value,c=e.onChange,s=void 0===c?function(){}:c,u=e.options,p=e.isMulti,f=void 0!==p&&p,m=e.url,j=e.nonce,x=e.method,w=void 0===x?"post":x,_=e.className,E=e.list_type,S=e.required,N=Object(i.useContext)(l.a).api,P=Object(i.useState)((t=r,Array.isArray(t)?t.map(String):t?[String(t)]:[])),C=o()(P,2),M=C[0],T=C[1],k=Object(i.useState)(""),D=o()(k,2),q=D[0],B=D[1],J=Object(O.g)({defaultValue:u||[]}),A=J.fetch,I=J.result;Object(O.f)((function(){if((""!==q||!u)&&E){var t=h(h({},e),{},{current_value:M&&M.filter(Boolean)||[],search:q});A({method:w,url:m,nonce:j,body:t})}}),[u,q,e,N,M,E]),Object(i.useEffect)((function(){s(f?M:M.find(Boolean))}),[s,f,r,M]);var H=function(e){T(f?e?e.map((function(e){return String(e.value)})):[]:e?[String(e.value)]:[])};return Object(a.createElement)(y.a,null,Object(a.createElement)(g.a,{id:n,onChange:H,value:I&&I.filter((function(e){return M.includes(String(e.value))})),onInputChange:B,options:I&&I.map((function(e){return h(h({},e),{},{value:String(e.value)})})),isMulti:f,className:_,noOptionsMessage:function(){return""===q?Object(b.__)("Type to search","wpify-custom-fields"):void 0}}),!S&&Boolean(M.length)&&!f&&Object(a.createElement)(d.a,{className:v()("wcf-button wcf-button--icon"),style:{marginLeft:"10px"},onClick:function(){return H(f?[]:null)}},Object(a.createElement)("svg",{viewBox:"0 0 20 20",width:"20",height:"20"},Object(a.createElement)("line",{stroke:"currentColor",x1:"3",y1:"3",x2:"17",y2:"17"}),Object(a.createElement)("line",{stroke:"currentColor",x1:"3",y1:"17",x2:"17",y2:"3"}))))};x.propTypes={id:u.a.string,value:u.a.oneOfType([u.a.string,u.a.number]),onChange:u.a.func,options:u.a.array,required:u.a.bool,isMulti:u.a.bool,url:u.a.string,nonce:u.a.string,method:u.a.string,className:u.a.string,list_type:u.a.string};var w=x,_=function(e){var t=e.id,n=e.value,r=e.onChange,s=e.options,u=e.description,p=e.list_type,f=void 0===p?null:p,b=e.group_level,O=void 0===b?0:b,g=e.required,d=e.isMulti,m=void 0!==d&&d,v=e.className,j=Object(i.useContext)(l.a).api,h=Object(i.useState)(n),x=o()(h,2),_=x[0],E=x[1];return Object(i.useEffect)((function(){r&&JSON.stringify(n)!==JSON.stringify(_)&&r(_)}),[r,n,_]),Object(a.createElement)(c.a.Fragment,null,0===O&&Object(a.createElement)("input",{type:"hidden",name:t,value:m?JSON.stringify(_.filter(Boolean)):_}),Object(a.createElement)(y.a,null,Object(a.createElement)(w,{id:t,value:n,onChange:function(e){E(e)},options:s,list_type:f,required:g,isMulti:m,url:j.url+"/list",nonce:j.nonce,method:"post",className:v})),u&&Object(a.createElement)(y.a,null,Object(a.createElement)("p",{className:"description",dangerouslySetInnerHTML:{__html:u}})))};_.propTypes={className:u.a.string,id:u.a.string,value:u.a.oneOfType([u.a.string,u.a.number]),onChange:u.a.func,options:u.a.array,description:u.a.oneOfType([u.a.string,u.a.element]),list_type:u.a.string,group_level:u.a.number,required:u.a.bool,isMulti:u.a.bool},_.getHumanTitle=function(e,t){if(Array.isArray(e.options)){var n=e.options.find((function(e){return String(e.value)===String(t)}));if(n)return n.label}return t},t.default=_},24:function(e,t){function n(){return e.exports=n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},e.exports.default=e.exports,e.exports.__esModule=!0,n.apply(this,arguments)}e.exports=n,e.exports.default=e.exports,e.exports.__esModule=!0},51:function(e,t,n){var r=n(59);e.exports=function(e,t){if(null==e)return{};var n,o,a=r(e,t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);for(o=0;o<i.length;o++)n=i[o],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a},e.exports.default=e.exports,e.exports.__esModule=!0},55:function(e,t,n){"use strict";var r=n(24),o=n.n(r),a=n(51),i=n.n(a),c=n(0),s=(n(1),n(5)),u=n.n(s),l=n(2),p=function(e){var t=e.className,n=i()(e,["className"]);return Object(c.createElement)("button",o()({type:"button",className:u()("button",t)},n))};p.propTypes={className:n.n(l).a.string},t.a=p},59:function(e,t){e.exports=function(e,t){if(null==e)return{};var n,r,o={},a=Object.keys(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||(o[n]=e[n]);return o},e.exports.default=e.exports,e.exports.__esModule=!0},66:function(e,t,n){"use strict";var r=n(24),o=n.n(r),a=n(9),i=n.n(a),c=n(51),s=n.n(c),u=n(0),l=(n(1),n(2)),p=n.n(l),f=n(5),b=n.n(f),O=n(84),g=n(3);function y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function d(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?y(Object(n),!0).forEach((function(t){i()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var m=function(e){var t=e.className,n=s()(e,["className"]);return Object(u.createElement)(g.a,null,Object(u.createElement)(O.a,o()({className:b()("wcf-select",t),styles:{control:function(e,t){var n=t.isFocused;return d(d({},e),{},{minHeight:"30px",borderColor:n?"#2271b1 !important":"#8c8f94",boxShadow:n?"0 0 0 1px #2271b1":"none",outline:n?"2px solid transparent":"none",borderRadius:"3px",maxWidth:"25em"})},valueContainer:function(e){return d(d({},e),{},{padding:"0 8px"})}}},n)))};m.propTypes={id:p.a.string,onChange:p.a.func,onInputChange:p.a.func,options:p.a.array,className:p.a.string},t.a=m}}]);