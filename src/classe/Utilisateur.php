<?php

namespace App\classe;

class Utilisateur
{

    /**
     * @var array
     */
    private $dataUser;

    private function __construct($id_utilisateur){
        $rs = \App\model\Utilisateur::getByID($id_utilisateur);
        if(empty($rs)){
            return null;
        }
        $this->dataUser = $rs[0];
    }

    /** Hash un mot de passe (irreversible) */
    public static function hash($password){
        return password_hash($password, PASSWORD_BCRYPT );
    }

    /** compare un password a un hash */
    public static function verifyHash($password, $hash): bool
    {
        return password_verify($password, $hash);
    }

    /** verifi si le client est connecté */
    public static function isConnected(): bool
    {
        return self::getCurrentUser() instanceof self;
    }

    /** retourne l'instance de user du client s'il est connécté */
    public static function getCurrentUser(): ?self
    {
        if(!isset($_SESSION['utilisateur']) || empty($_SESSION['utilisateur']['id_utilisateur'] ?? null)){
            return null;
        }
        return new self($_SESSION['utilisateur']['id_utilisateur']);
    }

    /** redirige le client vers la page passé en parametre */
    public static function RedirectIfNotConnected($url)
    {
        if(self::isConnected()){
            return ;
        }

        header('Location: '.$url);
        die();
    }

    /** retourne l'attribut du client a partir des données en cache */
    public function getAttribute(string $key)
    {
        return $this->dataUser[$key] ?? null;
    }

}