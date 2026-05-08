const feedSeguiti = document.getElementById('feed-seguiti');
const feedEsplora = document.getElementById('feed-esplora');
const bottoneEsplora = document.getElementById('bottone-esplora');
const bottoneSeguiti= document.getElementById('bottone-seguiti');

bottoneEsplora.addEventListener('click', function(){
    feedEsplora.style.display = "block";
    feedSeguiti.style.display = "none";
    bottoneEsplora.style.border = "2px solid var(--accent-color)";
    bottoneSeguiti.style.border = "1px solid var(--primary-color)";
});
bottoneSeguiti.addEventListener('click', function(){
    feedSeguiti.style.display = "block";
    feedEsplora.style.display = "none";
    bottoneSeguiti.style.border = "2px solid var(--accent-color)";
    bottoneEsplora.style.border = "1px solid var(--primary-color)";
});