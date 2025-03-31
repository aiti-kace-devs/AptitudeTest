import{_ as x}from"./AuthenticatedLayout-BlAmliJP.js";import{Z as _,N as l,k as s,f as b,a,w as n,F as D,o as v,b as o,d as i}from"./app-ctv0rznj.js";import{_ as M}from"./LinkButton-CgjH23nr.js";import{M as k}from"./MenuDropdown-Co-I7j6P.js";import{D as B}from"./DangerButton-CmYcJXZx.js";import{_ as C}from"./Modal-BR_I9X5c.js";import{_ as L}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./ApplicationLogo-BsOgWnat.js";const T={components:{AuthenticatedLayout:x,Head:_,LinkButton:M,MenuDropdown:k,DangerButton:B,Modal:C},data(){return{destroyModal:!1,selectedRow:null}},mounted(){this.fetch(),$(document).on("click",".dropdown-toggle",e=>{const t=$(e.target).attr("dropdown-log");this.$refs.menuDropdown&&e.target.classList.contains("dropdown-span")&&this.$refs.menuDropdown.toggleDropdown(t)}),$(document).on("click","body",e=>{this.$refs.menuDropdown&&!e.target.classList.contains("dropdown-span")&&this.$refs.menuDropdown.closeAllDropdowns()}),$(document).on("click",".edit",e=>{const t=$(e.currentTarget).data("id");l.get(route("admin.form.edit",t))}),$(document).on("click",".preview",e=>{const t=$(e.currentTarget).data("id");l.get(route("admin.form.preview",t))}),$(document).on("click",".responses",e=>{const t=$(e.currentTarget).data("id");l.get(route("admin.form.show",t))}),$(document).on("click",".delete",e=>{const t=$(e.currentTarget).data("id");this.showDestroyModal(t)})},methods:{fetch(){$("#data_table").DataTable({destroy:!0,stateSave:!1,processing:!1,serverSide:!0,ajax:{url:route("admin.form.fetch")},columns:[{data:"title",name:"title"},{data:"date",name:"date"},{data:"action",name:"action",orderable:!1,searchable:!1}],lengthMenu:[[10,25,50,100,-1],["10","25","50","100","All"]],order:[[1,"desc"]],columnDefs:[{width:"5%",targets:-1}],createdRow:function(e,t,c){var d=$(e).find(".dropdown-menu");d.length>0&&d.width(160)}})},showDestroyModal(e){this.selectedRow=e,this.destroyModal=!0},hideDestroyModal(){this.destroyModal=!1,this.selectedRow=null},destroy(){axios.post(route("admin.form.destroy",{form:this.selectedRow})).then(e=>[this.hideDestroyModal(),toastr.success("Form successfully deleted"),this.fetch()]).catch(e=>console.log(e))}}},A={class:"py-12"},F={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},N={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},R={class:"p-6"},j={class:"flex justify-end"},H={class:"flex justify-center mt-6 gap-4"};function V(e,t,c,d,u,r){const m=s("Head"),f=s("MenuDropdown"),p=s("LinkButton"),g=s("DangerButton"),h=s("Modal"),w=s("AuthenticatedLayout");return v(),b(D,null,[a(m,{title:"Forms"}),a(w,null,{header:n(()=>t[1]||(t[1]=[o("div",{class:"flex items-center"},"Forms",-1)])),default:n(()=>[a(f,{ref:"menuDropdown"},null,512),o("div",A,[o("div",F,[o("div",N,[o("div",R,[o("div",j,[a(p,{href:e.route("admin.form.create")},{default:n(()=>t[2]||(t[2]=[o("span",{class:"material-symbols-outlined mr-2"}," add ",-1),i(" create form ")])),_:1},8,["href"])]),t[3]||(t[3]=o("div",{class:"flex flex-col mt-4"},[o("div",{class:"overflow-x-auto sm:-mx-6 lg:-mx-8"},[o("div",{class:"py-2 inline-w-full sm:px-6 lg:px-8"},[o("div",{class:"overflow-x-auto"},[o("table",{id:"data_table",class:"w-full text-sm table-striped"},[o("thead",{class:"capitalize border-b bg-gray-100 font-medium"},[o("tr",null,[o("th",{scope:"col",class:"text-gray-900 px-6 py-4 text-left"}," Title "),o("th",{scope:"col",class:"text-gray-900 px-6 py-4 text-left"}," created at "),o("th",{scope:"col",class:"text-gray-900 px-6 py-4 text-left"}," action ")])])])])])])],-1))])])])]),a(h,{show:u.destroyModal,closeable:!0,modalTitle:"delete form",onClose:r.hideDestroyModal,maxWidth:"md"},{default:n(()=>[t[5]||(t[5]=o("div",{class:"flex justify-center mt-4"},[o("p",{class:"text-lg"},"Are you sure you want to delete this form?")],-1)),o("div",H,[a(g,{onClick:r.destroy,type:"submit"},{default:n(()=>t[4]||(t[4]=[i(" Yes ")])),_:1},8,["onClick"]),o("button",{onClick:t[0]||(t[0]=(...y)=>r.hideDestroyModal&&r.hideDestroyModal(...y)),type:"button",class:"block items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:cursor-not-allowed"}," Cancel ")])]),_:1},8,["show","onClose"])]),_:1})],64)}const G=L(T,[["render",V]]);export{G as default};
