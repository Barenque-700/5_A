// 1. Gestione Data
const oggi = new Date();
const opzioni = { day: '2-digit', month: 'long', year: 'numeric' };
document.getElementById('current-date').textContent = oggi.toLocaleDateString('it-IT', opzioni).toUpperCase();

// 2. Gestione Dark/Light Mode
const toggleBtn = document.getElementById('theme-toggle');
const body = document.body;

// Al caricamento, applica il tema salvato (default: dark)
const temaSalvato = localStorage.getItem('tema') || 'dark';
body.setAttribute('data-theme', temaSalvato);
toggleBtn.innerHTML = temaSalvato === 'dark' ? '🌙' : '☀️';

toggleBtn.addEventListener('click', () => {
    if (body.getAttribute('data-theme') === 'dark') {
        body.setAttribute('data-theme', 'light');
        toggleBtn.innerHTML = '☀️';
        localStorage.setItem('tema', 'light');
    } else {
        body.setAttribute('data-theme', 'dark');
        toggleBtn.innerHTML = '🌙';
        localStorage.setItem('tema', 'dark');
    }
});