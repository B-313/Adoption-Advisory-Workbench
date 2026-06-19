<script>
'use strict';
const SECTORS = REF.sectors, BENCH = REF.bench, TECHS = REF.techs,
      BARRIERS = REF.barriers, PHASES = REF.phases, SIZES = REF.sizes, STATES = REF.states;

function lsGet(k,d){ try{ const v=localStorage.getItem(k); return v?JSON.parse(v):d; }catch(e){ return d; } }
let services  = lsGet('aaa_services', []);
let customers = lsGet('aaa_customers', []);
let invites   = lsGet('aaa_invites', []);
function save(){ localStorage.setItem('aaa_services',JSON.stringify(services));
  localStorage.setItem('aaa_customers',JSON.stringify(customers));
  localStorage.setItem('aaa_invites',JSON.stringify(invites)); }

const $i = id => document.getElementById(id);
const esc = s => String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
const money = n => '£'+parseFloat(n||0).toLocaleString('en-GB');
const fdate = d => d?String(d).slice(0,10):'—';
const today = () => new Date().toISOString().slice(0,10);
const nowTs = () => new Date().toISOString();
const uid = () => Date.now()+Math.floor(Math.random()*9999);
const slug = s => String(s||'').toLowerCase().replace(/[^a-z]+/g,'-');
const stBadge = s => '<span class="badge-st st-'+slug(s)+'">'+esc(s)+'</span>';
const scoreColor = n => n>=60?'#059669':n>=35?'#d4a017':'#dc2626';
const scoreChip = n => '<span class="score-chip" style="background:'+scoreColor(n)+'">'+n+'</span>';
const custById = id => customers.find(c=>c.id===id);
function calcScore(c){ const tb=(c.techs?c.techs.length:0)/TECHS.length*100;
  return Math.round(0.4*(c.usagePct||0)+0.25*(c.staffPct||0)+0.2*tb+0.15*(c.readiness||0)); }
function vsBench(c){ const b=BENCH[c.sector]||16, diff=(c.usagePct||0)-b;
  if(diff>=5) return {cls:'tl-ahead',label:'Ahead +'+diff,diff};
  if(diff<=-5) return {cls:'tl-behind',label:'Behind '+diff,diff};
  return {cls:'tl-at',label:'At sector',diff}; }
const tlBadge = c => { const t=vsBench(c); return '<span class="tl '+t.cls+'"><span class="tl-dot"></span>'+t.label+'</span>'; };
function phaseFor(s){ for(let i=0;i<PHASES.length;i++){ if(s<PHASES[i].max) return i; } return PHASES.length-1; }
function toast(msg){ const a=$i('toast-area'); if(!a) return; const d=document.createElement('div');
  d.className='t-msg'; d.textContent=msg; a.appendChild(d); setTimeout(()=>d.remove(),3000); }
const _charts={};
function mk(id,cfg){ if(_charts[id]) _charts[id].destroy(); const c=$i(id); if(!c) return; _charts[id]=new Chart(c,cfg); }

function addService(e){ e.preventDefault();
  services.push({id:uid(),name:$i('sv-name').value.trim(),desc:$i('sv-desc').value.trim(),
    dur:$i('sv-dur').value.trim(),price:$i('sv-price').value||0});
  save(); e.target.reset(); toast('Service added'); renderServices(); }
function delService(id){ services=services.filter(s=>s.id!==id); save(); renderServices(); toast('Service removed'); }
function renderServices(){ const g=$i('svc-grid'); if(!g) return;
  g.innerHTML = services.length ? services.map(s=>
    '<div class="col-md-6 col-lg-4"><div class="svc"><div class="d-flex justify-content-between"><h5>'+esc(s.name)+
    '</h5><button class="btn-close" style="font-size:.6rem;" onclick="delService('+s.id+')"></button></div>'+
    '<p class="text-muted" style="font-size:.83rem;min-height:38px;">'+esc(s.desc)+'</p>'+
    '<div class="d-flex justify-content-between align-items-center"><span class="price">'+money(s.price)+
    '</span><span class="stat-pill">'+esc(s.dur||'—')+'</span></div></div></div>').join('')
    : '<div class="col-12 text-center text-muted py-5">No services yet. Add one, or load demo data.</div>'; }

