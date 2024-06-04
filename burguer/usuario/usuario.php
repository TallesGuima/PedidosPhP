<?php
    require "comportamentos.php";
    class usuario extends usuarioBD{
        public function cadastrar(string $nome, string $email, string $senha): bool{
           return $this->cadastrarUsuario($nome, $email, $senha);
        }

        public function autenticar(string $email, string $senha): bool{
            return $this->autenticarUsuario($email, $senha);
        }

        public function getDetalhes(int $idUser){
            return $this->getDetalhesUsuario($idUser);
        }
    }
?>