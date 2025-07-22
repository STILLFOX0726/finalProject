<?php 
session_start();

// Mock products data - this should be in your products.php file
$products = [
    1 => [
        'name' => 'sunscree',
        'price' => 44.00,
        'platform' => 'Light Lotion'
    ],
    2 => [
        'name' => 'Aveeno',
        'price' => 249.99,
        'platform' => 'Body Wash'
    ],
    3 => [
        'name' => 'Anti-acne',
        'price' => 249.99,
        'platform' => 'Facial wash'
    ]
];

// Handle adding to cart from index page
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['id'];
    $quantity = 1; // Default quantity
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    // Redirect to cart page or stay on same page
    // header('Location: cart.php');
    // exit;
}
// Handle avatar upload
if (isset($_POST['upload_avatar'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}
    // Check file size (max 2MB)
    if ($_FILES["avatar"]["size"] > 2000000) {
        $uploadOk = 0;
    }
       // Allow certain file formats
       if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
       && $imageFileType != "gif" ) {
           $uploadOk = 0;
       }
          // If everything is ok, try to upload file
    if ($uploadOk == 1) {
        // Delete old avatar if exists
        if (isset($_SESSION['avatar_path']) && file_exists($_SESSION['avatar_path'])) {
            unlink($_SESSION['avatar_path']);
        }
        
        // Generate unique filename
        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $_SESSION['avatar_path'] = $target_file;
        }
    }

    // Handle avatar deletion
if (isset($_POST['delete_avatar'])) {
    if (isset($_SESSION['avatar_path']) && file_exists($_SESSION['avatar_path'])) {
        unlink($_SESSION['avatar_path']);
        unset($_SESSION['avatar_path']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Your external stylesheet -->
    <style>
        /* Inline styles (keep your external CSS clean ideally) */
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff;
        }

        .dashboard {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #78f5c5, #0056b3);
            color: white;
            padding: 30px;
        }

        .sidebar .user {
            margin-bottom: 30px;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            padding: 10px 0;
            cursor: pointer;
            opacity: 0.8;
        }

        .sidebar .menu li:hover {
            font-weight: bold;
            opacity: 1;
        }

        .main {
            flex-grow: 1;
            padding: 30px;
            background: #e6eaed;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .top-bar input {
            width: 60%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .top-bar button {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .product-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-card button {
            background: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .cart-summary {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .cart-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .go-to-cart {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #5f4dee;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .avatar-upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .avatar-actions {
            display: flex;
            gap: 10px;
        }
        
        .avatar-actions button {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .delete-avatar {
            background-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="user">
                <p>Hello, John Paul</p>
            </div>
            <ul class="menu">
                <li>Notifications</li>
                <li>Updates</li>
                <li>Orders</li>
                <li>Account Settings</li>
                <li><a href="dash.php" style="color: white; text-decoration: none;">Logout</a></li>
            </ul>
            </ul>
            
        </aside>

        <!-- Main Content -->
        <main class="main">
            <div class="top-bar">
                <input type="text" placeholder="Search...">
                <button>+ Add New List</button>
            </div>

            <div class="products">
                <?php foreach ($products as $id => $product): ?>
                    <div class="product-card">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p>₱<?= number_format($product['price'], 2) ?></p>
                        <p><?= htmlspecialchars($product['platform']) ?></p>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" name="add_to_cart">Buy Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h2>🛒 Your Cart</h2>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $id => $qty):
                            if (!isset($products[$id])) continue;
                            $item = $products[$id];
                            $subtotal = $item['price'] * $qty;
                            $total += $subtotal;
                    ?>
                        <div class="cart-item">
                            <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                            Quantity: <?= $qty ?><br>
                            Subtotal: ₱<?= number_format($subtotal, 2) ?><br>
                            <a href="cart.php?action=remove&id=<?= $id ?>" style="color:red;">Remove</a>
                        </div>
                    <?php endforeach; ?>
                    <p><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
                    <a href="cart.php" class="go-to-cart">Go to Cart</a>
                <?php else: ?>
                    <p>Your cart is empty 🛒</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>