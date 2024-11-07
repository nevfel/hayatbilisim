import{Q as f,d as u,J as g,o as l,e as r,n as c,a as e,f as d,t as p,i as h,b as v,y as w}from"./app-CayAy1B_.js";import{A as _}from"./ApplicationLogo-CgoeIvT_.js";const b={class:"max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8"},k={class:"flex items-center justify-between flex-wrap"},x={class:"w-0 flex-1 flex items-center min-w-0"},y={key:0,class:"h-5 w-5 text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},M={key:1,class:"h-5 w-5 text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},$={class:"ms-3 font-medium text-sm text-white truncate"},B={class:"shrink-0 sm:ms-3"},Y={__name:"Banner",setup(m){const a=f(),i=u(!0),t=u("success"),s=u("");return g(async()=>{var o,n;t.value=((o=a.props.jetstream.flash)==null?void 0:o.bannerStyle)||"success",s.value=((n=a.props.jetstream.flash)==null?void 0:n.banner)||"",i.value=!0}),(o,n)=>(l(),r("div",null,[i.value&&s.value?(l(),r("div",{key:0,class:c({"bg-indigo-500":t.value=="success","bg-red-700":t.value=="danger"})},[e("div",b,[e("div",k,[e("div",x,[e("span",{class:c(["flex p-2 rounded-lg",{"bg-indigo-600":t.value=="success","bg-red-600":t.value=="danger"}])},[t.value=="success"?(l(),r("svg",y,n[1]||(n[1]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"},null,-1)]))):d("",!0),t.value=="danger"?(l(),r("svg",M,n[2]||(n[2]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"},null,-1)]))):d("",!0)],2),e("p",$,p(s.value),1)]),e("div",B,[e("button",{type:"button",class:c(["-me-1 flex p-2 rounded-md focus:outline-none sm:-me-2 transition",{"hover:bg-indigo-600 focus:bg-indigo-600":t.value=="success","hover:bg-red-600 focus:bg-red-600":t.value=="danger"}]),"aria-label":"Dismiss",onClick:n[0]||(n[0]=h(T=>i.value=!1,["prevent"]))},n[3]||(n[3]=[e("svg",{class:"h-5 w-5 text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"})],-1)]),2)])])])],2)):d("",!0)]))}},C={class:"w-screen bg-base-100 z-10"},j={class:"navbar max-w-7xl mx-auto bg-base-100 flex justify-between items-center"},z={class:"flex items-center"},L={class:"btn btn-ghost text-xl ml-2"},N={class:"hidden md:flex"},A={class:"menu menu-horizontal px-1"},P=["href"],H={key:0},V={class:"p-2 bg-base-100 rounded-t-none"},D=["href"],E=["href"],S={key:1},U=["href"],F={key:0,class:"md:hidden"},G={class:"menu menu-vertical px-2"},J=["href"],O={key:0},Q={class:"p-2 bg-base-100 rounded-t-none"},q=["href"],I=["href"],K={key:1},R=["href"],Z={__name:"Navbar",setup(m){const a=()=>{w.post(route("logout"))},i=u(!1),t=()=>{i.value=!i.value};return(s,o)=>(l(),r("nav",C,[e("div",j,[e("div",z,[v(_,{class:"block h-9 w-auto"}),e("a",L,p(s.$page.props.appName),1)]),e("div",N,[e("ul",A,[e("li",null,[e("a",{href:s.route("welcome")},"Anasayfa",8,P)]),o[1]||(o[1]=e("li",null,[e("a",{href:"/#messaging"},"Bize Ulaşın")],-1)),s.$page.props.user?(l(),r("li",H,[e("details",null,[o[0]||(o[0]=e("summary",null," Hesabım ",-1)),e("ul",V,[e("li",null,[e("a",{href:s.route("dashboard")},"Panel",8,D)]),e("li",null,[e("a",{href:s.route("profile.show")},"Profil",8,E)]),e("li",null,[e("a",{onClick:h(a,["prevent"])},"Çıkış")])])])])):(l(),r("li",S,[e("a",{href:s.route("login")},"Giriş",8,U)]))])]),e("div",{class:"md:hidden flex items-center"},[e("button",{onClick:t,class:"btn btn-ghost"},o[2]||(o[2]=[e("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-6 w-6",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"})],-1)]))])]),i.value?(l(),r("div",F,[e("ul",G,[e("li",null,[e("a",{href:s.route("welcome")},"Anasayfa",8,J)]),o[4]||(o[4]=e("li",null,[e("a",{href:"/#messaging"},"Bize Ulaşın")],-1)),s.$page.props.user?(l(),r("li",O,[e("details",null,[o[3]||(o[3]=e("summary",null," Hesabım ",-1)),e("ul",Q,[e("li",null,[e("a",{href:s.route("dashboard")},"Panel",8,q)]),e("li",null,[e("a",{href:s.route("profile.show")},"Profil",8,I)]),e("li",null,[e("a",{onClick:h(a,["prevent"])},"Çıkış")])])])])):(l(),r("li",K,[e("a",{href:s.route("login")},"Login",8,R)]))])])):d("",!0)]))}};export{Y as _,Z as a};
