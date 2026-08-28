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

    public function cadastrarServico($descricao, $preco, $id_usuario){
        $sql = $this->pdo->prepare("INSERT INTO service (description, price, user_id_user, created_at) VALUES (:descricao, :preco, :id_usuario, NOW())");
        $sql->bindValue(':descricao', $descricao);
        $preco = $this->converterPreco($preco);
        $sql->bindValue(':preco', $preco);
        $sql->bindValue(':id_usuario', $id_usuario);
        return $sql->execute();
    }

    public function converterPreco($preco){
        if(strpos($preco, ',') !== false){
            $preco = str_replace('.', '', $preco);
            $preco = str_replace(',', '.', $preco);
        }
        return $preco;
    }

    public function buscarServicoPorId($id){
        $sql = $this->pdo->prepare("SELECT * FROM service WHERE id_service = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return $sql->fetch();
        } else{
            return null;
        }
    }

    public function atualizarServico($id, $descricao, $preco){
        $sql = $this->pdo->prepare("UPDATE service SET description = :descricao, price = :preco WHERE id_service = :id");
        $sql->bindValue(':descricao', $descricao);
        $preco = $this->converterPreco($preco);
        $sql->bindValue(':preco', $preco);
        $sql->bindValue(':id', $id);
        return $sql->execute();
    }

    public function removerServico($id){
        $sql = $this->pdo->prepare("DELETE FROM service WHERE id_service = :id");
        $sql->bindValue(':id', $id);
        return $sql->execute();
    }

    public function calcularComissao($preco){
        $preco = $this->converterPreco($preco);
        if($preco <= 1000){
            return $preco * 0.05;
        } elseif($preco <= 10000){
            return $preco * 0.10;
        } else{
            return $preco * 0.20;
        }
    }

    public function finalizarServico($id){
        $servico = $this->buscarServicoPorId($id);
        if($servico){
            $comissao = $this->calcularComissao($servico['price']);
            $sql = $this->pdo->prepare("UPDATE service 
                                            SET finished_at = NOW(), commission_user = :comissao 
                                            WHERE id_service = :id AND finished_at IS NULL");
            $sql->bindValue(':comissao', $comissao);
            $sql->bindValue(':id', $id);
            $sql->execute();
            return $sql->rowCount() > 0;
        } else{
            return false;
        }
    }
}