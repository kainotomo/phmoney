var T=Object.defineProperty,D=Object.defineProperties;var C=Object.getOwnPropertyDescriptors;var _=Object.getOwnPropertySymbols;var N=Object.prototype.hasOwnProperty,$=Object.prototype.propertyIsEnumerable;var h=(t,o,s)=>o in t?T(t,o,{enumerable:!0,configurable:!0,writable:!0,value:s}):t[o]=s,f=(t,o)=>{for(var s in o||(o={}))N.call(o,s)&&h(t,s,o[s]);if(_)for(var s of _(o))$.call(o,s)&&h(t,s,o[s]);return t},g=(t,o)=>D(t,C(o));import{_ as w,r as c,o as m,g as u,a as e,t as l,k as i,v as p,l as v,m as k,b as a,d as B,c as y,w as b,i as U}from"./main.ff91f6f2.js";const L={},V={class:"p-6"},F={class:"text-center"},M={class:"w-full table-fixed"},S=e("th",{class:"text-right"},"Amount",-1),A={colspan:"4"},E={class:"text-right"},G={class:"text-right"},P={class:"w-full table-fixed"},j=e("th",{class:"text-left"},"Grand Total",-1),q={class:"text-right"};function z(t,o){const s=c("amount-financial");return m(),u("div",V,[e("h2",F,l(t.store.props.company)+" "+l(t.store.props.title)+" For Period Covering "+l(t.store.convert_date(t.store.props.date_start))+" to "+l(t.store.convert_date(t.store.props.date_end)),1),e("table",M,[e("thead",null,[e("th",null,[i(e("span",null,"Date",512),[[p,t.store.report_settings.params.date]])]),e("th",null,[i(e("span",null,"Num",512),[[p,t.store.report_settings.params.num]])]),e("th",null,[i(e("span",null,"Description",512),[[p,t.store.report_settings.params.description]])]),e("th",null,[i(e("span",null,"Memo",512),[[p,t.store.report_settings.params.memo]])]),S])]),(m(!0),u(v,null,k(t.store.props.rows,r=>(m(),u("table",{class:"w-full table-fixed",key:r.guid},[e("thead",null,[e("tr",null,[e("th",A,"Total for "+l(r.name),1),e("th",E,[a(s,{amount:r.total,commodity:r.commodity},null,8,["amount","commodity"])])])]),e("tbody",null,[(m(!0),u(v,null,k(r.rows,n=>(m(),u("tr",{key:n.split_guid},[e("td",null,[i(e("span",null,l(t.store.convert_date(n.post_date)),513),[[p,t.store.report_settings.params.date]])]),e("td",null,[i(e("span",null,l(n.num),513),[[p,t.store.report_settings.params.num]])]),e("td",null,[i(e("span",null,l(n.description),513),[[p,t.store.report_settings.params.description]])]),e("td",null,[i(e("span",null,l(n.memo),513),[[p,t.store.report_settings.params.memo]])]),e("td",G,[a(s,{amount:n.amount,commodity:r.commodity},null,8,["amount","commodity"])])]))),128))])]))),128)),e("table",P,[e("tbody",null,[e("tr",null,[j,e("th",q,[a(s,{amount:t.store.props.total,commodity:t.store.props.currency},null,8,["amount","commodity"])])])])])])}var H=w(L,[["render",z],["__file","/home/phalo/kainotomo/web_gnucash/vue/code/src/components/reports/ReportsTransactions.vue"]]);const I=e("div",{class:"font-bold"},"Display",-1),J={class:"flex gap-2"},K={class:"bg-white shadow mt-4 prose max-w-none"},O={data(){return{extra_params:{date:!0,num:!0,description:!0,memo:!0}}}},Q=B(g(f({},O),{name:"ReportsTransactions",setup(t){return(o,s)=>{const r=c("form-label"),n=c("form-checkbox"),R=c("ReportLayout");return m(),y(R,{title:"Transactions Report",extra_params:o.extra_params},{settings:b(()=>[I,e("div",J,[e("div",null,[a(r,{for:"filter_date",value:"Date"}),a(n,{id:"filter_date",checked:o.store.report_settings.params.date,"onUpdate:checked":s[0]||(s[0]=d=>o.store.report_settings.params.date=d)},null,8,["checked"])]),e("div",null,[a(r,{for:"filter_num",value:"Num"}),a(n,{id:"filter_num",checked:o.store.report_settings.params.num,"onUpdate:checked":s[1]||(s[1]=d=>o.store.report_settings.params.num=d)},null,8,["checked"])]),e("div",null,[a(r,{for:"filter_description",value:"Description"}),a(n,{id:"filter_description",checked:o.store.report_settings.params.description,"onUpdate:checked":s[2]||(s[2]=d=>o.store.report_settings.params.description=d)},null,8,["checked"])]),e("div",null,[a(r,{for:"filter_memo",value:"Memo"}),a(n,{id:"filter_memo",checked:o.store.report_settings.params.memo,"onUpdate:checked":s[3]||(s[3]=d=>o.store.report_settings.params.memo=d)},null,8,["checked"])])])]),default:b(()=>[e("div",K,[o.store.props.currency&&!o.store.processing?(m(),y(H,{key:0})):U("v-if",!0)])]),_:1},8,["extra_params"])}}}));var Y=w(Q,[["__file","/home/phalo/kainotomo/web_gnucash/vue/code/src/views/reports/ReportsTransactions.vue"]]);export{Y as default};
