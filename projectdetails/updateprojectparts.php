<?php 
 
$pdo = new PDO('mysql:host=localhost;dbname=bronco', 'root', '');

$projectid = filter_input(INPUT_POST, "projectid");
$partid = filter_input(INPUT_POST, "partid");
$pquantity = filter_input(INPUT_POST, "pquantity");


//var_dump($pquantity);
//Check Quantity
$sql = 'SELECT quantity FROM parts WHERE partid =:partid';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':partid', $partid);
$stmt->execute();
$count = $stmt->fetch();
$stmt->closeCursor();

$sql2 = 'SELECT quantity FROM project_parts WHERE partid =:partid AND projectid =:projectid';
$stmt2 = $pdo->prepare($sql2);
$stmt2->bindValue(':partid', $partid);
$stmt2->bindValue(':projectid', $projectid);
$stmt2->execute();
$ppq = $stmt2->fetch();
$stmt2->closeCursor();

//var_dump($count['quantity']);


$partq = 0;
if($pquantity > $ppq['quantity']){
        $addq = $pquantity - $ppq['quantity'];
        if($addq > $count['quantity']) {
                $error = "Error: Invalid inventory. Quantity exceeds inventory quantity on hand.";
                 echo $error;
        }
        else {      
                $partq =  $count['quantity'] - $addq;

                //update part inventory
                $sql = 'UPDATE parts
                SET quantity =:partq WHERE partid =:partid';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':partq', $partq);
                $stmt->bindValue(':partid', $partid);
                $stmt->execute();
                $stmt->closeCursor();


                //update project parts
                $sql1 = 'UPDATE project_parts
                        SET quantity =:pquantity WHERE partid =:partid AND projectid =:projectid';
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindValue(':pquantity', $pquantity);
                $stmt1->bindValue(':partid', $partid);
                $stmt1->bindValue(':projectid', $projectid);
                $stmt1->execute();
                $stmt1->closeCursor();  
        }
        
        
}
if($pquantity < $ppq['quantity']){
       
     $dif = $ppq['quantity'] - $pquantity;
     $partq = $dif + $count['quantity'];  

     //update part inventory
     $sql = 'UPDATE parts
     SET quantity =:partq WHERE partid =:partid';
     $stmt = $pdo->prepare($sql);
     $stmt->bindValue(':partq', $partq);
     $stmt->bindValue(':partid', $partid);
     $stmt->execute();
     $stmt->closeCursor();

     //update project parts
     $sql1 = 'UPDATE project_parts
             SET quantity =:pquantity WHERE partid =:partid AND projectid =:projectid';
     $stmt1 = $pdo->prepare($sql1);
     $stmt1->bindValue(':pquantity', $pquantity);
     $stmt1->bindValue(':partid', $partid);
     $stmt1->bindValue(':projectid', $projectid);
     $stmt1->execute();
     $stmt1->closeCursor();    
     
}

if($pquantity < 0){
        $error = "Error: Quantity must be 0 or higher. Please re-enter quantity";
        echo $error;
}

/*Compare inventory quantity to project parts request
if($q < 0){
    $error = "Error: Invalid inventory. Quantity exceeds inventory quantity on hand.";
    echo $error;
}*/

include('projectparts.php');
?>