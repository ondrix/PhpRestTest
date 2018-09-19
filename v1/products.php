<?php
// Connect to database
include("../connection.php");
$db = new dbObj();
$connection =  $db->getConnstring();

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method)
	{
		case 'GET':
			// Retrive Products
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				get_product($id);
			}
			else
			{
				get_products();
			}
            break;
        case 'POST':
            // Insert Product
            insert_product();
            break;
        case 'PUT':
            // Update Product
            $id=intval($_GET["id"]);
            update_product($id);
            break;        
        case 'DELETE':
            // Delete Product
            $id=intval($_GET["id"]);
            delete_product($id);
            break;                
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
    }
    
    function get_products()
	{
		global $connection;
		$query="SELECT * FROM products";
		$response=array();
		$result=mysqli_query($connection, $query);
		while($row=mysqli_fetch_array($result))
		{
			$response[]=$row;
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function get_product($id=0)
    {
        global $connection;
        $query="SELECT * FROM products WHERE id=".$id;
        $response=array();
        $result=mysqli_query($connection, $query);
        while($row=mysqli_fetch_array($result))
        {
            $response[]=$row;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function insert_product()
	{
		global $connection;

		$data = json_decode(file_get_contents('php://input'), true);
		$name=$data["name"];
		$price=$data["price"];
		echo $query="INSERT INTO products SET name='".$name."', price='".$price."'";
		if(mysqli_query($connection, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Product Added Successfully.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Product Addition Failed.'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function update_product($id)
	{
		global $connection;
		$data = json_decode(file_get_contents("php://input"),true);
		$name=$data["name"];
		$price=$data["price"];
		$query="UPDATE products SET name='".$name."', price='".$price."' WHERE id=".$id;
		if(mysqli_query($connection, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Employee Updated Successfully.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Employee Updation Failed.'
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
    }
    
    function delete_product($id)
    {
        global $connection;
        $query="DELETE FROM products WHERE id=".$id;
        if(mysqli_query($connection, $query))
        {
            $response=array(
                'status' => 1,
                'status_message' =>'Employee Deleted Successfully.'
            );
        }
        else
        {
            $response=array(
                'status' => 0,
                'status_message' =>'Employee Deletion Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>