import{_ as S}from"./AuthenticatedLayout-BlAmliJP.js";import{Z as L,T as U,k as m,f as l,a,w as _,F as f,o,b as t,d as b,t as k,e as $,l as u,n as x,c as C,g as T}from"./app-ctv0rznj.js";import{_ as j}from"./LinkButton-CgjH23nr.js";import{P as N}from"./PrimaryButton-Bpy3ozRt.js";import{a as O,b as P,_ as A}from"./TextInput-C4PvDNml.js";import{_ as D}from"./SelectInput-DRrKjI14.js";import{_ as H}from"./Checkbox-Dka3b9Kh.js";import{D as z}from"./DangerButton-CmYcJXZx.js";import{_ as M}from"./RadioInput-Dtfa8P8o.js";import{_ as Z}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./ApplicationLogo-BsOgWnat.js";const G={components:{AuthenticatedLayout:S,Head:L,LinkButton:j,PrimaryButton:N,TextInput:O,InputError:P,InputLabel:A,SelectInput:D,Checkbox:H,DangerButton:z,RadioInput:M},props:{errors:Object,admissionForm:Object,formResponse:Object},data(){const c={response_data:{}};return this.admissionForm.schema.forEach(i=>{["text","number","email","file","password","radio"].includes(i.type)?c.response_data[i.field_name]=null:i.type==="checkbox"?c.response_data[i.field_name]=[]:i.type==="select"&&(c.response_data[i.field_name]="")}),{form:U(c)}},mounted(){this.formResponse.response_data&&Object.assign(this.form.response_data,this.formResponse.response_data)},methods:{submit(){this.form.put(route("admin.form_responses.update",{response:this.formResponse.uuid}),{onSuccess:()=>{toastr.success("Entry successfully updated"),this.resetForm()},onError:()=>{toastr.error("Something went wrong")}})},resetForm(){this.form.reset(),this.form.clearErrors()}}},J={class:"py-12"},K={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},Q={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},W={class:"p-6"},X={class:"text-2xl font-bold capitalize"},Y={class:"mt-4"},q={class:"space-y-5"},ee={key:1},te=["value"],se={key:2,class:"flex items-center space-x-4"},oe={key:3,class:"flex items-center gap-4"};function re(c,n,i,le,r,v){const g=m("Head"),h=m("InputLabel"),w=m("TextInput"),I=m("SelectInput"),V=m("Checkbox"),E=m("RadioInput"),F=m("InputError"),R=m("PrimaryButton"),B=m("AuthenticatedLayout");return o(),l(f,null,[a(g,{title:"Form Response | Edit Response"}),a(B,null,{header:_(()=>n[1]||(n[1]=[t("div",{class:"flex items-center"},[b(" Form Response "),t("span",{class:"material-symbols-outlined text-gray-400"}," keyboard_arrow_right "),b(" Edit Response ")],-1)])),default:_(()=>[t("div",J,[t("div",K,[t("div",Q,[t("div",W,[t("div",null,[t("p",X,k(i.admissionForm.title),1)]),t("div",Y,[t("form",{onSubmit:n[0]||(n[0]=$((...e)=>v.submit&&v.submit(...e),["prevent"]))},[t("div",q,[(o(!0),l(f,null,u(i.admissionForm.schema,(e,d)=>(o(),l("div",{key:d},[t("div",null,[a(h,{for:`field-${d}`,value:e.title,required:e.is_required},null,8,["for","value","required"]),["text","number","email","password"].includes(e.type)?(o(),C(w,{key:0,id:`field-${d}`,type:e.type,class:x(["mt-1 w-full",{"block w-full mt-2 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100":e.type=="file","file:bg-red-600 hover:file:bg-red-500 file:text-white":e.type=="file","border-red-600":r.form.errors[`response_data.${e.field_name}`]}]),modelValue:r.form.response_data[e.field_name],"onUpdate:modelValue":s=>r.form.response_data[e.field_name]=s,placeholder:e.title},null,8,["id","type","modelValue","onUpdate:modelValue","placeholder","class"])):e.type==="select"?(o(),l("div",ee,[a(I,{id:e.field_name,modelValue:r.form.response_data[e.field_name],"onUpdate:modelValue":s=>r.form.response_data[e.field_name]=s,class:"mt-1 w-full"},{default:_(()=>[n[2]||(n[2]=t("option",{value:"",disabled:"",selected:""}," -- Select an option -- ",-1)),(o(!0),l(f,null,u(e.options.split(","),s=>(o(),l("option",{key:s.trim(),value:s.trim()},k(s.trim()),9,te))),128))]),_:2},1032,["id","modelValue","onUpdate:modelValue"])])):e.type==="checkbox"?(o(),l("div",se,[(o(!0),l(f,null,u(e.options.split(","),(s,p)=>(o(),l("div",{class:"mt-1 flex items-center space-x-2",key:p},[a(V,{id:`field-${d}-option-${p}`,checked:r.form.response_data[e.field_name],"onUpdate:checked":y=>r.form.response_data[e.field_name]=y,value:s.trim()},null,8,["id","checked","onUpdate:checked","value"]),a(h,{for:`field-${d}-option-${p}`,value:s.trim()},null,8,["for","value"])]))),128))])):e.type=="radio"?(o(),l("div",oe,[(o(!0),l(f,null,u(e.options.split(","),(s,p)=>(o(),l("div",{class:"mt-1 flex items-center space-x-2",key:p},[a(E,{id:`field-${d}-option-${p}`,checked:r.form.response_data[e.field_name],"onUpdate:checked":y=>r.form.response_data[e.field_name]=y,value:s.trim()},null,8,["id","checked","onUpdate:checked","value"]),a(h,{for:`field-${d}-option-${p}`,value:s.trim()},null,8,["for","value"])]))),128))])):T("",!0),a(F,{message:r.form.errors[`response_data.${e.field_name}`]},null,8,["message"])])]))),128)),t("div",null,[a(R,{type:"submit",disabled:r.form.processing,class:x({"opacity-25":r.form.processing})},{default:_(()=>n[3]||(n[3]=[b(" Submit ")])),_:1},8,["disabled","class"])])])],32)])])])])])]),_:1})],64)}const ye=Z(G,[["render",re]]);export{ye as default};