function addCustomer(e){ e.preventDefault();
  customers.push({id:uid(),name:$i('cu-name').value.trim(),email:$i('cu-email').value.trim(),
    sector:$i('cu-sector').value,size:$i('cu-size').value,state:'Non-adopter',status:'Active',
    scored:false,usagePct:0,staffPct:0,techs:[],readiness:0});
  save(); e.target.reset(); toast('Customer added'); renderCustomers(); }
function toggleStatus(id){ const c=custById(id); c.status=c.status==='Active'?'Closed':'Active'; save(); renderCustomers(); toast(c.name+' → '+c.status); }
function delCustomer(id){ customers=customers.filter(c=>c.id!==id); save(); renderCustomers(); toast('Customer removed'); }
function renderCustomers(){ const tb=$i('cust-tbody'); if(!tb) return;
  const q=($i('cust-search')?.value||'').toLowerCase(), fSt=$i('cust-fStatus')?.value||'',
        fState=$i('cust-fState')?.value||'', fSec=$i('cust-fSector')?.value||'';
  const rows=customers.filter(c=>(!q||(c.name+c.sector).toLowerCase().includes(q))&&(!fSt||c.status===fSt)&&(!fState||c.state===fState)&&(!fSec||c.sector===fSec));
  tb.innerHTML = rows.length ? rows.map(c=>{ const sc=c.scored?calcScore(c):null;
    return '<tr'+(c.status==='Closed'?' style="opacity:.55;"':'')+'><td><strong>'+esc(c.name)+'</strong><div class="text-muted" style="font-size:.72rem;">'+esc(c.email)+
    '</div></td><td style="font-size:.79rem;">'+esc(c.sector)+'</td><td>'+esc(c.size)+'</td><td>'+stBadge(c.state)+
    '</td><td>'+(sc!==null?scoreChip(sc):'<span class="text-muted">—</span>')+'</td><td>'+(c.scored?tlBadge(c):'<span class="text-muted">—</span>')+
    '</td><td>'+stBadge(c.status)+'</td><td style="white-space:nowrap;">'+
    '<button class="btn btn-sm btn-outline-primary" style="font-size:.68rem;padding:1px 7px;" onclick="toggleStatus('+c.id+')">'+(c.status==='Active'?'Close':'Reopen')+'</button> '+
    '<button class="btn btn-sm btn-outline-danger" style="font-size:.68rem;padding:1px 7px;" onclick="delCustomer('+c.id+')">Delete</button></td></tr>';
  }).join('') : '<tr><td colspan="8" class="text-center text-muted py-4">No customers found.</td></tr>'; }

function fillCustSelect(){ const s=$i('em-cust'); if(!s) return;
  s.innerHTML='<option value="">— pick customer —</option>'+customers.filter(c=>c.status==='Active').map(c=>'<option value="'+c.id+'">'+esc(c.name)+'</option>').join('');
  const sv=$i('em-svc'); if(sv) sv.innerHTML=services.map(s=>'<option>'+esc(s.name)+'</option>').join(''); }
function sendInvite(e){ e.preventDefault(); const cid=parseInt($i('em-cust').value,10); const c=custById(cid);
  if(!c){ toast('Pick a customer first'); return; }
  invites.push({id:uid(),customerId:cid,customerName:c.name,service:$i('em-svc').value,subject:$i('em-subj').value,stage:'Sent',sentTs:nowTs(),respondedTs:null,scanScore:null});
  save(); toast('Invite sent to '+c.name); renderEmails(); }
