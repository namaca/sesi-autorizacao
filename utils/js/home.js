var alunos = [];

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/get_nomealunos.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log(response)
            for(i in response){
                alunos.push(response[i].nome);
            }

            // Popula o datalist com os nomes dos alunos
            var datalist = document.getElementById("alunos");
            alunos.forEach(function(aluno) {
                console.log(alunos)
                var option = document.createElement("option");
                option.value = aluno;
                datalist.appendChild(option);
            });
        } else {
            console.error('Erro ao verificar código');
            console.log('asdasd')
        }
    };
    xhr.send();

    
    
    document.getElementById("formfoda").addEventListener("submit", function(event) {
        const id = document.getElementById('senha').value.trim();
        console.log(id)

        event.preventDefault();

        xhr.open('GET', `api/checarfuncionario.php?id=${id}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(`api/checarfuncionario.php?id=${id}`)
                console.log(response)
                if(response.res){
                    document.getElementById('error-cod').style.color = 'white';
                    document.getElementById('error-cod').textContent = '';

                    document.getElementById('formfoda').submit();
                } else {
                    document.getElementById('error-cod').style.color = 'red';
                    document.getElementById('error-cod').textContent = 'Código invalido';
                }
            } else {
                console.error('Erro ao verificar código');
            }
        };
        xhr.send();
    })

    var input = document.getElementById('senha');

    // Add an event listener for input events
    input.addEventListener('input', function(event) {
      // Get the input value
      var inputValue = event.target.value;

      // Remove non-numeric characters using regular expression
      var numericValue = inputValue.replace(/\D/g, '');

      // Update the input value with only numeric characters
      event.target.value = numericValue;
    })
    // evento onclick que será ativo quando chamar o yes e tornará a div visivel ou não
    function exibirmore() {
        document.getElementById('more').style.display = 'block';
    }
    function escondermore() {
        document.getElementById('more').style.display = 'none';
        document.getElementById('responsavel').value = '';
        document.getElementById('parentesco').value = '';
    }