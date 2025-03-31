import{_ as $}from"./AuthenticatedLayout-BlAmliJP.js";import{Z as R,T as F,k as m,f as s,a as n,w as y,F as f,o,b as l,d as b,t as v,l as _,c as L,n as U,g as B}from"./app-ctv0rznj.js";import{_ as C}from"./LinkButton-CgjH23nr.js";import{P as S}from"./PrimaryButton-Bpy3ozRt.js";import{a as T,b as j,_ as N}from"./TextInput-C4PvDNml.js";import{_ as O}from"./SelectInput-DRrKjI14.js";import{_ as A}from"./Checkbox-Dka3b9Kh.js";import{D}from"./DangerButton-CmYcJXZx.js";import{_ as E}from"./RadioInput-Dtfa8P8o.js";import{_ as H}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./ApplicationLogo-BsOgWnat.js";const z={components:{AuthenticatedLayout:$,Head:R,LinkButton:C,PrimaryButton:S,TextInput:T,InputError:j,InputLabel:N,SelectInput:O,Checkbox:A,DangerButton:D,RadioInput:E},props:{errors:Object,admissionForm:Object,formResponse:Object},data(){const c={response_data:{}};return this.admissionForm.schema.forEach(a=>{["text","number","email","file","password","radio"].includes(a.type)?c.response_data[a.field_name]=null:a.type==="checkbox"?c.response_data[a.field_name]=[]:a.type==="select"&&(c.response_data[a.field_name]="")}),{form:F(c)}},mounted(){this.formResponse.response_data&&Object.assign(this.form.response_data,this.formResponse.response_data)}},P={class:"py-12"},Z={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},G={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},J={class:"p-6"},K={class:"text-2xl font-bold capitalize"},M={class:"mt-4"},Q={class:"space-y-5"},W={key:1},X=["value"],Y={key:2,class:"flex items-center space-x-4"},q={key:3,class:"flex items-center gap-4"};function ee(c,p,a,te,r,oe){const k=m("Head"),u=m("InputLabel"),x=m("TextInput"),g=m("SelectInput"),w=m("Checkbox"),V=m("RadioInput"),I=m("AuthenticatedLayout");return o(),s(f,null,[n(k,{title:"Form Response | View Response"}),n(I,null,{header:y(()=>p[0]||(p[0]=[l("div",{class:"flex items-center"},[b(" Form Response "),l("span",{class:"material-symbols-outlined text-gray-400"}," keyboard_arrow_right "),b(" View Response ")],-1)])),default:y(()=>[l("div",P,[l("div",Z,[l("div",G,[l("div",J,[l("div",null,[l("p",K,v(a.admissionForm.title),1)]),l("div",M,[l("div",Q,[(o(!0),s(f,null,_(a.admissionForm.schema,(e,d)=>(o(),s("div",{key:d},[l("div",null,[n(u,{for:`field-${d}`,value:e.title,required:e.is_required},null,8,["for","value","required"]),["text","number","email","password"].includes(e.type)?(o(),L(x,{key:0,id:`field-${d}`,type:e.type,class:U(["mt-1 w-full",{"block w-full mt-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100":e.type=="file","file:bg-red-600 hover:file:bg-red-500 file:text-white":e.type=="file","border-red-600":r.form.errors[`response_data.${e.field_name}`]}]),modelValue:r.form.response_data[e.field_name],"onUpdate:modelValue":t=>r.form.response_data[e.field_name]=t,placeholder:e.title,disabled:!0},null,8,["id","type","modelValue","onUpdate:modelValue","placeholder","class"])):e.type==="select"?(o(),s("div",W,[n(g,{id:e.field_name,modelValue:r.form.response_data[e.field_name],"onUpdate:modelValue":t=>r.form.response_data[e.field_name]=t,class:"mt-1 w-full",disabled:!0},{default:y(()=>[p[1]||(p[1]=l("option",{value:"",disabled:"",selected:""},"-- Select an option --",-1)),(o(!0),s(f,null,_(e.options.split(","),t=>(o(),s("option",{key:t.trim(),value:t.trim()},v(t.trim()),9,X))),128))]),_:2},1032,["id","modelValue","onUpdate:modelValue"])])):e.type==="checkbox"?(o(),s("div",Y,[(o(!0),s(f,null,_(e.options.split(","),(t,i)=>(o(),s("div",{class:"mt-1 flex items-center space-x-2",key:i},[n(w,{id:`field-${d}-option-${i}`,checked:r.form.response_data[e.field_name],"onUpdate:checked":h=>r.form.response_data[e.field_name]=h,value:t.trim(),disabled:!0},null,8,["id","checked","onUpdate:checked","value"]),n(u,{for:`field-${d}-option-${i}`,value:t.trim()},null,8,["for","value"])]))),128))])):e.type=="radio"?(o(),s("div",q,[(o(!0),s(f,null,_(e.options.split(","),(t,i)=>(o(),s("div",{class:"mt-1 flex items-center space-x-2",key:i},[n(V,{id:`field-${d}-option-${i}`,checked:r.form.response_data[e.field_name],"onUpdate:checked":h=>r.form.response_data[e.field_name]=h,value:t.trim(),disabled:!0},null,8,["id","checked","onUpdate:checked","value"]),n(u,{for:`field-${d}-option-${i}`,value:t.trim()},null,8,["for","value"])]))),128))])):B("",!0)])]))),128))])])])])])])]),_:1})],64)}const _e=H(z,[["render",ee]]);export{_e as default};
