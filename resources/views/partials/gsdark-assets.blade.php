<style>
.gsdark-toggle{background:rgba(13,122,104,0.15)!important;border:1px solid rgba(13,122,104,0.6)!important;border-radius:8px!important;padding:6px 10px!important;margin-right:10px!important;cursor:pointer!important;color:#0d7a68!important;display:flex!important;align-items:center!important;justify-content:center!important;transition:background 0.2s!important}
.gsdark-toggle:hover{background:rgba(13,122,104,0.3)!important}
.gsdark-icon-moon{display:block!important}
.gsdark-icon-sun{display:none!important}
body.gsdark-mode .gsdark-icon-moon{display:none!important}
body.gsdark-mode .gsdark-icon-sun{display:block!important}

body.gsdark-mode{background:#0f1923!important;color:#e0e0e0!important}
body.gsdark-mode .gsdark-toggle{background:rgba(0,0,0,0.3)!important;border-color:rgba(255,255,255,0.3)!important;color:#ffd54f!important}
body.gsdark-mode header,body.gsdark-mode nav,body.gsdark-mode .navbar{background-color:#1a2530!important;border-bottom:1px solid #2a3540!important}
body.gsdark-mode .card,body.gsdark-mode .hotel-card,body.gsdark-mode [class*="card"]{background-color:#1e2d3a!important;border-color:#2a3540!important;color:#e0e0e0!important}
body.gsdark-mode h1,body.gsdark-mode h2,body.gsdark-mode h3,body.gsdark-mode h4,body.gsdark-mode h5{color:#f0f0f0!important}
body.gsdark-mode p,body.gsdark-mode span,body.gsdark-mode li,body.gsdark-mode a:not([class*="btn"]){color:#c0c8d0!important}
body.gsdark-mode .gscons2-wrap{background:#162230!important}
body.gsdark-mode .gscons2-card{background:#1e2d3a!important;box-shadow:0 4px 24px rgba(0,0,0,0.3)!important}
body.gsdark-mode .gscons2-name{color:#f0f0f0!important}
body.gsdark-mode .gssticky-nav{background:#1e2d3a!important;box-shadow:0 4px 24px rgba(0,0,0,0.4)!important}
body.gsdark-mode .gssticky-label{color:#b0bec5!important}
body.gsdark-mode footer,body.gsdark-mode .gsfooter{background-color:#080e14!important}
body.gsdark-mode input,body.gsdark-mode select,body.gsdark-mode textarea{background-color:#1e2d3a!important;color:#e0e0e0!important;border-color:#2a3540!important}
body.gsdark-mode .bg-white,body.gsdark-mode [class*="bg-white"]{background-color:#1e2d3a!important}
body.gsdark-mode .bg-light,body.gsdark-mode [class*="bg-light"]{background-color:#162230!important}
body.gsdark-mode .text-dark,body.gsdark-mode [class*="text-dark"]{color:#e0e0e0!important}
body.gsdark-mode .border,body.gsdark-mode [class*="border"]{border-color:#2a3540!important}
body.gsdark-mode .shadow,body.gsdark-mode [class*="shadow"]{box-shadow:0 4px 16px rgba(0,0,0,0.4)!important}
body.gsdark-mode section{background-color:#0f1923!important}
body.gsdark-mode .hcons-section{background:linear-gradient(135deg,#0d7a68 0%,#1a9e87 50%,#0d8a75 100%)!important}
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
