<style>
.gsdark-toggle{background:rgba(13,122,104,0.15)!important;border:1px solid rgba(13,122,104,0.6)!important;border-radius:8px!important;padding:6px 10px!important;margin-right:10px!important;cursor:pointer!important;color:#0d7a68!important;display:flex!important;align-items:center!important;justify-content:center!important;transition:background 0.2s!important}
.gsdark-toggle:hover{background:rgba(13,122,104,0.3)!important}
.gsdark-icon-moon{display:block!important}
.gsdark-icon-sun{display:none!important}
body.gsdark-mode .gsdark-icon-moon{display:none!important}
body.gsdark-mode .gsdark-icon-sun{display:block!important}

body.gsdark-mode .gsdark-toggle{background:rgba(255,255,255,0.15)!important;border:1px solid rgba(255,255,255,0.3)!important;color:#ffd54f!important}
body.gsdark-mode .gsdark-toggle:hover{background:rgba(255,255,255,0.25)!important}

body.gsdark-mode *{transition:background-color 0.3s,color 0.3s,border-color 0.3s!important}
body.gsdark-mode{background:#0f1923!important;color:#d0d8e0!important}
body.gsdark-mode header,body.gsdark-mode nav,body.gsdark-mode .navbar,body.gsdark-mode [class*="header"]{background:#141f2b!important;border-bottom:1px solid #1e2d3a!important}
body.gsdark-mode section,body.gsdark-mode .section{background:#0f1923!important}
body.gsdark-mode div:not([class*="teal"]):not([class*="gradient"]):not([class*="hcons"]):not([class*="gssticky-icon"]):not([class*="gscons2-contact-icon"]):not([class*="gsdark-toggle"]){background-color:inherit!important}
body.gsdark-mode .bg-white,body.gsdark-mode .bg-light,body.gsdark-mode [style*="background:#fff"],body.gsdark-mode [style*="background: #fff"],body.gsdark-mode [style*="background:white"]{background:#1a2636!important}
body.gsdark-mode .card,body.gsdark-mode [class*="-card"],body.gsdark-mode [class*="card-"]{background:#1a2636!important;border-color:#253545!important}
body.gsdark-mode h1,body.gsdark-mode h2,body.gsdark-mode h3,body.gsdark-mode h4,body.gsdark-mode h5,body.gsdark-mode h6{color:#eef2f5!important}
body.gsdark-mode p,body.gsdark-mode span:not([class*="icon"]),body.gsdark-mode li,body.gsdark-mode label{color:#b0bec8!important}
body.gsdark-mode a:not(.btn):not([class*="btn"]){color:#5dc9b4!important}
body.gsdark-mode input,body.gsdark-mode select,body.gsdark-mode textarea{background:#1a2636!important;color:#d0d8e0!important;border-color:#253545!important}
body.gsdark-mode table,body.gsdark-mode th,body.gsdark-mode td{background:#1a2636!important;border-color:#253545!important;color:#b0bec8!important}
body.gsdark-mode .gscons2-wrap{background:#111b26!important}
body.gsdark-mode .gscons2-card{background:#1a2636!important;box-shadow:0 4px 24px rgba(0,0,0,0.4)!important}
body.gsdark-mode .gscons2-name{color:#eef2f5!important}
body.gsdark-mode .gscons2-role{color:#2ec4a9!important}
body.gsdark-mode .gscons2-sub,body.gsdark-mode .gscons2-heading{color:#b0bec8!important}
body.gsdark-mode .gscons2-contact-row span,body.gsdark-mode .gscons2-contact-row a{color:#b0bec8!important}
body.gsdark-mode .gscons2-phone{color:#eef2f5!important}
body.gsdark-mode .gssticky-nav{background:#1a2636!important;box-shadow:0 4px 24px rgba(0,0,0,0.5)!important}
body.gsdark-mode .gssticky-label{color:#8fa0b0!important}
body.gsdark-mode .gssticky-item:hover{background:#253545!important}
body.gsdark-mode .gsfooter{background:#080d12!important}
body.gsdark-mode footer{background:#080d12!important;color:#b0bec8!important}
body.gsdark-mode .shadow,body.gsdark-mode [class*="shadow"]{box-shadow:0 4px 16px rgba(0,0,0,0.5)!important}
body.gsdark-mode .border,body.gsdark-mode [class*="border-"]{border-color:#253545!important}
body.gsdark-mode .text-muted{color:#7a909f!important}
body.gsdark-mode .text-dark{color:#d0d8e0!important}
body.gsdark-mode .dropdown-menu,body.gsdark-mode [class*="dropdown"]{background:#1a2636!important;border-color:#253545!important}
body.gsdark-mode img{opacity:0.92!important}
body.gsdark-mode .hcons-section{background:linear-gradient(135deg,#0d7a68 0%,#1a9e87 50%,#0d8a75 100%)!important}
body.gsdark-mode .hcons-feature{background:rgba(255,255,255,0.08)!important}
body.gsdark-mode .hcons-feature-title,body.gsdark-mode .hcons-feature-text{color:#fff!important}
</style>
<script>
function gsToggleDark(){
  var on=document.body.classList.toggle('gsdark-mode');
  try{localStorage.setItem('gsDarkMode',on?'1':'0');}catch(e){}
}
(function(){
  try{
    if(localStorage.getItem('gsDarkMode')==='1'){
      document.body.classList.add('gsdark-mode');
    }
  }catch(e){}
})();
</script>
