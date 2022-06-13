var q=Object.defineProperty,x=Object.defineProperties;var B=Object.getOwnPropertyDescriptors;var f=Object.getOwnPropertySymbols;var F=Object.prototype.hasOwnProperty,L=Object.prototype.propertyIsEnumerable;var _=(s,e,i)=>e in s?q(s,e,{enumerable:!0,configurable:!0,writable:!0,value:i}):s[e]=i,v=(s,e)=>{for(var i in e||(e={}))F.call(e,i)&&_(s,i,e[i]);if(f)for(var i of f(e))L.call(e,i)&&_(s,i,e[i]);return s},g=(s,e)=>x(s,B(e));import{_ as S,x as b,r as d,o as u,g as a,a as n,b as r,n as y,k as h,p as k,l as w,m as V,t as I,w as D,e as N,d as A,c as P,i as M}from"./main.ff91f6f2.js";const T={data(){return{form:{date_posted:b.convert_date(),date_due:b.convert_date(this.store.props.date_due),description:null,account_guid:null,accumulate:!0}}},methods:{async submit(){await this.store.post(`/phmoney/business/invoices/post/${this.$route.params.invoice_pk}`,this.form),this.$router.back()}}},j={class:"p-3 border"},z={class:"flex flex-wrap gap-2"},E=n("option",{disabled:"",value:null},"- Select receivable account -",-1),G=["value"],H=n("option",{disabled:"",value:null},"- Select payable account -",-1),J=["value"],K={class:"flex gap-2 items-center justify-end mt-4"},O=n("span",{class:"material-icons-outlined"}," save ",-1);function Q(s,e,i,m,t,p){const l=d("form-label"),c=d("form-input"),U=d("form-checkbox"),C=d("form-button");return u(),a("form",{onSubmit:e[6]||(e[6]=N((...o)=>p.submit&&p.submit(...o),["prevent"])),class:"p-6"},[n("div",j,[n("div",z,[n("div",null,[r(l,{for:"date_posted",value:"Post Date"}),r(c,{id:"date_posted",type:"date",modelValue:t.form.date_posted,"onUpdate:modelValue":e[0]||(e[0]=o=>t.form.date_posted=o),required:""},null,8,["modelValue"])]),n("div",null,[r(l,{for:"date_due",value:"Due Date"}),r(c,{id:"date_due",type:"date",modelValue:t.form.date_due,"onUpdate:modelValue":e[1]||(e[1]=o=>t.form.date_due=o),class:y({"opacity-60":s.store.props.invoice.terms}),disabled:s.store.props.invoice.terms,required:""},null,8,["modelValue","class","disabled"])]),n("div",null,[r(l,{for:"description",value:"Description"}),r(c,{id:"description",type:"text",modelValue:t.form.description,"onUpdate:modelValue":e[2]||(e[2]=o=>t.form.description=o)},null,8,["modelValue"])]),n("div",null,[r(l,{for:"account",value:"Post to Account"}),s.store.getInvoiceType()==="Invoice"?h((u(),a("select",{key:0,id:"account","onUpdate:modelValue":e[3]||(e[3]=o=>t.form.account_guid=o),required:"",class:"border-gray-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm"},[E,(u(!0),a(w,null,V(s.store.props.i_accounts,o=>(u(),a("option",{key:o.guid,value:o.guid},I(o.name),9,G))),128))],512)),[[k,t.form.account_guid]]):h((u(),a("select",{key:1,id:"account","onUpdate:modelValue":e[4]||(e[4]=o=>t.form.account_guid=o),required:"",class:"border-gray-300 focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 rounded-md shadow-sm"},[H,(u(!0),a(w,null,V(s.store.props.b_accounts,o=>(u(),a("option",{key:o.guid,value:o.guid},I(o.name),9,J))),128))],512)),[[k,t.form.account_guid]])]),n("div",null,[r(l,{for:"accumulate",value:"Accumulate Splits?"}),r(U,{id:"accumulate",checked:t.form.accumulate,"onUpdate:checked":e[5]||(e[5]=o=>t.form.accumulate=o)},null,8,["checked"])])])]),n("div",K,[r(C,{class:y({"opacity-25":t.form.processing||!t.form.account_guid}),disabled:t.form.processing||!t.form.account_guid,title:"Save"},{default:D(()=>[O]),_:1},8,["class","disabled"])])],32)}var R=S(T,[["render",Q],["__file","/home/phalo/kainotomo/web_gnucash/vue/code/src/components/business/invoices/InvoicesPost.vue"]]);const W={class:"bg-white shadow mt-4 prose max-w-none"},X={async created(){await this.store.get(`/phmoney/business/invoices/post/${this.$route.params.invoice_pk}`)}},Y=A(g(v({},X),{name:"InvoicesPost",setup(s){return(e,i)=>{const m=d("FormLayout");return u(),P(m,{title:`Post ${e.store.props.invoice?e.store.getInvoiceType()+" - "+e.store.props.invoice.id:"..."}`},{default:D(()=>[n("div",W,[e.store.processing?M("v-if",!0):(u(),P(R,{key:0}))])]),_:1},8,["title"])}}}));var ee=S(Y,[["__file","/home/phalo/kainotomo/web_gnucash/vue/code/src/views/business/invoices/InvoicesPost.vue"]]);export{ee as default};