function advance(id){ const i=invites.find(x=>x.id===id); if(!i) return;
  if(i.stage==='Draft'){ i.stage='Sent'; i.sentTs=nowTs(); }
  else if(i.stage==='Sent'){ i.stage='Opened'; }
  else if(i.stage==='Opened'){ i.stage='Responded'; i.respondedTs=nowTs();
    const sc=40+Math.floor(Math.random()*55); i.scanScore=sc; const c=custById(i.customerId);
    if(c){ c.scored=true; c.usagePct=sc; c.staffPct=Math.max(10,sc-10-Math.floor(Math.random()*15));
      c.readiness=Math.min(100,sc+Math.floor(Math.random()*20)); c.techs=TECHS.slice(0,Math.min(TECHS.length,1+Math.floor(sc/30)));
      c.state=sc>=45?'Current user':sc>=25?'Planner':'Non-adopter'; } }
  save(); renderEmails(); toast('Lifecycle advanced → '+i.stage); }
function renderEmails(){ const tb=$i('emails-tbody'); if(!tb) return; fillCustSelect();
  const by={Draft:0,Sent:0,Opened:0,Responded:0}; invites.forEach(i=>by[i.stage]=(by[i.stage]||0)+1);
  const p=$i('email-pills'); if(p) p.innerHTML=Object.keys(by).map(k=>'<span class="stat-pill">'+k+' <b>'+by[k]+'</b></span>').join('');
  const rows=[...invites].reverse();
  tb.innerHTML = rows.length ? rows.map(i=>{ const adv=i.stage!=='Responded';
    return '<tr><td><strong>'+esc(i.customerName)+'</strong></td><td style="font-size:.79rem;">'+esc(i.subject)+'</td><td style="font-size:.78rem;">'+esc(i.service||'—')+
    '</td><td style="font-size:.76rem;white-space:nowrap;">'+(i.sentTs?fdate(i.sentTs):'—')+'</td><td>'+stBadge(i.stage)+'</td><td>'+
    (adv?'<button class="btn btn-sm btn-outline-primary" style="font-size:.68rem;padding:1px 8px;" onclick="advance('+i.id+')">'+(i.stage==='Draft'?'Send':i.stage==='Sent'?'Mark opened':'Mark responded')+'</button>':'<span class="text-success">✓</span>')+'</td></tr>';
  }).join('') : '<tr><td colspan="6" class="text-center text-muted py-4">No invites yet.</td></tr>'; }

function renderTracking(){ const f=$i('funnel'); if(!f) return;
  const sent=invites.filter(i=>i.stage!=='Draft'), opened=invites.filter(i=>i.stage==='Opened'||i.stage==='Responded'), resp=invites.filter(i=>i.stage==='Responded');
  const max=Math.max(1,sent.length);
  f.innerHTML=[['Sent',sent.length,'#059669'],['Opened',opened.length,'#34b97f'],['Responded',resp.length,'#C9A227']].map(s=>{
    const pct=Math.round(s[1]/max*100);
    return '<div class="funnel-step"><div class="funnel-bar" style="background:'+s[2]+';width:'+Math.max(12,pct)+'%;">'+s[0]+' · '+s[1]+'</div><div class="funnel-meta">'+pct+'% of sent</div></div>';
  }).join('')+'<div class="text-muted mt-2" style="font-size:.78rem;">Overall response rate: <b style="color:var(--emerald-deep);">'+(sent.length?Math.round(resp.length/sent.length*100):0)+'%</b></div>';
  const bySec={}; SECTORS.forEach(s=>bySec[s]=0); resp.forEach(i=>{ const c=custById(i.customerId); if(c) bySec[c.sector]++; });
  const lbl=SECTORS.filter(s=>bySec[s]>0);
  mk('chart-resp-sector',{type:'bar',data:{labels:lbl,datasets:[{label:'Responses',data:lbl.map(s=>bySec[s]),backgroundColor:'#059669',borderRadius:3}]},
    options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,ticks:{stepSize:1}},y:{grid:{display:false},ticks:{font:{size:10}}}}}});
  const tb=$i('track-tbody'); const sr=[...sent].reverse();
  tb.innerHTML = sr.length ? sr.map(i=>{ const c=custById(i.customerId)||{};
    return '<tr><td><strong>'+esc(i.customerName)+'</strong></td><td style="font-size:.78rem;">'+esc(c.sector||'—')+'</td><td style="font-size:.76rem;">'+fdate(i.sentTs)+
    '</td><td style="font-size:.76rem;">'+(i.respondedTs?fdate(i.respondedTs):'—')+'</td><td>'+(i.respondedTs?Math.round((new Date(i.respondedTs)-new Date(i.sentTs))/864e5)+'d':'—')+
    '</td><td>'+stBadge(i.stage)+'</td><td>'+(i.scanScore!=null?scoreChip(i.scanScore):'<span class="text-muted">—</span>')+'</td></tr>';
  }).join('') : '<tr><td colspan="7" class="text-center text-muted py-4">No invites sent yet.</td></tr>'; }

