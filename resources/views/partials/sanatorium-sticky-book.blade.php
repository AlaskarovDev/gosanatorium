<style>
html{scroll-behavior:smooth}
#booking{scroll-margin-top:90px}
.sticky-book-mobile,.sticky-book-desktop{display:none}
.sticky-book-mobile{position:fixed;bottom:0;left:0;width:100%;z-index:999;background:#2ecc71;padding:12px 16px;box-shadow:0 -4px 16px rgba(0,0,0,.15)}
.sticky-book-mobile.visible{display:block}
.btn-book-sticky{display:block;text-align:center;color:#fff;font-size:16px;font-weight:800;text-decoration:none}
.btn-book-sticky:hover{color:#fff;text-decoration:none}
.sticky-book-desktop{position:fixed;right:0;top:50%;transform:translateY(-50%);z-index:999}
.sticky-book-desktop.visible{display:block}
.btn-book-sticky-desktop{display:block;background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:15px;font-weight:800;padding:14px 22px;border-radius:50px 0 0 50px;text-decoration:none;box-shadow:0 6px 20px rgba(26,158,135,.45)}
.btn-book-sticky-desktop:hover{color:#fff;text-decoration:none}
@media (min-width:768px){.sticky-book-mobile{display:none!important}}
@media (max-width:767px){.sticky-book-desktop{display:none!important}}
</style>
<div class="sticky-book-mobile" id="stickyBook"><a href="#booking" class="btn-book-sticky">📅 Забронировать — без предоплаты</a></div>
<div class="sticky-book-desktop" id="stickyBookDesktop"><a href="#booking" class="btn-book-sticky-desktop">Забронировать →</a></div>
<script>
(function(){
  var m=document.getElementById('stickyBook'),d=document.getElementById('stickyBookDesktop');
  var footer=document.querySelector('footer');
  function update(){
    var show=window.scrollY>400;
    if(show&&footer){show=footer.getBoundingClientRect().top>window.innerHeight;}
    m.classList.toggle('visible',show);
    d.classList.toggle('visible',show);
  }
  window.addEventListener('scroll',update,{passive:true});
  window.addEventListener('resize',update);
  update();
  [m,d].forEach(function(w){w.querySelector('a').addEventListener('click',function(e){
    var t=document.getElementById('booking');
    if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});}
  });});
})();
</script>
