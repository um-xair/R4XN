<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'r4xn';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create client_projects table
$sql = "CREATE TABLE IF NOT EXISTS client_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    website_type VARCHAR(100) NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    timeline VARCHAR(100) NOT NULL,
    website_url VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table client_projects created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Insert sample data with correct image paths
$sample_data = [
    [
        'title' => 'E-Commerce Platform',
        'description' => 'A comprehensive e-commerce solution with advanced features including payment processing, inventory management, and customer analytics. Built with modern technologies for optimal performance and user experience.',
        'website_type' => 'E-Commerce',
        'client_name' => 'TechCorp Solutions',
        'timeline' => 'Jan 2025 – Mar 2025',
        'website_url' => 'https://techcorp.com',
        'image_path' => 'index-assets/project-1.png',
        'featured' => 1,
        'sort_order' => 1
    ],
    [
        'title' => 'Corporate Website',
        'description' => 'A professional corporate website showcasing company services, team profiles, and client testimonials. Features responsive design and SEO optimization for maximum online visibility.',
        'website_type' => 'Corporate',
        'client_name' => 'Global Enterprises',
        'timeline' => 'Feb 2025 – Apr 2025',
        'website_url' => 'https://globalenterprises.com',
        'image_path' => 'index-assets/project-2.png',
        'featured' => 1,
        'sort_order' => 2
    ],
    [
        'title' => 'Portfolio Website',
        'description' => 'A creative portfolio website for a design agency featuring project galleries, client testimonials, and contact forms. Built with modern animations and interactive elements.',
        'website_type' => 'Portfolio',
        'client_name' => 'Creative Studio',
        'timeline' => 'Mar 2025 – May 2025',
        'website_url' => 'https://creativestudio.com',
        'image_path' => 'index-assets/project-3.png',
        'featured' => 0,
        'sort_order' => 3
    ],
    [
        'title' => 'Blog Platform',
        'description' => 'A content management system for a lifestyle blog with user authentication, comment system, and social media integration. Features responsive design and fast loading times.',
        'website_type' => 'Blog',
        'client_name' => 'Lifestyle Magazine',
        'timeline' => 'Apr 2025 – Jun 2025',
        'website_url' => 'https://lifestylemag.com',
        'image_path' => 'index-assets/project-4.png',
        'featured' => 0,
        'sort_order' => 4
    ],
    [
        'title' => 'Restaurant Website',
        'description' => 'A restaurant website with online ordering system, menu management, and reservation booking. Features mobile-first design and real-time order tracking.',
        'website_type' => 'Restaurant',
        'client_name' => 'Fine Dining Co.',
        'timeline' => 'May 2025 – Jul 2025',
        'website_url' => 'https://finedining.com',
        'image_path' => 'index-assets/project-5.png',
        'featured' => 1,
        'sort_order' => 5
    ]
];

// Check if table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM client_projects");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Insert sample data
    foreach ($sample_data as $data) {
        $stmt = $conn->prepare("INSERT INTO client_projects (title, description, website_type, client_name, timeline, website_url, image_path, featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssis", 
            $data['title'], 
            $data['description'], 
            $data['website_type'], 
            $data['client_name'], 
            $data['timeline'], 
            $data['website_url'], 
            $data['image_path'], 
            $data['featured'], 
            $data['sort_order']
        );
        
        if ($stmt->execute()) {
            echo "Sample project added: " . $data['title'] . "\n";
        } else {
            echo "Error adding sample project: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
} else {
    echo "Table already has data, skipping sample data insertion\n";
}

$conn->close();
echo "Database setup completed!\n";
?> 