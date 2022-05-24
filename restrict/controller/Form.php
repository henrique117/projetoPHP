<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
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
  public function salvar()
  {
    if (isset($_POST['nome']) && isset($_POST['linguagem']) && isset($_POST['data'])) {
      try {
        $conexao = Transaction::get();
        $software = new Crud('softwares');
        $nome = $conexao->quote($_POST['nome']);
        $linguagem = $conexao->quote($_POST['linguagem']);
        $data = $conexao->quote($_POST['data']);
        if (empty($_POST["id"])) {
          $software->insert("nome,linguagem,data", "$nome,$linguagem,$data");
        } else {
          $id = $conexao->quote($_POST['id']);
          $software->update("nome=$nome,linguagem=$linguagem,data=$data", "id=$id");
        }
        $this->message = $software->getMessage();
        $this->error = $software->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $software = new Crud('softwares');
        $resultado = $software->select("*", "id=$id");
        if (!$software->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $nome) {
            $form->set($cod, $nome);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $software->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}