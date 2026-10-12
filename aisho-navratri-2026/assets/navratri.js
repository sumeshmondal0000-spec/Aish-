(()=>{const questions=[
{q:"Your Garba energy is…",o:["Front row, full power","Easy steps, big smile","I learn as I go","Snack break enthusiast"]},
{q:"Pick your festive look.",o:["Classic chaniya choli","Minimal and elegant","Bright and experimental","Comfort comes first"]},
{q:"When the beat drops…",o:["I lead the circle","I follow the vibe","I cheer everyone on","I need one more practice"]},
{q:"Your ideal Garba partner…",o:["Matches my energy","Makes me laugh","Teaches me a step","Never judges my moves"]},
{q:"After Garba plans?",o:["Late-night chai","Long drive and music","Street food together","Straight home, happy"]}] ;
const start=document.querySelector(".garba-start"),hero=document.querySelector(".garba-hero"),quiz=document.querySelector(".garba-quiz"),result=document.querySelector(".garba-result"),title=document.querySelector(".quiz-question"),opts=document.querySelector(".quiz-options"),count=document.querySelector(".quiz-count b"),bar=document.querySelector(".quiz-progress span"),restart=document.querySelector(".garba-restart");let step=0,score=0;
function show(){const item=questions[step];title.textContent=item.q;count.textContent=step+1;bar.style.width=((step+1)/questions.length*100)+"%";opts.innerHTML="";item.o.forEach((x,i)=>{const b=document.createElement("button");b.type="button";b.textContent=x;b.onclick=()=>{score+=4-i;step++;step<questions.length?show():finish()};opts.appendChild(b)})}
function begin(){step=0;score=0;hero.hidden=true;result.hidden=true;quiz.hidden=false;show();scrollTo({top:0,behavior:"smooth"})}
function finish(){quiz.hidden=true;result.hidden=false;const pct=88+Math.min(10,score%11);document.querySelector(".result-score").textContent=pct+"%";scrollTo({top:0,behavior:"smooth"})}
start&&start.addEventListener("click",begin);restart&&restart.addEventListener("click",begin);
})();