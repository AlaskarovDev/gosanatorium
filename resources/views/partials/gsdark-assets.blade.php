<style>
.gsdark-toggle{background:rgba(13,122,104,0.15)!important;border:1px solid rgba(13,122,104,0.6)!important;border-radius:8px!important;padding:6px 10px!important;margin-right:10px!important;cursor:pointer!important;color:#0d7a68!important;display:flex!important;align-items:center!important;justify-content:center!important;transition:background 0.2s!important}
.gsdark-toggle:hover{background:rgba(13,122,104,0.3)!important}
.gsdark-icon-moon{display:block!important}
.gsdark-icon-sun{display:none!important}
body.gsdark-mode .gsdark-icon-moon{display:none!important}
body.gsdark-mode .gsdark-icon-sun{display:block!important}

body.gsdark-mode .gsdark-toggle{background:rgba(255,255,255,0.15)!important;border:1px solid rgba(255,255,255,0.3)!important;color:#ffd54f!important}
body.gsdark-mode .gsdark-toggle:hover{background:rgba(255,255,255,0.25)!important}

body.gsdark-mode{background:#0f1923!important;color:#d0d8e0!important}

body.gsdark-mode header,
body.gsdark-mode nav,
body.gsdark-mode .navbar{background:#141f2b!important;border-bottom:1px solid #1e2d3a!important}

body.gsdark-mode h1,
body.gsdark-mode h2,
body.gsdark-mode h3,
body.gsdark-mode h4,
body.gsdark-mode h5,
body.gsdark-mode h6{color:#eef2f5!important}

body.gsdark-mode p,
body.gsdark-mode li,
body.gsdark-mode label,
body.gsdark-mode .text-muted{color:#a0b0bc!important}

body.gsdark-mode a:not(.btn):not([class*="btn"]):not([class*="nav-link"]):not(.gsdark-toggle){color:#5dc9b4!important}

body.gsdark-mode input,
body.gsdark-mode select,
body.gsdark-mode textarea{background:#1a2636!important;color:#d0d8e0!important;border-color:#253545!important}

body.gsdark-mode .card,
body.gsdark-mode .hotel-card{background:#1a2636!important;border-color:#253545!important}

body.gsdark-mode .bg-white{background:#1a2636!important}
body.gsdark-mode .bg-light{background:#162230!important}
body.gsdark-mode .text-dark{color:#d0d8e0!important}

body.gsdark-mode .gscons2-wrap{background:#111b26!important}
body.gsdark-mode .gscons2-card{background:#1a2636!important}
body.gsdark-mode .gscons2-name{color:#eef2f5!important}
body.gsdark-mode .gscons2-role{color:#2ec4a9!important}
body.gsdark-mode .gscons2-heading{color:#eef2f5!important}
body.gsdark-mode .gscons2-sub{color:#a0b0bc!important}
body.gsdark-mode .gscons2-phone{color:#eef2f5!important}

body.gsdark-mode .gssticky-nav{background:#1a2636!important}
body.gsdark-mode .gssticky-label{color:#8fa0b0!important}
body.gsdark-mode .gssticky-item:hover{background:#253545!important}

body.gsdark-mode footer,
body.gsdark-mode .gsfooter{background:#080d12!important}

body.gsdark-mode .dropdown-menu{background:#1a2636!important;border-color:#253545!important}
body.gsdark-mode .dropdown-item{color:#d0d8e0!important}
body.gsdark-mode .dropdown-item:hover{background:#253545!important}

body.gsdark-mode main,
body.gsdark-mode .main,
body.gsdark-mode #main,
body.gsdark-mode [id*="main"],
body.gsdark-mode .container,
body.gsdark-mode .container-fluid,
body.gsdark-mode .wrapper,
body.gsdark-mode #wrapper,
body.gsdark-mode .page-wrapper,
body.gsdark-mode .content-wrapper,
body.gsdark-mode .site-content,
body.gsdark-mode #content{background:#0f1923!important;color:#d0d8e0!important}

body.gsdark-mode section{background:#0f1923!important}

body.gsdark-mode .row > div,
body.gsdark-mode .col,
body.gsdark-mode [class*="col-"]{background:transparent!important}

body.gsdark-mode .feature-box,
body.gsdark-mode .feature-item,
body.gsdark-mode .service-box,
body.gsdark-mode .info-box,
body.gsdark-mode .icon-box{background:#1a2636!important;color:#d0d8e0!important}

body.gsdark-mode .feature-box p,
body.gsdark-mode .feature-item p,
body.gsdark-mode .icon-box p,
body.gsdark-mode .info-box p{color:#a0b0bc!important}

body.gsdark-mode .feature-box h3,
body.gsdark-mode .feature-item h3,
body.gsdark-mode .icon-box h3{color:#eef2f5!important}

body.gsdark-mode .feature-box .icon,
body.gsdark-mode .feature-item .icon,
body.gsdark-mode [class*="icon-wrap"],
body.gsdark-mode [class*="icon-bg"]{background:#1e3a4a!important}
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
