document.addEventListener('DOMContentLoaded', () => {
    const podios = document.querySelectorAll('.podio')
    const topVenda = document.getElementById('topVenda')
    const top3 = document.querySelector('top3')

    podios.forEach((podio, index) => {
        podio.addEventListener('mouseenter', e => {
            const titulo = podio.querySelector('.titulo-podio')
            const text = podio.querySelector('.text-content')

            podio.parentElement.style.width = "100%"
            podio.parentElement.style.height = "100%"
            
            if(index == 0){
                titulo.textContent = "Nescafézes"
                text.style.fontSize = "20px"
                text.textContent = "Um café com gosto bem diferenciado"
            }else if(index == 1){
                titulo.textContent = "3 Infrações"
                text.style.fontSize = "20px"
                text.textContent = "Uma marca com apenas 3 registros de óbito após o consumo de seus produtos"
            }else{
                titulo.textContent = "Criolo"
                text.style.fontSize = "20px"
                text.textContent = "Uma marca de café que vem desde os anos 1500 sempre com bons cafés"
            } 
        })
        podio.addEventListener('mouseleave', e => {
            const titulo = podio.querySelector('.titulo-podio')
            const text = podio.querySelector('.text-content')
            
            podio.parentElement.style.width = ""
            podio.parentElement.style.height = ""
            
            if(index == 0){
                titulo.textContent = "Top " + 2
                text.style.fontSize = ""
                text.textContent = "☕☕"
            }else if(index == 1){
                titulo.textContent = "Top " + 1
                text.style.fontSize = ""
                text.textContent = "☕☕☕"
            }else{
                titulo.textContent = "Top " + 3
                text.style.fontSize = ""
                text.textContent = "☕"
            } 
        })
    })
})