function renderInsights(){ if(!$i('insights-kpis')) return;
  const scored=customers.filter(c=>c.scored), sent=invites.filter(i=>i.stage!=='Draft').length, resp=invites.filter(i=>i.stage==='Responded').length;
  const avg=scored.length?Math.round(scored.reduce((s,c)=>s+calcScore(c),0)/scored.length):0;
  const ahead=scored.filter(c=>vsBench(c).diff>=5).length;
  $i('insights-kpis').innerHTML=[['Scans Completed',resp,''],['Response Rate',(sent?Math.round(resp/sent*100):0)+'%','gold'],['Avg Adoption Score',avg,'gold'],['Ahead of Sector',ahead+' / '+scored.length,'']]
    .map(k=>'<div class="col-6 col-lg-3"><div class="kpi '+k[2]+'"><div class="kv">'+k[1]+'</div><div class="kl">'+k[0]+'</div></div></div>').join('');
  const bySec={}; scored.forEach(c=>(bySec[c.sector]=bySec[c.sector]||[]).push(c.usagePct||0));
  const lbl=Object.keys(bySec);
  mk('chart-bench',{type:'bar',data:{labels:lbl,datasets:[
    {label:'Your clients',data:lbl.map(s=>Math.round(bySec[s].reduce((a,b)=>a+b,0)/bySec[s].length)),backgroundColor:'#059669',borderRadius:3},
    {label:'Sector benchmark',data:lbl.map(s=>BENCH[s]),backgroundColor:'#C9A227',borderRadius:3}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{x:{grid:{display:false},ticks:{font:{size:9}}},y:{beginAtZero:true,ticks:{callback:v=>v+'%'}}}}});
  mk('chart-tech',{type:'polarArea',data:{labels:TECHS,datasets:[{data:TECHS.map(t=>scored.filter(c=>(c.techs||[]).includes(t)).length),backgroundColor:['#059669','#34b97f','#C9A227','#F3E7BE']}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:10}}}}}});
  mk('chart-barriers',{type:'bar',data:{labels:BARRIERS.map(b=>b[0]),datasets:[{data:BARRIERS.map(b=>b[1]),backgroundColor:'#047857',borderRadius:3}]},
    options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,max:100,ticks:{callback:v=>v+'%'}},y:{grid:{display:false},ticks:{font:{size:10}}}}}});
  const rd=$i('readiness-scores');
  rd.innerHTML = scored.length ? [...scored].sort((a,b)=>(b.readiness||0)-(a.readiness||0)).slice(0,7).map(c=>
    '<div class="mb-2"><div class="d-flex justify-content-between" style="font-size:.8rem;"><span>'+esc(c.name)+'</span><strong>'+(c.readiness||0)+'%</strong></div><div class="prog-sm"><div style="width:'+(c.readiness||0)+'%;background:'+scoreColor(c.readiness||0)+';"></div></div></div>').join('')
    : '<div class="text-muted text-center py-4">No completed scans yet.</div>'; }

