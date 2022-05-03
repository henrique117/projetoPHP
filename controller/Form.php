<?php
class Form
{
  private $message = "";
  public function __construct() {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("nome", "");
    $form->set("linguagem", "");
    $form->set("data", "");
    $this->message = $form->saida();
  }

  public function salvar() {
    if (isset($_POST['nome']) && isset($_POST['linguagem']) && isset($_POST['data'])) {
      try {
        $conexao = Transaction::get();
        $softwares = new Crud('softwares');
        $nome = $conexao->quote($_POST['nome']);
        $linguagem = $conexao->quote($_POST['linguagem']);
        $data = $conexao->quote($_POST['data']);
        if (empty($_POST["id"])) {
          $softwares->insert("nome,linguagem,data", "$nome,$linguagem,$data");
        } else {
          $id = $conexao->quote($_POST['id']);
          $softwares->update("nome=$nome,linguagem=$linguagem,data=$data", "id=$id");
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $softwares = new Crud('softwares');
        $resultado = $softwares->select("*", "id=$id");
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function __destruct() {
    Transaction::close();
  }

}