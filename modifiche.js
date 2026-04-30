function controlloNome(str) {
                let btn = document.getElementById("ConfermaInfo");
                if (str.length == 0 || str== "<?=$NomeUtente?>") {
                    document.getElementById("indicatore").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let risposta = this.responseText;
                            document.getElementById("indicatore").innerHTML = risposta;
                            if (risposta.trim().length > 0) {
                                btn.disabled = true;
                            } else {
                                btn.disabled = false;
                            }
                        }
                    };
                    xmlhttp.open("GET", "controlloNome.php?q=" + str, true);
                    xmlhttp.send();
                }
            }
            function validaEInvia(event) {
                event.preventDefault(); 

                let passwordAttuale = document.getElementById("password").value;
                let indicatore = document.getElementById("IndPass");
                let form = document.getElementById("formPassword");
                let passwordNuova = document.getElementById("passwordNuova").value;
                if (passwordNuova.indexOf(' ') >= 0) {
                    alert("La password non può contenere spazi!");
                    event.preventDefault();
                    return false;
                }
                if (passwordNuova.trim().length === 0) {
                    alert("La password non può essere vuota o composta solo da spazi!");
                    event.preventDefault();
                    return false;
                }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        let risposta = this.responseText.trim();
                        
                        if (risposta === "") {
                            indicatore.innerHTML = "";
                            form.submit(); 
                        } else {
                            indicatore.innerHTML = risposta;
                        }
                    }
                };
                
                xmlhttp.open("GET", "controlloPassword.php?q=" + encodeURIComponent(passwordAttuale), true);
                xmlhttp.send();

                return false;
            };
            function controllo(event){
                event.preventDefault(); 
                let nomeutente = document.getElementById("nomeutente").value;
                let form = document.getElementById("form");
                if (nomeutente.indexOf(' ') >= 0 || nomeutente.trim().length === 0) {
                    alert("Il nome utente non può contenere spazi o essere vuoto!");
                    event.preventDefault();
                    return false;
                }
                form.submit();
                return false;
            }