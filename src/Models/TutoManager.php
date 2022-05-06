<?php

namespace tutoAPI\Models;

use tutoAPI\Services\Manager;

class TutoManager extends Manager
{

    public function find($id)
    {

        // Connexion à la BDD
        $dbh = static::connectDb();

        // Requête
        $sth = $dbh->prepare('SELECT * FROM tutos WHERE id = :id');
        $sth->bindParam(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        // Instanciation d'un tuto
        $tuto = new Tuto();
        $tuto->setId($result["id"]);
        $tuto->setTitle($result["title"]);
        $tuto->setDescription($result["description"]);
        $tuto->setCreatedAt($result["createdAt"]);

        // Retour
        return $tuto;
    }

    public function findAll($num_page=0)
    {

        // Connexion à la BDD
        $dbh = static::connectDb();
        $num_offset = (2 * $num_page)-2;
        // Requête
        //Pagination de 2 en 2
        if($num_page==0){
            $sth = $dbh->prepare('SELECT * FROM tutos');
        }else{
            $sth = $dbh->prepare('SELECT * FROM tutos LIMIT 2 OFFSET :num_offset');
            $sth->bindParam(':num_offset', $num_offset, \PDO::PARAM_INT);
        }
        $sth->execute();

        $tutos = [];

        while($row = $sth->fetch(\PDO::FETCH_ASSOC)){

            $tuto = new Tuto();
            $tuto->setId($row['id']);
            $tuto->setTitle($row['title']);
            $tuto->setDescription($row['description']);
            $tuto->setCreatedAt($row["createdAt"]);
            $tutos[] = $tuto;

        }

        return $tutos;

    }

    public function add(Tuto $tuto){

        // Connexion à la BDD
        $dbh = static::connectDb();

        // Requête
        $sth = $dbh->prepare('INSERT INTO tutos (title, description, createdAt) VALUES (:title, :description, :createdAt)');
        $title = $tuto->getTitle();
        $sth->bindParam(':title', $title);
        $description = $tuto->getDescription();
        $sth->bindParam(':description', $description);
        $createdAt = $tuto->getCreatedAt();
        $sth->bindParam(':createdAt', $createdAt);
        $sth->execute();

        // Retour
        $id = $dbh->lastInsertId();
        $tuto->setId($id);
        return $tuto;

    }

    public function update(Tuto $tuto){

      // Connexion à la BDD
      $dbh = static::connectDb();
      $id = $tuto->getId();
      $title = $tuto->getTitle();
      $description = $tuto->getDescription();
      $createdAt = $tuto->getCreatedAt();
      // Requête
      $sth = $dbh->prepare('UPDATE tutos SET title = :title, description = :description, createdAt = :createdAt WHERE id = :id');
      $sth->bindParam(':id', $id, \PDO::PARAM_INT);
      $sth->bindParam(':title', $title);
      $sth->bindParam(':description', $description);
      $sth->bindParam(':createdAt', $createdAt);
      $sth->execute();

      // Retour
      return $tuto;

    }

    public function delete($id)
    {

        // Connexion à la BDD
        $dbh = static::connectDb();

        // Requête
        $sth = $dbh->prepare('delete FROM tutos WHERE id = :id');
        $sth->bindParam(':id', $id, \PDO::PARAM_INT);
        $sth->execute();


    }







}
