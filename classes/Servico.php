<?php
class Servico{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarServicosComNomeUsuario(){
        $sql = $this->pdo->prepare(
            "SELECT 
                s.id_service, s.description, s.price, 
                CASE WHEN s.finished_at IS NULL THEN 'Pendente' ELSE 'Finalizado' END as status, 
                u.name AS nome_usuario 
                FROM service AS s 
                INNER JOIN user AS u ON s.user_id_user = u.id_user
                ORDER BY s.created_at DESC");
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetchAll();
        } else{
            return [];
        }
    }
}