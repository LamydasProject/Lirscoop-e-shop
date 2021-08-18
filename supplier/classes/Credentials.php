<?php 
session_start();
/*
 * Credentials Class - Supplier Login
 */
class Credentials
{
	
	private $con;

	function __construct()
	{
		include_once("Database.php");
		$db = new Database();
		$this->con = $db->connect();
	}

	public function loginSupplier($email, $password){
		$q = $this->con->query("SELECT * FROM supplier WHERE supplier_email = '$email' LIMIT 1");
		if ($q->num_rows > 0) {
			$row = $q->fetch_assoc();
			if (password_verify($password, $row['supplier_password'])) {
				$_SESSION['supplier_name'] = $row['supplier_name'];
				$_SESSION['supplier_id'] = $row['supplier_id'];
				return ['status'=> 202, 'message'=> 'Login Successful'];
			}else{
				return ['status'=> 303, 'message'=> 'Login Fail'];
			}
		}else{
			return ['status'=> 303, 'message'=> 'Account not created yet with this email'];
		}
	}

}


if (isset($_POST['supplier_login'])) {
	extract($_POST);
	if (!empty($email) && !empty($password)) {
		$c = new Credentials();
		$result = $c->loginSupplier($email, $password);
		echo json_encode($result);
		exit();
	}else{
		echo json_encode(['status'=> 303, 'message'=> 'Empty fields']);
		exit();
	}
}


?>