import{G as h,e as m,m as L,g as q,u as P,B as M,z as A,A as T}from"./index.20192476.js";import{s as B}from"./metabox.dc73facf.js";import{m as e,h as N,i as W,j as U,k as z}from"./postTitle.d67eb903.js";const D=()=>{let t=!1;if(document.querySelector("#wp-content-wrap.tmce-active")){const r=window.setInterval(()=>{!window.tinyMCE||!window.tinyMCE.activeEditor||(window.clearInterval(r),e(),window.tinyMCE.get("content").on("keyup",()=>{e(500)}),window.tinyMCE.get("content").on("paste",()=>{e(500)}))},50)}else{const r=document.querySelector("textarea#content");r&&(r.addEventListener("keyup",()=>{e(500)}),r.addEventListener("paste",()=>{e(500)}))}const n=document.querySelector("#post input#title");n&&n.addEventListener("input",()=>{e(500)}),C();const i=document.querySelector("#post_name");i&&i.addEventListener("change",()=>{e(500)});const o=document.querySelector("#edit-slug-buttons");o&&o.addEventListener("click",r=>{r.target===o.querySelector("#edit-slug-buttons button.save")&&e()});const a=document.querySelector("#categorychecklist");a&&a.addEventListener("change",function(){N()});const c=function(r){r.forEach(w=>{if(w.attributeName==="class"){if(document.querySelector("#wp-content-wrap.tmce-active")){if(!window.tinyMCE)return;window.tinyMCE.get("content").on("keyup",()=>{e(500)}),window.tinyMCE.get("content").on("paste",()=>{e(500)})}const l=document.querySelector("#content");l&&(l.addEventListener("keyup",()=>{e(500)}),l.addEventListener("paste",()=>{e(500)})),C()}})},d=new MutationObserver(c),u=document.querySelector("#wp-content-wrap");u&&d.observe(u,{attributes:!0});const p=document.querySelector("#wp-excerpt-wrap");p&&d.observe(p,{attributes:!0}),setInterval(()=>{t&&(t=!1)},500),W()&&!h()&&(t=!0,setInterval(()=>{window.tinyMCE&&window.tinyMCE.activeEditor&&window.tinyMCE.activeEditor.isDirty()&&!t&&(t=!0,e())},500))},C=()=>{if(document.querySelector("#wp-excerpt-wrap.tmce-active")){const n=window.setInterval(()=>{window.tinyMCE&&(window.clearInterval(n),e(),window.tinyMCE.get("excerpt").on("keyup",()=>{e(500)}),window.tinyMCE.get("excerpt").on("paste",()=>{e(500)}))},50)}const t=document.querySelector("#post textarea#excerpt");t&&(t.addEventListener("keyup",()=>{e(500)}),t.addEventListener("paste",()=>{e(500)}))},O=()=>{e(),window.wp.data.subscribe(()=>{e(500);const t=window.wp.data.select("core/editor").isSavingPost(),n=window.wp.data.select("core/editor").isAutosavingPost();if(t&&!n){const i=m();i.isDirty=!1,e()}})},k=()=>{const t=L();return(t==null?void 0:t.isUnlicensed)||!1},I=()=>{var f,E,v,g,S,x,b,_;let t="",n="",i="",o=[],a="";const c=q(),d=m(),u=document.getElementById("_sku");u&&(t=u.value,c.updateWooCommerceSku(t));const p=document.getElementById("_sale_price"),r=document.getElementById("_regular_price");p&&(n=p.value),!n&&r&&(n=r.value);const l=((E=(f=P().aioseo.data)==null?void 0:f.wooCommerce)==null?void 0:E.currencySymbol)||"$"+parseFloat(n||0).toFixed(2);if(c.updateWooCommercePrice(l),o=document.querySelectorAll('#post input[name="tax_input[product_brand][]"]:checked'),o.length||(o=document.querySelectorAll('#post input[name="tax_input[pwb-brand][]"]:checked')),o.length){if(i!==o[0].parentNode.innerText&&(i=o[0].parentNode.innerText,c.updateWooCommerceBrand(o[0].parentNode.innerText)),(g=(v=d.currentPost)==null?void 0:v.primary_term)!=null&&g["pwb-brand"]){const s=document.getElementById(`in-pwb-brand-${d.currentPost.primary_term["pwb-brand"]}`);(S=s==null?void 0:s.parentNode)!=null&&S.innerText&&c.updateWooCommerceBrand(s.parentNode.innerText)}}else c.updateWooCommerceBrand("");const y=document.querySelectorAll('#post input[name="tax_input[product_cat][]"]:checked');if(y.length&&(a=y[0].parentNode.innerText),(b=(x=d.currentPost)==null?void 0:x.primary_term)!=null&&b.product_cat){const s=document.getElementById(`in-product_cat-${d.currentPost.primary_term.product_cat}`);a=((_=s==null?void 0:s.parentNode)==null?void 0:_.innerText)||""}c.updateTaxonomyTitle(a)};window.addEventListener("DOMContentLoaded",()=>{var t,n;((n=(t=window==null?void 0:window.aioseo)==null?void 0:t.screen)==null?void 0:n.base)!=="customize"&&!k()&&I()});const $=()=>{k()||window.addEventListener("change",t=>{t.target.tagName==="INPUT"&&I()})},G=(t,n=!0)=>{if(!B())return;e();const i=m();if(i.currentPost.context==="term")U();else if(P().ping(),n&&i.savePostState(),M()){const a=window.setInterval(()=>{window.wp.data.select("core/editor").getCurrentPost().id&&(window.clearInterval(a),O())},50)}else h()&&$(),(A()||T())&&D(),z()};export{G as l};