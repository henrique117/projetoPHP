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
        $resultado = $softwares->insert("nome,linguagem,data", "$nome,$linguagem,$data");
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