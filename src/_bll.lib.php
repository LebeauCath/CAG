
<?php

// ----------------------------------------------------------------------------
/* Projet CAG
 * 
 * Business Logic Layer
 * Classes de gestion du métier (CRUD)
 * 
 * Utilise les services de la classe PdoDao
 * Utilise les services de la bibliothèque _reference.lib.php
 *
 * @package 	default
 * @author
 * @version    	1.0
 * @link       	http://www.php.net/manual/fr/book.pdo.php
 */
// ----------------------------------------------------------------------------

require_once '_data.lib.php';
require_once '_reference.lib.php';
use \PdoDao;

// classe utilisée pour gérer les négociants
class Negociants {
    
    /**
     * Méthode qui charge une liste des négociants   
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */
    static public function chargerNegociants($mode) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "SELECT nonegociant as ID, "
                ."nomnegociant as Nom "
                ."FROM negociant "
                ."ORDER BY nomnegociant";        
        try {
            $res = $cnx->getRows($strSQL, array(), $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui crée un objet de la classe Negociant à partir de son ID      
     * @param $id : l'identifiant du négociant
     * @return un objet de la classe Negociant
    */
    static public function chargerNegociantParID($id) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "SELECT nonegociant as ID, "
                ."nomnegociant as Nom, "
                ."adrnegociant AS Adresse "
                ."FROM negociant "
                ."WHERE nonegociant = ?";
        try {
            $res = $cnx->getRows($strSQL, array($id), 1);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        if ($res != -1) {
            // le négociant existe
            $nom = $res[0]->Nom;
            $adresse = $res[0]->Adresse;
            return new Negociant($id,$nom,$adresse);
        }
        else {
            return NULL;
        }
    }

    /**
     * Méthode qui ajoute un négociant dans la base      
     * @param   $params : tableau contenant les valeurs (nom et adresse)
     * @return  un objet de la classe Negociant
    */
    static public function ajouterNegociant($params) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "INSERT INTO negociant (nomnegociant, adrnegociant) "
                ."VALUES (?,?)";
        try {
            $cnx->execSQL($strSQL, $params);
            $strSQL = "SELECT MAX(nonegociant) FROM negociant";
            // récupération du numéro
            $id = $cnx->getValue($strSQL, array());
            // instanciation de l'objet
            $nego = self::chargerNegociantParID($id);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $nego;
    }        
    
    /**
     * Méthode qui modifie un négociant dans la base      
     * @param   $negociant : un objet de la classe Negociant
     * @return  un entier qui vaut 1 si la maj a été effectuée
    */
    static public function modifierNegociant($negociant) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "UPDATE negociant "
                ."SET nomnegociant = ?, "
                ."adrnegociant = ? "
                ."WHERE nonegociant = ?";
        try {
            $values = array($negociant->getNom(),
                    $negociant->getAdresse(),
                    $negociant->getID()
                );
            $res = $cnx->execSQL($strSQL, $values);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }        
    
    /**
     * supprime un négociant de la base
     * @param   $nego : un objet négociant
     * @return  un entier qui contient 1 si la mise à jour a été effectuées
    */
    static public function supprimerNegociant($nego) {
        $cnx = new PdoDao();        
        $strSQL = "DELETE FROM negociant "
                . "WHERE nonegociant = ?";
        $values = array($nego->getID());
        try {
            $cnx->execSQL($strSQL,$values);
            // suppression de l'objet en mémoire
            $nego = NULL;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $nego;
    } 
}

// classe utilisée pour gérer les contrats
class Contrats {
    
    /**
     * Méthode qui charge une liste des contrats   
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */
    static public function chargerContrats($mode) {
        $cnx = new PdoDao();
        $strSQL =  "SELECT nocontrat AS ID, "
            ."datecontrat as Date, "
            ."nomnegociant AS Negociant, "
            ."variete AS Cereale, "
            ."qtecde AS Commande, "
            ."ifnull(qtelivree,0) AS Livre, "
            ."ifnull(qtealivrer,qtecde) AS Reste, "
            ."prix AS Prix, "
            ."montant AS Montant, "
            ."etatcontrat AS Etat "
            ."FROM v_contrats "
            ."ORDER BY nocontrat DESC";         
        try {
            $res = $cnx->getRows($strSQL, array(), $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui charge une liste des contrats
     * dont l'état est C ou E
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */
    static public function chargerContratsEouC($mode) {
        $cnx = new PdoDao();
        $strSQL =  "SELECT nocontrat AS ID, "
            ."datecontrat as Date, "
            ."nomnegociant AS Negociant, "
            ."variete AS Cereale, "
            ."qtecde AS Commande, "
            ."ifnull(qtelivree,0) AS Livre, "
            ."ifnull(qtealivrer,qtecde) AS Reste, "
            ."etatcontrat AS Etat "
            ."FROM v_contrats "
            ."where (etatcontrat LIKE 'C' OR etatcontrat LIKE 'E')"  
            ."ORDER BY nocontrat DESC";         
        try {
            $res = $cnx->getRows($strSQL, array(), $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui crée un objet de la classe Contrat à partir de son ID      
     * @param $id : l'identifiant du contrat
     * @return un objet de la classe Contrat
    */
    static public function chargerContratParID($id) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "SELECT nocontrat AS ID, "
                ."codecereale AS Cereale, "
                ."nonegociant AS Negociant, "
                ."date_format(datecontrat,'%d-%m-%Y') AS Date, "
                ."qtecde AS QteCde, "
                ."prixcontrat AS Prix, "
                ."etatcontrat AS Etat "
                ."FROM contrat "
                ."WHERE nocontrat = ?";
        try {
            $res = $cnx->getRows($strSQL, array($id), 1);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        if ($res != -1) {
            // le contrat existe
            $cereale = $res[0]->Cereale;
            $negociant = $res[0]->Negociant;
            $date = $res[0]->Date;
            $qtecde = $res[0]->QteCde;
            $prix = $res[0]->Prix;
            $etat = $res[0]->Etat;
            return new Contrat($id,$cereale,$negociant,$date,$qtecde,$prix,$etat);
        }
        else {
            return NULL;
        }
    }
    
     /**
     * Méthode qui 
     * @param $id : l'identifiant du contrat
     * @return un entier correspondant au reste à livrer pour le contrat
    */
    static public function resteAlivrer($id) {
        $cnx = new PdoDao();
        $strSQL =  "SELECT ifnull(qtealivrer,qtecde) AS Reste "
            ."FROM v_contrats "
            ."WHERE nocontrat = ?";        
        try {
            $res = $cnx->getRows($strSQL, array($id), 1);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        if ($res != -1) {
            $reste = $res[0]->Reste;
        return $reste;
        }
    }

    /**
     * Méthode qui ajoute un contrat dans la base      
     * @param   $params : tableau contenant les valeurs (nom et adresse)
     * @return  un objet de la classe Negociant
    */
    static public function ajouterContrat($params) {
        $cnx = new PdoDao();
        $strSQL = "INSERT INTO contrat ( "
                ."codecereale,nonegociant,datecontrat,qtecde,prixcontrat,etatcontrat) "
                ."VALUES (?,?,?,?,?,?)";
        try {
            $cnx->execSQL($strSQL, $params);
            $strSQL = "SELECT MAX(nocontrat) FROM contrat";
            // récupération du numéro
            $id = $cnx->getValue($strSQL, array());
            // instanciation de l'objet
            $contrat = self::chargerContratParID($id);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $contrat;
    }        
    
    /**
     * Méthode qui modifie un contrat dans la base      
     * @param   $contrat : un objet de la classe Contrat
     * @return  un entier qui vaut 1 si la maj a été effectuée
    */
    static public function modifierContrat($contrat) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "UPDATE contrat SET
            codecereale = ?,
            datecontrat = ?,
            qtecde = ?, 
            prixcontrat = ?
            WHERE nocontrat = ?";
        try {
            $values = array($contrat->getCereale()->getID(),
                    $contrat->getDate(),
                    $contrat->getQteCde(),
                    $contrat->getPrix(),
                    $contrat->getID()
                );
            $res = $cnx->execSQL($strSQL, $values);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui modifie un contrat dans la base      
     * @param   $contrat : un objet de la classe Contrat
     * @param   $etat : le nouvel état du contrat ('C' ou 'S')
     * @return  un entier qui vaut 1 si la maj a été effectuée
    */
    static public function modifierEtatContrat($idContrat,$etat) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "UPDATE contrat SET            
            etatcontrat = ?           
            WHERE nocontrat = ?";
        try {
            $values = array(
                $etat,
                $idContrat
                );
            $res = $cnx->execSQL($strSQL, $values);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * supprime un contrat de la base
     * @param   $contrat : un objet de la classe Contrat
     * @return  un entier qui contient 1 si la mise à jour a été effectuées
    */
    static public function supprimerContrat($contrat) {
        $cnx = new PdoDao();        
        $strSQL = "DELETE FROM contrat "
                . "WHERE nocontrat = ?";
        $values = array($contrat->getID());
        try {
            $cnx->execSQL($strSQL,$values);
            // suppression de l'objet en mémoire
            $contrat = NULL;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        return $contrat;
    }

    /**
     * Méthode qui charge une liste de silos adaptés 
     * à la céréale du contrat
     * @param   $idContrat : l'identifiant du contrat
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */


    static public function chargeLesSilos($idContrat, $mode) {
        $cnx = new PdoDao();  
        $strSQL = "SELECT CODESILO as Silo FROM contrat C "
                  ."INNER JOIN silo S "
                  ."ON C.codecereale = S.codecereale "
                  ."WHERE nocontrat = ?";
        $values = array($idContrat); 
        try {
            $res = $cnx->getRows($strSQL, $values, $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
}

// classe utilisée pour gérer les céréales
class Cereales {
    
    /**
     * Méthode qui charge une liste des céréales
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */
    static public function chargerCereales($mode) {
        $cnx = new PdoDao();
        $strSQL =  "SELECT codecereale AS ID, "
            ."variete as Nom "
            ."FROM cereale";
        try {
            $res = $cnx->getRows($strSQL, array(), $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui crée un objet de la classe Céréale à partir de son ID      
     * @param $id : l'identifiant de la céréale
     * @return un objet de la classe Cereale
    */
    static public function chargerCerealeParID($id) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "SELECT codecereale AS ID, "
                ."variete AS Nom, "
                ."prixachatref AS PrixAchatRef, "
                ."prixvente AS PrixVente "
                ."FROM cereale "
                ."WHERE codecereale = ?";
        try {
            $res = $cnx->getRows($strSQL, array($id), 1);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        if ($res != -1) {
            // la céréale existe
            $nom = $res[0]->Nom;
            $prixachatref = $res[0]->PrixAchatRef;
            $prixvente = $res[0]->PrixVente;
            return new Cereale($id,$nom,$prixachatref,$prixvente);
        }
        else {
            return NULL;
        }
    }    
}

class livraisons
{
    
     /**
     * Méthode qui charge une liste de livraisons   
     * @param $mode : le type de résultat
     *                  0 == tableau associatif
     *                  1 == tableau "objet"
     * @return un tableau de type "mode"
    */
    static public function chargerLivraisons($mode) {
        $cnx = new PdoDao();
        $strSQL =  "SELECT nolivraison AS ID, "
            ."nocontrat AS IDContrat, "    
            ."dateliv as Date, "
            ."qteliv AS Livre, "
            ."codeSilo AS Silo "
            ."FROM livraison "
            ."ORDER BY nolivraison DESC";         
        try {
            $res = $cnx->getRows($strSQL, array(), $mode);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $res;
    }
    
    /**
     * Méthode qui crée un objet de la classe Livraison à partir de son ID      
     * @param $id : l'identifiant de la livraison
     * @return un objet de la classe Livraison
    */
    static public function chargerLivraisonParID($id) {
        $cnx = new PdoDao();
        // créer la requête
        $strSQL = "SELECT nolivraison AS ID, "
                ."nocontrat AS IDContrat, "
                ."date_format(dateliv,'%d-%m-%Y') AS Date, "
                ."qteliv AS Livre, "
                ."codeSilo AS Silo "
                ."FROM livraison "
                ."WHERE nolivraison = ?";
        try {
            $res = $cnx->getRows($strSQL, array($id), 1);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        if ($res != -1) {
            // la livraison existe
            $idContrat = $res[0]->IDContrat;
            $date = $res[0]->Date;
            $livre = $res[0]->Livre;
            $silo = $res[0]->Silo;
            return new Livraison($id,$idContrat,$date,$livre,$silo);
        }
        else {
            return NULL;
        }
    }

    
     /**
     * Méthode qui ajoute une livraison dans la base      
     * @param   $params : tableau contenant les valeurs
     * @return  un objet de la classe livraison
    */
    static public function ajouterLivraison($params) {
        $cnx = new PdoDao();
        $strSQL = "INSERT INTO livraison ( "
                ."nocontrat,dateliv,qteliv,codesilo) "
                ."VALUES (?,?,?,?)";
        try {
            $cnx->execSQL($strSQL, $params);
            $strSQL = "SELECT MAX(nolivraison) FROM livraison";
            // récupération du numéro
            $id = $cnx->getValue($strSQL, array());
            // instanciation de l'objet
            $livraison = self::chargerLivraisonParID($id);
        } catch (Exception $ex) {
            die ($ex->getMessage());
        }
        return $livraison;
    }        
     
}


