$(document).ready(() => {

	$('#documentacao').on('click', () => {
        
        $.post('documentacao.html', data =>{
            $('#pagina').html(data)
        })
    })
    $('#suporte').on('click', () => {
                
        $.post('suporte.html', data =>{
            $('#pagina').html(data)
        })

    })

    //ajax

    $('#competencia').on('change', e =>{
        let competencia = $(e.target).val()
        
        //requisição http por ajax
        //metodo, url, dados, sucesso, erro
        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#clientesAtivos').html(dados.clientesAtivos)
                $('#clientesInativos').html(dados.clientesInativos)
                $('#contatoReclamacao').html(dados.contatoReclamacao)
                $('#contatoElogio').html(dados.contatoElogio)
                $('#contatoSugestao').html(dados.contatoSugestao)
                $('#totalDespesas').html(dados.totalDespesas)
                
                //console.log(dados)
                
            },
            error:  erro => {console.log(dados)}
        })
    })


})