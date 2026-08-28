<?php

// Classe responsável por gerenciar os serviços
class Servico{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarServicosComNomeUsuario($inicio = null, $fim = null, $nome = null, $status = null, $usuario = null){
        $query = "SELECT s.*, u.name as nome_usuario, CASE WHEN s.finished_at IS NULL THEN 'Pendente' ELSE 'Finalizado' END as status
                  FROM service s
                  JOIN user u ON s.user_id_user = u.id_user
                  WHERE 1=1";

        if($inicio && $fim){
            $query .= " AND s.created_at BETWEEN :inicio AND :fim";
        }
        if($nome){
            $query .= " AND s.description LIKE :nome";
        }
        if($status === 'pendente'){
            $query .= " AND s.finished_at IS NULL";
        } elseif($status === 'finalizado'){
            $query .= " AND s.finished_at IS NOT NULL";
        }
        if($usuario){
            $query .= " AND s.user_id_user = :usuario";
        }

        $query .= " ORDER BY s.created_at DESC";

        $sql = $this->pdo->prepare($query);

        if($inicio && $fim){
            $sql->bindValue(':inicio', $inicio);
            $sql->bindValue(':fim', $fim . ' 23:59:59');
        }
        if($nome){
            $sql->bindValue(':nome', "%$nome%");
        }
        if($usuario){
            $sql->bindValue(':usuario', $usuario);
        }

        $sql->execute();
        return $sql->fetchAll();
    }

    public function cadastrarServico($descricao, $preco, $id_usuario){
        $sql = $this->pdo->prepare("INSERT INTO service (description, price, user_id_user, created_at) VALUES (:descricao, :preco, :id_usuario, NOW())");
        $sql->bindValue(':descricao', $descricao);
        $preco = $this->converterPreco($preco);
        $sql->bindValue(':preco', $preco);
        $sql->bindValue(':id_usuario', $id_usuario);
        return $sql->execute();
    }

    // Converte preço do formato brasileiro para o formato americano
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

    // Método para calcular a comissão com base no preço do serviço
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

    public function totalServicosPorUsuario($id_usuario){
        $sql = $this->pdo->prepare("SELECT COALESCE(SUM(price), 0) FROM service WHERE user_id_user = :id");
        $sql->bindValue(':id', $id_usuario);
        $sql->execute();
        return $sql->fetchColumn();
    }

    public function resumoServicosPorUsuario($id_usuario){
        $sql = $this->pdo->prepare("SELECT
                                        COALESCE(SUM(price), 0) AS total,
                                        COALESCE(SUM(CASE WHEN finished_at IS NOT NULL THEN price ELSE 0 END), 0) AS finalizados,
                                        COALESCE(SUM(CASE WHEN finished_at IS NULL THEN price ELSE 0 END), 0) AS pendentes,
                                        COALESCE(SUM(CASE WHEN finished_at IS NOT NULL THEN commission_user ELSE 0 END), 0) AS comissao,
                                        COUNT(*) AS quantidade,
                                        SUM(CASE WHEN finished_at IS NOT NULL THEN 1 ELSE 0 END) AS quantidade_finalizados,
                                        SUM(CASE WHEN finished_at IS NULL THEN 1 ELSE 0 END) AS quantidade_pendentes
                                    FROM service
                                    WHERE user_id_user = :id");
        $sql->bindValue(':id', $id_usuario);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function ultimosServicosPendentes($id_usuario, $limite = 5){
        $sql = $this->pdo->prepare("SELECT * FROM service 
                                    WHERE user_id_user = :id AND finished_at IS NULL 
                                    ORDER BY created_at DESC 
                                    LIMIT :limite");
        $sql->bindValue(':id', $id_usuario);
        $sql->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }
}