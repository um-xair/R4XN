-- Services and Case Studies Management Tables

-- Services table
CREATE TABLE IF NOT EXISTS `services` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text NOT NULL,
    `image_url` varchar(500) DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `sort_order` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Projects table (for each service)
CREATE TABLE IF NOT EXISTS `projects` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `image_url` varchar(500) DEFAULT NULL,
    `project_url` varchar(500) DEFAULT NULL,
    `case_study_url` varchar(500) DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `sort_order` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Case Studies table
CREATE TABLE IF NOT EXISTS `case_studies` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `hero_image_url` varchar(500) DEFAULT NULL,
    `preview_button_text` varchar(100) DEFAULT 'Preview Project',
    `preview_button_url` varchar(500) DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Features table (for case studies)
CREATE TABLE IF NOT EXISTS `case_study_features` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `case_study_id` int(11) NOT NULL,
    `feature_name` varchar(255) NOT NULL,
    `color_class` varchar(100) DEFAULT 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
    `sort_order` int(11) DEFAULT 0,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`case_study_id`) REFERENCES `case_studies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default services
INSERT INTO `services` (`name`, `slug`, `description`, `image_url`, `sort_order`) VALUES
('E-commerce Website', 'ecommerce', 'Complete online shopping solutions with payment processing, inventory management, and customer analytics. Perfect for retail businesses looking to expand their online presence.', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2015&q=80', 1),
('Services Appointment', 'services', 'Professional booking and scheduling systems for service-based businesses with advanced calendar integration and automated reminders.', 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80', 2),
('Interactive Storytelling', 'storytelling', 'Engaging narrative experiences that captivate audiences through multimedia content, interactive elements, and immersive storytelling techniques.', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80', 3),
('Product Showcase', 'product', 'Comprehensive product catalog websites with detailed specifications, interactive features, and optimized user experience for product discovery.', 'https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80', 4),
('Corporate Identity', 'corporate', 'Professional corporate websites with comprehensive brand guidelines, company information, and business solutions designed for enterprise clients.', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80', 5),
('Custom Solutions', 'custom', 'Innovative custom solutions, mobile applications, IoT projects, and cutting-edge digital experiences tailored to unique business requirements.', 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80', 6);

-- Insert sample projects for E-commerce service
INSERT INTO `projects` (`service_id`, `name`, `description`, `image_url`, `project_url`, `case_study_url`, `sort_order`) VALUES
(1, 'TechCorp E-commerce Platform', 'A comprehensive e-commerce solution with advanced features including payment processing, inventory management, and customer analytics. Built with modern technologies for optimal performance and user experience.', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2015&q=80', '#', 'case-study.php?project=techcorp', 1),
(1, 'Fashion Store Online', 'Modern fashion e-commerce website with product catalog, shopping cart, and secure payment gateway integration. Features responsive design and advanced filtering options.', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2015&q=80', '#', 'case-study.php?project=fashion', 2);

-- Insert sample case study for TechCorp project
INSERT INTO `case_studies` (`project_id`, `title`, `description`, `hero_image_url`, `preview_button_text`, `preview_button_url`) VALUES
(1, 'TechCorp E-commerce Platform', 'A comprehensive e-commerce solution with advanced features including payment processing, inventory management, and customer analytics. Built with modern technologies for optimal performance and user experience.', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2015&q=80', 'Preview Project', 'case-study-detail.php?project=techcorp');

-- Insert sample features for the case study
INSERT INTO `case_study_features` (`case_study_id`, `feature_name`, `color_class`, `sort_order`) VALUES
(1, 'Payment Processing', 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200', 1),
(1, 'Inventory Management', 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200', 2),
(1, 'Customer Analytics', 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200', 3),
(1, 'Responsive Design', 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200', 4),
(1, 'Shopping Cart', 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200', 5),
(1, 'Order Tracking', 'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200', 6);
