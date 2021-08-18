<?php 
session_start();
class Products
{
	private $con;

	function __construct(){
		include_once("Database.php");
		$db = new Database();
		$this->con = $db->connect();
	}

	public function getProducts($supplier_id){
		$q = $this->con->query("SELECT p.product_id, p.product_name, p.product_description, p.product_image, p.product_price, c.category_name, c.category_id FROM product p, category c WHERE c.category_id = p.category_id AND p.supplier_id = '$supplier_id'");
		
		$products = [];
		if ($q->num_rows > 0) {
			while($row = $q->fetch_assoc()){
				$products[] = $row;
			}
			//return ['status'=> 202, 'message'=> $ar];
			$_DATA['products'] = $products;
		}

		$categories = [];
		$q = $this->con->query("SELECT * FROM category");
		if ($q->num_rows > 0) {
			while($row = $q->fetch_assoc()){
				$categories[] = $row;
			}
			//return ['status'=> 202, 'message'=> $ar];
			$_DATA['categories'] = $categories;
		}

		return ['status'=> 202, 'message'=> $_DATA];
	}
	public function addProduct($product_name,
								$category_id,
								$product_description,
								$product_price,
                                $supplier_id,
								$file){


		$fileName = $file['name'];
		$fileNameAr= explode(".", $fileName);
		$extension = end($fileNameAr);
		$ext = strtolower($extension);

		if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
			
			//print_r($file['size']);

			if ($file['size'] > (1024 * 2)) {
				
				$uniqueImageName = time()."_".$file['name'];
				$imagelocation = $_SERVER['DOCUMENT_ROOT']."/lirs/supplier/images/".$uniqueImageName;
				if (move_uploaded_file($file['tmp_name'], $imagelocation)) {
					$q = $this->con->query("INSERT INTO `product`(`category_id`, `product_name`, `product_description`, `product_price`, `product_image`, `supplier_id`) VALUES ('$category_id', '$product_name', '$product_description', '$product_price', '$uniqueImageName', '$supplier_id')");
					if ($q) {
						return ['status'=> 202, 'message'=> 'Product Added Successfully..!'];
					}else{
						return ['status'=> 303, 'message'=> 'Failed to run query'];
					}

				}else{
					return ['status'=> 303, 'message'=> 'Failed to upload product'];
				}

			}else{
				return ['status'=> 303, 'message'=> 'Large Image ,Max Size allowed 2MB'];
			}
			return ['status'=> 303, 'message'=> 'Invalid Image Format [Valid Formats : jpg, jpeg, png]'];
		}

	}
	public function editProductWithImage($pid,
										$product_name,
										$category_id,
										$product_price,
										$product_description,
										$supplier_id,
										$file){


		$fileName = $file['name'];
		$fileNameAr= explode(".", $fileName);
		$extension = end($fileNameAr);
		$ext = strtolower($extension);

		if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
			
			//print_r($file['size']);

			if ($file['size'] > (1024 * 2)) {
				
				$uniqueImageName = time()."_".$file['name'];
				if (move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/lirs/supplier/images/".$uniqueImageName)) {
					
					$q = $this->con->query("UPDATE `product` SET 
										`category_id` = '$category_id', 
										`product_name` = '$product_name', 
										`product_description` = '$product_description', 
										`product_image` = '$uniqueImageName',
										`product_price` = '$product_price'
										WHERE `product_id` = '$pid' AND `supplier_id` = '$supplier_id'");
										
					if ($q) {
						return ['status'=> 202, 'message'=> 'Movie Modified Successfully..!'];
					}else{
						return ['status'=> 303, 'message'=> 'Failed to run query'];
					}

				}else{
					return ['status'=> 303, 'message'=> 'Failed to upload image'];
				}

			}else{
				return ['status'=> 303, 'message'=> 'Large Image ,Max Size allowed 2MB'];
			}

		}else{
			return ['status'=> 303, 'message'=> 'Invalid Image Format [Valid Formats : jpg, jpeg, png]'];
		}

	}
	public function editProductWithoutImage($pid,
										$product_name,
										$category_id,
										$product_price,
										$product_description,
										$supplier_id){

		if ($pid != null) {
			$q = $this->con->query("UPDATE `product` SET 
										`category_id` = '$category_id', 
										`product_name` = '$product_name', 
										`product_description` = '$product_description', 
										`product_price` = '$product_price'
										WHERE `product_id` = '$pid' AND `supplier_id` = '$supplier_id'");

			if ($q) {
				return ['status'=> 202, 'message'=> 'Movie updated Successfully'];
			}else{
				return ['status'=> 303, 'message'=> 'Failed to run query'];
			}
			
		}else{
			return ['status'=> 303, 'message'=> 'Invalid movie id'];
		}
		
	}
	public function deleteProduct($pid = null){
		if ($pid != null) {
			$q = $this->con->query("DELETE FROM product WHERE product_id = '$pid'");
			if ($q) {
				return ['status'=> 202, 'message'=> 'Product removed from list'];
			}else{
				return ['status'=> 202, 'message'=> 'Failed to run query'];
			}
			
		}else{
			return ['status'=> 303, 'message'=>'Invalid product id'];
		}

	}
}

