{{-- Scripts específicos para os modais --}}
<script>
$(document).ready(function() {
    // Função para mostrar notificações com fallback
    function showNotification(message, type) {
        // Tenta usar toastr primeiro
        if (typeof toastr !== 'undefined') {
            if (type === 'success') {
                toastr.success(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        } 
        // Fallback para SweetAlert2 se disponível
        else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Sucesso!' : 'Erro!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        }
        // Fallback final para alert nativo
        else {
            alert(message);
        }
    }
    
    // Validação do comentário obrigatório para avaliações Regular (3) e Ruim (4)
    $('#modalAvaliarChamado form').on('submit', function(e) {
        var avaliacaoSelecionada = $('input[name="avaliacao"]:checked').val();
        var comentario = $('#comentarioAvaliacao').val().trim();
        
        // Se selecionou Regular (3) ou Ruim (4) e não preencheu comentário
        if ((avaliacaoSelecionada == '3' || avaliacaoSelecionada == '4') && comentario === '') {
            e.preventDefault();
            showNotification('Por favor, deixe um comentário explicando sua avaliação.', 'error');
            $('#comentarioAvaliacao').focus();
            return false;
        }
    });
    
    // Atualizar indicação visual do campo comentário baseado na avaliação
    $('input[name="avaliacao"]').on('change', function() {
        var avaliacaoSelecionada = $(this).val();
        var comentarioLabel = $('label[for="comentarioAvaliacao"]');
        var comentarioField = $('#comentarioAvaliacao');
        var opcionalText = $('.opcional-text');
        
        if (avaliacaoSelecionada == '3' || avaliacaoSelecionada == '4') {
            // Tornar obrigatório visualmente
            if (!comentarioLabel.find('.text-danger').length) {
                comentarioLabel.append(' <span class="text-danger">*</span>');
            }
            opcionalText.html('(Obrigatório)').addClass('text-danger');
            comentarioField.attr('placeholder', 'Por favor, explique sua avaliação (obrigatório)');
        } else {
            // Remover obrigatoriedade visual
            comentarioLabel.find('.text-danger').remove();
            opcionalText.html('(Opcional)').removeClass('text-danger');
            comentarioField.attr('placeholder', 'Deixe um comentário sobre o atendimento recebido...');
        }
    });
    
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            showNotification('{{ $error }}', 'error');
        @endforeach
    @endif
    
    // Fechar modal após sucesso
    @if(session('success'))
        $('#modalComentario').modal('hide');
        $('#modalPendencia').modal('hide');
        $('#modalTransferir').modal('hide');
        $('#modalDevolver').modal('hide');
        $('#modalResolver').modal('hide');
        $('#modalAlterarResponsavel').modal('hide');
        $('#modalResponderUsuario').modal('hide');
        $('#modalAvaliarChamado').modal('hide');
        $('#modalReabrirChamado').modal('hide');
        // Limpar os formulários
        $('#modalComentario form')[0].reset();
        $('#modalPendencia form')[0].reset();
        $('#modalTransferir form')[0].reset();
        $('#modalDevolver form')[0].reset();
        $('#modalResolver form')[0].reset();
        $('#modalAlterarResponsavel form')[0].reset();
        $('#modalResponderUsuario form')[0].reset();
        $('#modalAvaliarChamado form')[0].reset();
        $('#modalReabrirChamado form')[0].reset();
    @endif
});
</script>
