<?php

class Instructor
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function insertData($table, $data, $header)
    {


        $column = implode(',', array_keys($data));
        $placeholder = ':' . implode(',:', array_keys($data));

        $insertData = $this->pdo->prepare("INSERT INTO $table ($column) VALUES ($placeholder)");
        $insertData->execute($data);
        header("location: ../index.php?page=$header");
    }


    public function deleteData($table, $columnToUpdate, $newValue, $whereColumn, $whereValue, $header)
    {
        $deleteData = $this->pdo->prepare("UPDATE $table SET $columnToUpdate = :newValue  WHERE $whereColumn= :whereValue ");
        $deleteData->bindParam(':newValue', $newValue);
        $deleteData->bindParam(':whereValue', $whereValue);
        $deleteData->execute();
        header("location: ../index.php?page=$header");
        exit();
    }


    public function editInstructor($data)
    {

        $editData = $this->pdo->prepare("UPDATE users SET full_name = :full_name, street = :street, house_number = :house_number, postal_code = :postal_code, city = :city, phone = :phone, email = :email WHERE id = :userid");
        $editData->execute(array(
            'full_name' => $data['full_name'],
            'street' => $data['street'],
            'house_number' => $data['house_number'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'userid' => $data['userid']
        ));
        header("Location: ../index.php?page=manageInstructor");
        exit();
    }


}