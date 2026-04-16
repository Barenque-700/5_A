// 1. Gestione Data
const oggi = new Date();
const opzioni = { day: '2-digit', month: 'long', year: 'numeric' };
document.getElementById('current-date').textContent = oggi.toLocaleDateString('it-IT', opzioni).toUpperCase();

// 2. Gestione Dark/Light Mode
const toggleBtn = document.getElementById('theme-toggle');
const body = document.body;
const Tema= 0;
toggleBtn.addEventListener('click', () => {
    if (body.getAttribute('data-theme') === 'dark') {
        body.setAttribute('data-theme', 'light');
        toggleBtn.innerHTML = "☀️";
        Tema = 1;
    } else {
        body.setAttribute('data-theme', 'dark');
        toggleBtn.innerHTML = "🌙";
        Tema = 0;
    }
});
if (Tema == null)
    alert("ciao");
if(Tema== 1){
    body.setAttribute('data-theme', 'light');
    toggleBtn.innerHTML = "☀️";
    alert("ciao");
}
else{
    body.setAttribute('data-theme', 'dark');
    toggleBtn.innerHTML = "🌙";

}