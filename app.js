document.getElementById('insightBtn')?.addEventListener('click', async function () {
  const btn=this, out=document.getElementById('insightOutput');
  btn.disabled=true; btn.textContent='Analyzing…'; out.textContent='Reviewing your inventory…';
  try {
    const response=await fetch('ai_insights.php');
    const data=await response.json();
    if(!response.ok) throw new Error(data.error || 'Could not generate insights.');
    out.textContent=data.text;
  } catch(e) { out.textContent=e.message; }
  finally { btn.disabled=false; btn.textContent='Generate insights'; }
});
