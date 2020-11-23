<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Verifique se o ID do contato existe
if (isset($_GET['id'])) {
    // Selecione o registro que será excluído
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Não existe contato com esse ID!');
    }
    // Certifique-se de que o usuário confirma antes da exclusão
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // O usuário clicou no botão "Sim", excluir registro
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Você excluiu o contato!';
        }else{
            // O usuário clicou no botão "Não", redireciona-o de volta para a página de leitura
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

/*Para deletar um registro o código irá verificar se existe a variável de solicitação GET " id ", 
se existir então verificar se o registro existe na tabela de Contatos e confirmar ao usuário se deseja deletar o contato ou não, 
uma simples solicitação GET irá determinar em qual botão o usuário clicou (Sim ou Não).*/

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Contact #<?=$contact['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete contact #<?=$contact['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Yes</a>
        <a href="delete.php?id=<?=$contact['id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>