function renderSupport(){ const pc=$i('phase-cards'); if(!pc) return;
  const scored=customers.filter(c=>c.scored), counts=PHASES.map((_,i)=>scored.filter(c=>phaseFor(calcScore(c))===i).length);
  pc.innerHTML=PHASES.map((p,i)=>'<div class="col-md-6 col-lg-3"><div class="svc" style="border-top-color:'+p.color+';height:100%;">'+
    '<div class="d-flex justify-content-between align-items-center"><h5 style="color:'+p.color+';">'+p.name+'</h5><span class="score-chip" style="background:'+p.color+';">'+counts[i]+'</span></div>'+
    '<p class="text-muted" style="font-size:.8rem;min-height:42px;">'+esc(p.desc)+'</p><div style="font-size:.76rem;color:var(--emerald-deep);">'+esc(p.support)+'</div></div></div>').join('');
  mk('chart-phases',{type:'bar',data:{labels:PHASES.map(p=>p.name),datasets:[{data:counts,backgroundColor:PHASES.map(p=>p.color),borderRadius:4}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  const tb=$i('support-tbody');
  tb.innerHTML = scored.length ? [...scored].sort((a,b)=>calcScore(a)-calcScore(b)).map(c=>{ const sc=calcScore(c),p=PHASES[phaseFor(sc)];
    return '<tr><td><strong>'+esc(c.name)+'</strong><div class="text-muted" style="font-size:.72rem;">'+esc(c.sector)+'</div></td><td>'+scoreChip(sc)+
    '</td><td><span class="tl" style="color:'+p.color+';"><span class="tl-dot" style="background:'+p.color+';"></span>'+p.name+'</span></td><td style="font-size:.79rem;">'+esc(p.support)+'</td></tr>';
  }).join('') : '<tr><td colspan="4" class="text-center text-muted py-4">No completed scans yet — load demo data or send scans.</td></tr>'; }

function outreachChart(){ const sel=$i('outreach-range'); if(!sel) return; const range=sel.value, title=$i('outreach-title');
  let labels=[],sent=[],resp=[]; const fmt=(d,o)=>d.toLocaleDateString('en-GB',o);
  if(range==='year'){ for(let m=11;m>=0;m--){ const d=new Date(); d.setDate(1); d.setMonth(d.getMonth()-m); const ym=d.toISOString().slice(0,7);
    labels.push(fmt(d,{month:'short'}));
    sent.push(invites.filter(i=>i.stage!=='Draft'&&String(i.sentTs||'').slice(0,7)===ym).length);
    resp.push(invites.filter(i=>i.stage==='Responded'&&String(i.respondedTs||'').slice(0,7)===ym).length); }
    title.textContent='Scan Outreach — Last 12 Months';
  } else { const n=range==='month'?30:7; for(let k=n-1;k>=0;k--){ const d=new Date(); d.setDate(d.getDate()-k); const ds=d.toISOString().slice(0,10);
    labels.push(range==='month'?fmt(d,{day:'numeric',month:'short'}):fmt(d,{weekday:'short'}));
    sent.push(invites.filter(i=>i.stage!=='Draft'&&fdate(i.sentTs)===ds).length);
    resp.push(invites.filter(i=>i.stage==='Responded'&&fdate(i.respondedTs)===ds).length); }
    title.textContent='Scan Outreach — '+(range==='month'?'Last 30 Days':'Last 7 Days'); }
  mk('chart-outreach',{type:'bar',data:{labels,datasets:[{label:'Sent',data:sent,backgroundColor:'#059669',borderRadius:3},{label:'Responded',data:resp,backgroundColor:'#C9A227',borderRadius:3}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{stepSize:1}}}}}); }
function renderDashboard(){ if(!$i('kpi-sent')) return;
  const sent=invites.filter(i=>i.stage!=='Draft'), resp=invites.filter(i=>i.stage==='Responded'), active=customers.filter(c=>c.status==='Active'), scored=active.filter(c=>c.scored);
  const week=new Date(Date.now()-7*864e5).toISOString();
  $i('kpi-sent').textContent=sent.length; $i('kpi-sent-sub').textContent=sent.filter(i=>i.sentTs>week).length+' this week';
  $i('kpi-rate').textContent=(sent.length?Math.round(resp.length/sent.length*100):0)+'%'; $i('kpi-rate-sub').textContent=resp.length+' of '+sent.length+' responded';
  $i('kpi-active').textContent=active.length; $i('kpi-active-sub').textContent=customers.filter(c=>c.status==='Closed').length+' closed';
  $i('kpi-score').textContent=scored.length?Math.round(scored.reduce((s,c)=>s+calcScore(c),0)/scored.length):0;
  const acts=[...sent].sort((a,b)=>new Date(b.sentTs)-new Date(a.sentTs)).slice(0,8);
  $i('dash-activity').innerHTML = acts.length ? acts.map(i=>{ const c=custById(i.customerId)||{};
    return '<tr><td style="white-space:nowrap;font-size:.76rem;">'+esc(String(i.sentTs||'').slice(0,16).replace('T',' '))+'</td><td>'+esc(i.customerName)+'</td><td style="font-size:.79rem;">'+esc(i.service||'—')+'</td><td>'+stBadge(i.stage)+'</td><td style="font-size:.78rem;">'+esc(c.sector||'—')+'</td></tr>';
  }).join('') : '<tr><td colspan="5" class="text-center text-muted py-4">No outreach yet. Load demo data to begin.</td></tr>';
  const wait=invites.filter(i=>i.stage==='Sent'||i.stage==='Opened');
  $i('dash-awaiting').innerHTML = wait.length ? wait.slice(0,6).map(i=>
    '<div class="d-flex justify-content-between align-items-center mb-2"><div><strong>'+esc(i.customerName)+'</strong><div class="text-muted" style="font-size:.74rem;">'+Math.round((Date.now()-new Date(i.sentTs))/864e5)+'d ago</div></div>'+stBadge(i.stage)+'</div>').join('')
    : '<div class="text-muted text-center py-3">Nothing awaiting response.</div>';
  outreachChart();
  mk('chart-state',{type:'doughnut',data:{labels:STATES,datasets:[{data:STATES.map(s=>customers.filter(c=>c.state===s).length),backgroundColor:['#059669','#C9A227','#cbd5e1'],borderWidth:2,borderColor:'#fff'}]},
    options:{responsive:false,plugins:{legend:{position:'bottom',labels:{font:{size:10},boxWidth:12}}}}});
  const bs={}; scored.forEach(c=>(bs[c.sector]=bs[c.sector]||[]).push(c.usagePct||0)); const bl=Object.keys(bs);
  mk('chart-bench-mini',{type:'bar',data:{labels:bl.map(s=>s.split(' ')[0]),datasets:[
    {label:'Client',data:bl.map(s=>Math.round(bs[s].reduce((a,b)=>a+b,0)/bs[s].length)),backgroundColor:'#059669',borderRadius:3},
    {label:'Sector',data:bl.map(s=>BENCH[s]),backgroundColor:'#F3E7BE',borderRadius:3}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:10}}}},scales:{x:{grid:{display:false},ticks:{font:{size:9}}},y:{beginAtZero:true,max:60,ticks:{callback:v=>v+'%'}}}}}); }

