// evento onclick que será ativo quando chamar o yes e tornará a div visivel ou não
function exibirmore() {
    document.getElementById('more').style.display = 'block';
}
function escondermore() {
    document.getElementById('more').style.display = 'none';
    document.getElementById('responsavel').value = '';
    document.getElementById('parentesco').value = '';
}

let alunos;

const xhr = new XMLHttpRequest();
xhr.open('GET', './api/get_alunos.php', true);
xhr.onload = function() {
    if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        alunos = response;
        if (response['1']) {
            for (let i in response) {
                // Create a new <div> element
                var divElement = document.createElement("div");

                // Set attributes for the <div> element
                divElement.setAttribute("id", "container");
                divElement.setAttribute("class", `aluno${i}`);

                divElement.innerHTML = `
                <a href="cordenador.php"><ul>${response[i]}</ul></a>
                <hr>
                <br>
                <div class="buttons">
                    <button class="fa fa-check autorizar${i}" style="color:rgb(41, 130, 72)"></button>
                    <button class="fa fa-times nautorizar${i}" style="color:rgb(226, 42, 42)"></button>
                </div>
                <br>
                `;

                // Append the <div> element to the body of the document
                document.body.appendChild(divElement);

                var botaoAutorizar = divElement.querySelector('.autorizar' + i);
                var botaoNautorizar = divElement.querySelector('.nautorizar' + i);

                botaoAutorizar.addEventListener('click', function() {
                    console.log('Autorizar clicado para aluno ' + i);
                    autorizarAluno(i);
                    // Adicione aqui o que deseja fazer quando o botão de autorizar for clicado
                });

                botaoNautorizar.addEventListener('click', function() {
                    console.log('Não autorizar clicado para aluno ' + i);
                    naoAutorizarAluno(i);
                    // Adicione aqui o que deseja fazer quando o botão de não autorizar for clicado
                });
            }
        } else {
            // Se o código não existir, submeter o formulário
            console.log("Não tem nenhum aluno")
        }
    } else {
        console.error('Erro ao verificar código');
    }
};
xhr.send();

function autorizarAluno(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Aluno autorizado com sucesso!');
            removerAluno(id);
        } else {
            console.error('Erro ao autorizar aluno');
        }
    };
    xhr.send(`action=autorizar&id=${id}&aluno=${alunos[id]}`);
}

function naoAutorizarAluno(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', window.location.href, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Aluno não autorizado e removido com sucesso!');
            removerAluno(id);
        } else {
            console.error('Erro ao não autorizar aluno');
        }
    };
    xhr.send(`action=nao_autorizar&id=${id}&aluno=${alunos[id]}`);
}


function removerAluno(id) {
    var alunoDiv = document.getElementsByClassName(`aluno${id}`)[0];
    alunoDiv.remove();
}