if (isset($_POST['GET_PRODUCT'])) {
	if (isset($_SESSION['supplier_id'])) {
		$p = new Products();
        $supplier_id = $_SESSION['supplier_id'];
		echo json_encode($p->getProducts($supplier_id));
		exit();
	}
}
if (isset($_POST['add_product'])) {
    if (isset($_SESSION['supplier_id'])) {
        extract($_POST);
        if (!empty($product_name) 
        && !empty($category_id)
        && !empty($product_description)
        && !empty($product_price)
        && !empty($_FILES['product_image']['name'])) {
            
            $supplier_id = $_SESSION['supplier_id'];
            $p = new Products();
            $result = $p->addProduct($product_name,
                                    $category_id,
                                    $product_description,
                                    $product_price,
                                    $supplier_id,
                                    $_FILES['product_image']);
            header("Content-type: application/json");
            echo json_encode($result);
            http_response_code($result['status']);
            exit();
        }else{
            echo json_encode(['status'=> 303, 'message'=> 'Empty fields']);
		    exit();
        }
	}else{
		echo json_encode(['status'=> 303, 'message'=> 'Session Empty']);
		exit();
	}



	
}
if (isset($_POST['edit_product'])) {
	if (isset($_SESSION['supplier_id'])) {
		extract($_POST);
		$supplier_id = $_SESSION['supplier_id'];
		if (!empty($pid)
		&& !empty($e_product_name)  
		&& !empty($e_category_id)
		&& !empty($e_product_price)
		&& !empty($e_product_description)) {
		
		$p = new Products();
		if (isset($_FILES['e_product_image']['name']) 
			&& !empty($_FILES['e_product_image']['name'])) {
			$result = $p->editProductWithImage($pid,
								$e_product_name,
								$e_category_id,
								$e_product_price,
								$e_product_description,
								$supplier_id,
								$_FILES['e_product_image']);
		}else{
			$result = $p->editProductWithoutImage($pid,
								$e_product_name,
								$e_category_id,
								$e_product_price,
								$e_product_description,
								$supplier_id);
		}

		echo json_encode($result);
		exit();


		}else{
			echo json_encode(['status'=> 303, 'message'=> 'Empty fields']);
			exit();
		}
	}else{
		echo json_encode(['status'=> 303, 'message'=> 'Invalid Session']);
		exit();
	}
	
}
if (isset($_POST['DELETE_PRODUCT'])) {
	$p = new Products();
	if (isset($_SESSION['supplier_id'])) {
		if(!empty($_POST['pid'])){
			$pid = $_POST['pid'];
			echo json_encode($p->deleteProduct($pid));
			exit();
		}else{
			echo json_encode(['status'=> 303, 'message'=> 'Invalid product id']);
			exit();
		}
	}else{
		echo json_encode(['status'=> 303, 'message'=> 'Invalid Session']);
	}


}

?>