function exportExcel(kind){
  let data,name;
  if(kind==='customers'){ data=customers.map(c=>({Company:c.name,Email:c.email,Sector:c.sector,Size:c.size,State:c.state,Engagement:c.status,Score:c.scored?calcScore(c):'',Usage:c.usagePct,Benchmark:BENCH[c.sector]})); name='customers'; }
  else { data=invites.map(i=>{ const c=custById(i.customerId)||{}; return {Customer:i.customerName,Sector:c.sector||'',Subject:i.subject,Service:i.service,Stage:i.stage,Sent:i.sentTs,Responded:i.respondedTs,ScanScore:i.scanScore}; }); name='invites'; }
  if(!data.length){ toast('Nothing to export'); return; }
  const ws=XLSX.utils.json_to_sheet(data), wb=XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb,ws,name); XLSX.writeFile(wb,'adoption-advisory-'+name+'.xlsx'); toast('Exported '+name+'.xlsx'); }

function seedDemo(){
  if((customers.length||invites.length||services.length) && !confirm('Replace current data with demo dataset?')) return;
  services=[
    {id:uid(),name:'AI Readiness Scan',desc:'10-minute survey benchmarking the SME against its sector.',dur:'1 day',price:0},
    {id:uid()+1,name:'Sector Benchmark Report',desc:'Detailed positioning vs sector adoption, drivers and barriers.',dur:'1 week',price:1200},
    {id:uid()+2,name:'AI Adoption Roadmap',desc:'Prioritised 12-month plan from quick wins to scaled deployment.',dur:'3 weeks',price:4500},
    {id:uid()+3,name:'Implementation Sprint',desc:'Hands-on delivery of a first high-impact AI use case.',dur:'6 weeks',price:9800}];
  const seed=[['Meridian Software','Information & Communication','Mid'],['Lighthouse Analytics','Information & Communication','Small'],
    ['Castle Financial','Finance & Real Estate','Large'],['Atlas Property Group','Finance & Real Estate','Mid'],
    ['Brightwork Consulting','Business Services','Small'],['Vantage HR Partners','Business Services','Micro'],
    ['Forge Manufacturing','Manufacturing','Mid'],['Northgate Construction','Construction','Mid'],
    ['Harvest Retail Group','Retail & Distribution','Large'],['CornerShop Collective','Retail & Distribution','Micro'],
    ['Swift Logistics','Transport & Logistics','Small'],['Bayview Hotels','Hotel & Catering','Mid']];
  customers=seed.map((r,idx)=>({id:uid()+idx,name:r[0],email:slug(r[0])+'@example.com',sector:r[1],size:r[2],
    state:'Non-adopter',status:idx%6===5?'Closed':'Active',scored:false,usagePct:0,staffPct:0,techs:[],readiness:0}));
  invites=[];
  customers.forEach((c,idx)=>{ if(idx%6===5) return;
    const stage=idx%5===0?'Sent':idx%5===1?'Opened':'Responded'; const sentDate=new Date(Date.now()-(idx+1)*864e5).toISOString();
    const inv={id:uid()+100+idx,customerId:c.id,customerName:c.name,service:'AI Readiness Scan',subject:'Your complimentary AI Readiness Scan',stage,sentTs:sentDate,respondedTs:null,scanScore:null};
    if(stage==='Responded'){ const b=BENCH[c.sector]; const sc=Math.max(8,Math.min(70,b+(idx%3-1)*9+Math.floor(Math.random()*8)));
      inv.respondedTs=new Date(new Date(sentDate).getTime()+(1+idx%4)*864e5).toISOString(); inv.scanScore=sc;
      c.scored=true; c.usagePct=sc; c.staffPct=Math.max(8,sc-12); c.readiness=Math.min(100,sc+15);
      c.techs=TECHS.slice(0,1+Math.floor(sc/22)); c.state=sc>=45?'Current user':sc>=25?'Planner':'Non-adopter'; }
    invites.push(inv); });
  save(); toast('Demo data loaded');
  (RENDER[document.body.dataset.view]||function(){})();
}

const RENDER={dashboard:renderDashboard,services:renderServices,customers:renderCustomers,emails:renderEmails,tracking:renderTracking,insights:renderInsights,support:renderSupport};
document.addEventListener('DOMContentLoaded',()=>{ (RENDER[document.body.dataset.view]||function(){})(); });
</script>
