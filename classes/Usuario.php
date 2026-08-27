<?php
class Usuario{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function logar($email, $senha){
          $sql = $this->pdo->prepare("SELECT id_user, name, password, ativo FROM user WHERE email = :u");
          $sql->bindValue(":u", $email);
          $sql->execute();

          if($sql->rowCount() > 0){
               $dado = $sql->fetch();
               $auth = password_verify($senha, $dado['password']);
               if($auth && $dado['ativo'] == 1){
                    $_SESSION['id_user'] = $dado['id_user'];
                    return true;
               } else{
                    return false;
               }
          } else{
               return false;
          }
    }
}