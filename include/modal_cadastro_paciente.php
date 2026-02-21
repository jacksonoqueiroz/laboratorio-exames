
<?php
include_once './include/head.php';
?>
<div class="modal fade" id="modalPaciente" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Cadastrar Paciente</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div id="erroPaciente" class="text-danger mb-2"></div>

<form id="formPaciente">

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" id="nome" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">CPF</label>
        <input type="text" name="cpf" id="cpf" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Data de Nascimento</label>
        <input type="date" name="data_nascimento" class="form-control" id="data_nascimento" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Tipo de Atendimento</label>
        <select name="tipo_atendimento" class="form-control" id="tipo_atendimento" required>
            <option value="">Selecione</option>
            <option value="Particular">Particular</option>
            <option value="Convenio">Convênio</option>
        </select>
    </div>
</div>

</form>

</div>

<div class="modal-footer">
    <button type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
        Cancelar
    </button>

    <button type="button"
            class="btn btn-success"
            onclick="salvarPaciente()">
        Salvar Paciente
    </button>
</div>

</div>
</div>
</div>

<script>
function salvarPaciente() {

    const form = document.getElementById('formPaciente');
    const dados = new FormData(form);

    fetch('<?= URL ?>ajax/salvar_paciente.php', {
        method: 'POST',
        body: dados
    })
    .then(res => res.json())
    .then(ret => {

        if (ret.erro) {
            document.getElementById('erroPaciente').innerText = ret.erro;
            return;
        }

        document.getElementById('paciente_nome').value = ret.nome;
        document.getElementById('paciente_id').value = ret.id;

        document.getElementById('cpf').innerText = ret.cpf;
        document.getElementById('data_nascimento').innerText = ret.data_nascimento;
        document.getElementById('dadosPaciente').style.display = 'block';

        bootstrap.Modal.getInstance(
            document.getElementById('modalPaciente')
        ).hide();

        form.reset();
        document.getElementById('erroPaciente').innerText = '';
    })
    .catch(() => {
        document.getElementById('erroPaciente').innerText =
            'Erro ao salvar paciente.';
    });
}
</script>
