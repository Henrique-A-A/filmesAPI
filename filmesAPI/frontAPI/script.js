const form = document.querySelector('#filmesForm')
const tituloInput = document.querySelector('#tituloInput')
const diretorInput = document.querySelector('#diretorInput')
const ano_lancamentoInput = document.querySelector('#ano_lancamentoInput')
const generoInput = document.querySelector('#generoInput')
const url = 'http://localhost:8000/filmes.php'

const tableBody = document.querySelector('#filmesTable')

function carregarFilmes() {
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        mode: 'cors'
    })
        .then(response => response.json())
        .then(filmes => {
            tableBody.innerHTML = ''

            for (let i = 0; i < filmes.length; i++) {
                const tr = document.createElement('tr')
                const filme = filmes[i]
                console.log(filme)
                tr.innerHTML = `
                    <td>${filme.id}</td>
                    <td>${filme.titulo}</td>
                    <td>${filme.diretor}</td>
                    <td>${filme.ano_lancamento}</td>
                    <td>${filme.genero}</td>
                    <td>
                        <button data-id="${filme.id}" onclick="atualizarFilme(${filme.id})">Editar</button>
                        <button onclick="excluirFilme(${filme.id})">Excluir</button>
                    </td>
                `
                tableBody.appendChild(tr)
            }

        })
}

//função para criar um filme
function adicionarFilme(e) {

    e.preventDefault()

    const titulo = tituloInput.value
    const diretor = diretorInput.value
    const ano_lancamento = ano_lancamentoInput.value
    const genero = generoInput.value

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `titulo=${encodeURIComponent(titulo)}&diretor=${encodeURIComponent(diretor)}&ano_lancamento=${encodeURIComponent(ano_lancamento)}&genero=${encodeURIComponent(genero)}`
    })
        .then(response => {
            if (response.ok) {
                carregarFilmes()
                tituloInput.value = ''
                diretorInput.value = ''
                ano_lancamentoInput.value = ''
                generoInput.value = ''
            } else {
                console.error('Erro ao add filme')
                alert('Erro ao add filme')
            }
        })
}

//função para atualizar um filme
function atualizarFilme(id) {
    const novoTitulo = prompt("Digite o novo titulo")
    const novodiretor = prompt("Digite o novo diretor")
    const novoAno = prompt("Digite o novo ano")
    const novoGenero = prompt("Digite o novo genero")

    if (novoTitulo && novodiretor && novoTitulo) {
        fetch(`${url}?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: `titulo=${encodeURIComponent(novoTitulo)}&diretor=${encodeURIComponent(novodiretor)}&ano_lancamento=${encodeURIComponent(novoAno)}&genero=${encodeURIComponent(novoGenero)}`
        })
            .then(response => {
                if (response.ok) {
                    carregarFilmes()
                } else {
                    console.error('Erro ao editar o filme')
                    alert('Erro ao editar o filme')
                }
            })
    }
}

function excluirFilme(id) {
    if (confirm('Deseja excluir esse filme?')) {
        fetch(`${url}?id=${id}`, {
            method: 'DELETE'
        })

            .then(response => {
                if (response.ok) {
                    carregarFilmes()
                } else {
                    console.error('Erro ao excluir o filme')
                    alert('Erro ao excluir o filme')
                }
            })
    }
}

form.addEventListener('submit', adicionarFilme)

carregarFilmes()