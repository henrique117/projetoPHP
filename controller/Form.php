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
    print_r($_POST);
    if (isset($_POST['marca']) && isset($_POST['configuracao']) && isset($_POST['valor'])) {
      try {
        $conexao = Transaction::get();
        $softwares = new Crud('softwares');
        $nome = $conexao->quote($_POST['nome']);
        $linguagem = $conexao->quote($_POST['linguagem']);
        $data = $conexao->quote($_POST['data']);
        $resultado = $computador->insert("nome,linguagem,data", "$nome,$linguagem,$data");
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