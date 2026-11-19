/* NEW YEAR MODE — loaded (defer) only while the mode is active */
(function(){
var d=document,w=window,root=d.documentElement,me=d.currentScript,end=me&&me.getAttribute('data-end'),bar=d.getElementById('nyBar'),today='';
try{today=new Intl.DateTimeFormat('en-CA',{timeZone:'Asia/Baku',year:'numeric',month:'2-digit',day:'2-digit'}).format(new Date())}catch(e){}
if(end&&today&&today>end){root.classList.remove('ny-on');if(bar){bar.parentNode.removeChild(bar);w.gsTopbarUpdate&&w.gsTopbarUpdate()}return}
function pl(n){var a=n%10,b=n%100;return a==1&&b!=11?'день':a>=2&&a<=4&&(b<12||b>14)?'дня':'дней'}
var a=bar&&bar.querySelector('.ny-bar__link');
if(a&&/^\d{4}-\d\d-\d\d$/.test(today)){var p=today.split('-'),y=+p[0],m=+p[1],dd=+p[2],t,n;
if(m<=2)t='🎄 С Новым '+y+' годом! Зимние цены в санаториях →';
else if(m==12&&dd==31)t='🎄 Новый год уже сегодня ночью! Новогодние предложения в санаториях →';
else{n=Math.round((Date.UTC(y+1,0,1)-Date.UTC(y,m-1,dd))/864e5);t='🎄 До Нового года '+n+' '+pl(n)+'. Новогодние предложения в санаториях →'}
if(a.textContent!==t)a.textContent=t}
if(w.matchMedia&&w.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
var KEY='nySnow',on=true,cv,ctx,fl=[],raf=0,W=0,H=0,last=0,btn=d.createElement('button'),pt=0;
try{on=localStorage.getItem(KEY)!=='off'}catch(e){}
btn.type='button';btn.className='ny-snow-btn';btn.textContent='❄';
function label(){var s=on?'Выключить снег':'Включить снег';btn.setAttribute('aria-label',s);btn.title=s;btn.setAttribute('aria-pressed',on?'true':'false');btn.classList.toggle('is-off',!on)}
btn.addEventListener('click',function(){on=!on;try{localStorage.setItem(KEY,on?'on':'off')}catch(e){}label();on?start():stop(true)});
function fixedOf(e){for(;e&&e!==d.body&&e!==root;e=e.parentElement)if(getComputedStyle(e).position==='fixed')return e;return null}
function place(){var b=16,i,e,f,r;btn.style.pointerEvents='none';
for(i=0;i<6;i++){e=d.elementFromPoint(36,H-b-20)||d.elementFromPoint(18,H-b-2);f=e&&fixedOf(e);if(!f){e=d.elementFromPoint(18,H-b-2);f=e&&fixedOf(e)}if(!f)break;r=f.getBoundingClientRect();if(r.top<H/2)break;b=H-r.top+10}
btn.style.pointerEvents='';btn.style.bottom=b+'px'}
function onScroll(){if(pt)return;pt=setTimeout(function(){pt=0;place()},400)}
function mk(any){return{x:Math.random()*W,y:any?Math.random()*H:-6,r:1.2+Math.random()*2.4,s:.5+Math.random()*1.1,dx:Math.random()*.5-.25,p:Math.random()*6.3}}
function size(){W=w.innerWidth;H=w.innerHeight;if(cv){cv.width=W;cv.height=H}var n=W<768?35:70;while(fl.length<n)fl.push(mk(1));fl.length=n;place()}
function frame(t){var k=last?Math.min(3,(t-last)/16.7):1,i,f;last=t;ctx.clearRect(0,0,W,H);ctx.beginPath();
for(i=0;i<fl.length;i++){f=fl[i];f.y+=f.s*k;f.p+=.02*k;f.x+=(f.dx+Math.sin(f.p)*.35)*k;if(f.y>H+6||f.x<-6||f.x>W+6){fl[i]=f=mk(0)}ctx.moveTo(f.x+f.r,f.y);ctx.arc(f.x,f.y,f.r,0,6.2832)}
ctx.fill();ctx.stroke();raf=w.requestAnimationFrame(frame)}
function start(){if(raf||!on||d.hidden)return;cv.style.display='';last=0;raf=w.requestAnimationFrame(frame)}
function stop(clear){if(raf)w.cancelAnimationFrame(raf);raf=0;if(clear){ctx.clearRect(0,0,W,H);cv.style.display='none'}}
function init(){cv=d.createElement('canvas');cv.className='ny-snow';cv.setAttribute('aria-hidden','true');ctx=cv.getContext('2d');if(!ctx)return;
d.body.appendChild(cv);d.body.appendChild(btn);label();size();ctx.fillStyle='rgba(255,255,255,.95)';ctx.strokeStyle='rgba(90,130,170,.45)';ctx.lineWidth=.8;
var rt;w.addEventListener('resize',function(){clearTimeout(rt);rt=setTimeout(function(){size();ctx.fillStyle='rgba(255,255,255,.95)';ctx.strokeStyle='rgba(90,130,170,.45)';ctx.lineWidth=.8},200)});
w.addEventListener('scroll',onScroll,{passive:true});d.addEventListener('click',onScroll,true);
d.addEventListener('visibilitychange',function(){d.hidden?stop(false):start()});
if(on)start();else cv.style.display='none';setTimeout(place,3000)}
if(d.readyState==='complete')init();else w.addEventListener('load',init);
})();
