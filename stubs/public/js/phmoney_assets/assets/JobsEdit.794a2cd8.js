var m=Object.defineProperty,_=Object.defineProperties;var d=Object.getOwnPropertyDescriptors;var t=Object.getOwnPropertySymbols;var u=Object.prototype.hasOwnProperty,b=Object.prototype.propertyIsEnumerable;var a=(s,o,e)=>o in s?m(s,o,{enumerable:!0,configurable:!0,writable:!0,value:e}):s[o]=e,r=(s,o)=>{for(var e in o||(o={}))u.call(o,e)&&a(s,e,o[e]);if(t)for(var e of t(o))b.call(o,e)&&a(s,e,o[e]);return s},i=(s,o)=>_(s,d(o));import{_ as h,d as l,r as f,o as n,c as p,w,a as v,i as J}from"./main.ff91f6f2.js";import{J as j}from"./JobsEdit.dadeaf0c.js";const k={class:"bg-white shadow mt-4 prose max-w-none"},y={async created(){await this.store.get(`/phmoney/business/jobs/edit/${this.$route.params.job_pk}`)}},E=l(i(r({},y),{name:"JobsEdit",setup(s){return(o,e)=>{const c=f("FormLayout");return n(),p(c,{title:`Edit Job - ${o.store.props.job?o.store.props.job.name:"..."}`},{default:w(()=>[v("div",k,[o.store.processing?J("v-if",!0):(n(),p(j,{key:0}))])]),_:1},8,["title"])}}}));var B=h(E,[["__file","/home/phalo/kainotomo/web_gnucash/vue/code/src/views/business/jobs/JobsEdit.vue"]]);export{B as default};
