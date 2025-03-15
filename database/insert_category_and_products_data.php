<?php
    include "..\config\database_connect.php";

    function insert_data_to_tables($connect, $table_name, $table_sql_statements)
    {
        if ($connect->query($table_sql_statements) === TRUE) {
            echo "Table $table_name created successfully";
        } else {
            echo "Error creating table $table_name: " . $connect->error;
        }
    }

    $insert_sql_statements = [
        "categories" => "INSERT INTO categories (name) VALUES
    ('T-Shirts'),
    ('Jeans'),
    ('Jackets'),
    ('Shoes'),
    ('Dresses'),
    ('Sweaters'),
    ('Hats'),
    ('Accessories'),
    ('Sportswear'),
    ('Formal Wear')",

        "products" => "INSERT INTO products (name, description, price, in_stock, image_url, category_id, rating) VALUES
    -- T-Shirts (Category ID 1)
    ('Classic White T-Shirt', 'A soft cotton white t-shirt, perfect for everyday wear.', 15.99, 50, 'images/tshirt1.jpg', 1, 9),
    ('Black Graphic Tee', 'Trendy black t-shirt with a stylish printed design.', 18.50, 40, 'images/tshirt2.jpg', 1, 8),
    ('V-Neck Blue Tee', 'A slim-fit v-neck t-shirt in navy blue.', 17.99, 30, 'images/tshirt3.jpg', 1, 7),
    ('Oversized Streetwear Tee', 'A fashionable oversized t-shirt for urban style lovers.', 20.00, 25, 'images/tshirt4.jpg', 1, 9),
    ('Sports Performance Tee', 'Breathable t-shirt for workouts and sports activities.', 22.50, 35, 'images/tshirt5.jpg', 1, 8),
    
    -- Jeans (Category ID 2)
    ('Slim Fit Blue Jeans', 'Stylish slim fit jeans with a classic blue wash.', 45.00, 20, 'images/jeans1.jpg', 2, 9),
    ('Distressed Ripped Jeans', 'Trendy ripped jeans for a rugged casual look.', 50.00, 15, 'images/jeans2.jpg', 2, 8),
    ('Straight Cut Black Jeans', 'Timeless black straight-cut jeans.', 42.99, 18, 'images/jeans3.jpg', 2, 7),
    ('Baggy Denim Jeans', 'Loose fit baggy jeans for a retro style.', 55.00, 12, 'images/jeans4.jpg', 2, 9),
    ('High Waist Skinny Jeans', 'Elegant high waist jeans for a flattering fit.', 48.99, 22, 'images/jeans5.jpg', 2, 8),
    
    -- Jackets (Category ID 3)
    ('Classic Leather Jacket', 'Premium leather jacket with a biker-style finish.', 120.00, 10, 'images/jacket1.jpg', 3, 10),
    ('Denim Jacket', 'Casual denim jacket for layering over any outfit.', 60.00, 15, 'images/jacket2.jpg', 3, 8),
    ('Puffer Winter Jacket', 'Warm and comfortable jacket for cold weather.', 90.00, 12, 'images/jacket3.jpg', 3, 9),
    ('Bomber Jacket', 'Trendy bomber jacket with a sleek design.', 75.00, 18, 'images/jacket4.jpg', 3, 9),
    ('Hooded Windbreaker', 'Lightweight windbreaker jacket with a hood.', 55.00, 20, 'images/jacket5.jpg', 3, 7),
    
    -- Shoes (Category ID 4)
    ('Running Sneakers', 'High-performance running shoes for comfort and support.', 85.00, 25, 'images/shoes1.jpg', 4, 9),
    ('Classic White Sneakers', 'Minimalist white sneakers for everyday wear.', 70.00, 30, 'images/shoes2.jpg', 4, 9),
    ('Leather Formal Shoes', 'Elegant leather shoes for formal occasions.', 100.00, 10, 'images/shoes3.jpg', 4, 8),
    ('Hiking Boots', 'Durable boots for outdoor adventures.', 110.00, 15, 'images/shoes4.jpg', 4, 9),
    ('Slip-On Loafers', 'Casual slip-on loafers for comfort and ease.', 65.00, 20, 'images/shoes5.jpg', 4, 7),
    
    -- Dresses (Category ID 5)
    ('Floral Summer Dress', 'Light and breezy floral dress for summer days.', 55.00, 12, 'images/dress1.jpg', 5, 9),
    ('Elegant Evening Gown', 'A stunning long evening gown for special occasions.', 120.00, 8, 'images/dress2.jpg', 5, 10),
    ('Casual Wrap Dress', 'Comfortable and stylish wrap dress.', 50.00, 14, 'images/dress3.jpg', 5, 8),
    ('Maxi Dress', 'Long maxi dress with a flattering fit.', 65.00, 10, 'images/dress4.jpg', 5, 9),
    ('Mini Bodycon Dress', 'Trendy body-hugging mini dress.', 45.00, 18, 'images/dress5.jpg', 5, 7),
    
    -- Sweaters (Category ID 6)
    ('Chunky Knit Sweater', 'Warm and cozy knit sweater.', 50.00, 20, 'images/sweater1.jpg', 6, 9),
    ('Turtleneck Sweater', 'Classic turtleneck sweater for winter.', 55.00, 15, 'images/sweater2.jpg', 6, 8),
    ('Cardigan', 'Soft and lightweight cardigan for layering.', 45.00, 18, 'images/sweater3.jpg', 6, 7),
    ('Oversized Sweater', 'Trendy oversized sweater for a relaxed fit.', 60.00, 10, 'images/sweater4.jpg', 6, 9),
    ('Cashmere Sweater', 'Premium cashmere sweater with a soft touch.', 75.00, 8, 'images/sweater5.jpg', 6, 10),
    
    -- Hats (Category ID 7)
    ('Baseball Cap', 'Classic baseball cap for everyday wear.', 20.00, 25, 'images/hat1.jpg', 7, 8),
    ('Beanie', 'Warm knitted beanie for winter.', 18.00, 20, 'images/hat2.jpg', 7, 7),
    ('Wide Brim Hat', 'Stylish wide-brim hat for sun protection.', 30.00, 12, 'images/hat3.jpg', 7, 9),
    ('Bucket Hat', 'Trendy bucket hat for casual outfits.', 22.00, 18, 'images/hat4.jpg', 7, 8),
    ('Snapback Cap', 'Modern snapback cap with adjustable fit.', 25.00, 15, 'images/hat5.jpg', 7, 7),
    
    -- Accessories (Category ID 8)
    ('Leather Wallet', 'High-quality leather wallet with card slots.', 40.00, 10, 'images/accessory1.jpg', 8, 9),
    ('Sunglasses', 'Trendy sunglasses with UV protection.', 35.00, 15, 'images/accessory2.jpg', 8, 8),
    ('Gold Chain Necklace', 'Elegant gold chain necklace.', 50.00, 12, 'images/accessory3.jpg', 8, 9),
    ('Wristwatch', 'Stylish wristwatch with a leather strap.', 75.00, 8, 'images/accessory4.jpg', 8, 10),
    ('Canvas Belt', 'Durable and stylish belt.', 30.00, 20, 'images/accessory5.jpg', 8, 7)"
    ];

    // Loop through the tables queries in the array
    foreach ($insert_sql_statements as $table_name => $table_sql_statements) {
        insert_data_to_tables($connect, $table_name, $table_sql_statements);
    }

    $connect->close();
?>