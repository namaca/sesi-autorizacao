const searchParams = new URLSearchParams(window.location.search);
// Função para imprimir as informações recebidas no console
function logNotification() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.responseText)
                var notification = JSON.parse(xhr.responseText);
                console.log("Informações recebidas:", notification);
                if(notification.payload.includes('sucesso')){
                  document.getElementById('response').innerText = 'Autorizado';
                } else {
                  document.getElementById('response').innerText = 'Não autorizado';
                }
                
                setTimeout(() => {
                  window.location = '/sesi-autorizacao2/supa/home'
                }, 4000);
            } else {
                console.error("Erro ao carregar informações:", xhr.status, xhr.statusText);
            }
        }
    };
    xhr.open("GET", `./api/getAutorizacao.php?token=${searchParams.get('token')}`, true);
    xhr.send();
}

// Chama a função logNotification() quando a página é carregada
window.onload = logNotification;