var _=Object.defineProperty,p=Object.defineProperties;var d=Object.getOwnPropertyDescriptors;var s=Object.getOwnPropertySymbols;var u=Object.prototype.hasOwnProperty,l=Object.prototype.propertyIsEnumerable;var a=(t,e,o)=>e in t?_(t,e,{enumerable:!0,configurable:!0,writable:!0,value:o}):t[e]=o,n=(t,e)=>{for(var o in e||(e={}))u.call(e,o)&&a(t,o,e[o]);if(s)for(var o of s(e))l.call(e,o)&&a(t,o,e[o]);return t},r=(t,e)=>p(t,d(e));import{I as f}from"./InvoicesEdit.4edc1df6.js";import{d as h,r as w,o as c,c as i,w as v,a as y,i as C}from"./main.769fda87.js";const g={class:"bg-white shadow mt-4 prose max-w-none"},k={async created(){await this.store.get("/phmoney/business/invoices/create")}},b=h(r(n({},k),{setup(t){return(e,o)=>{const m=w("FormLayout");return c(),i(m,{title:"Create Invoice"},{default:v(()=>[y("div",g,[e.store.processing?C("",!0):(c(),i(f,{key:0}))])]),_:1})}}}));export{b as default};
