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



document.addEventListener('click', function (e) {
    const btn = e.target.closest('.like-button');
    
    if (btn) {
        e.preventDefault();
        
        const postId = btn.getAttribute('data-postid');
        const likeSpan = document.getElementById(`like-count-${postId}`);
        const icon = document.getElementById(`icon-${postId}`);

        btn.style.pointerEvents = 'none'; 

        const formData = new FormData();
        formData.append('id_post', postId);

        fetch('GestioneLike.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Errore di rete');
            return response.json();
        })
        .then(data => {
            if (data.stato !== 'errore') {
                const allSpans = document.querySelectorAll(`[id="like-count-${postId}"]`);
                const allIcons = document.querySelectorAll(`[id="icon-${postId}"]`);
                
                allSpans.forEach(s => s.innerText = data.totale);
                allIcons.forEach(i => {
                    if (data.stato === 'aggiunto') {
                        i.classList.replace('fa-heart-o', 'fa-heart');
                        i.style.color = "red";
                    } else {
                        i.classList.replace('fa-heart', 'fa-heart-o');
                        i.style.color = "";
                    }
                });
            }
        })
        .catch(error => console.error('Errore:', error))
        .finally(() => {
            btn.style.pointerEvents = 'auto';
        });
    }
});