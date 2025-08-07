-- Pricing Plans Table
CREATE TABLE IF NOT EXISTS `pricing_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `features` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `is_popular` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service-Specific Pricing Table
CREATE TABLE IF NOT EXISTS `service_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) NOT NULL,
  `service_description` text,
  `icon_class` varchar(100) DEFAULT 'fas fa-code',
  `color_gradient` varchar(100) DEFAULT 'from-blue-500 to-purple-600',
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service Pricing Items Table
CREATE TABLE IF NOT EXISTS `service_pricing_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `fk_service_pricing_items_service` FOREIGN KEY (`service_id`) REFERENCES `service_pricing` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service Features Table
CREATE TABLE IF NOT EXISTS `service_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `fk_service_features_service` FOREIGN KEY (`service_id`) REFERENCES `service_pricing` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample pricing plans
INSERT INTO `pricing_plans` (`name`, `price`, `description`, `features`, `status`, `is_popular`, `sort_order`) VALUES
('Basic', 2500.00, 'Perfect for small businesses and startups', 'Responsive Website Design,Up to 5 Pages,Basic SEO Optimization,Contact Form Integration,Mobile-First Design,2 Revisions,Basic Analytics Setup,1 Month Support', 'active', 0, 1),
('Professional', 5000.00, 'Ideal for growing businesses and e-commerce', 'Everything in Basic,Up to 15 Pages,Advanced SEO Optimization,E-commerce Integration,Custom CMS Development,Payment Gateway Setup,Advanced Analytics,3 Months Support,Performance Optimization,Security Implementation', 'active', 1, 2),
('Enterprise', 12000.00, 'For large-scale applications and custom solutions', 'Everything in Professional,Unlimited Pages,Custom Web Application,IoT Integration,Advanced Security,API Development,Database Design,Cloud Infrastructure,6 Months Support,Priority Support,Performance Monitoring,Custom Integrations', 'active', 0, 3);

-- Insert sample service pricing
INSERT INTO `service_pricing` (`service_name`, `service_description`, `icon_class`, `color_gradient`, `status`, `sort_order`) VALUES
('Frontend Development', 'Responsive interfaces and modern web applications with cutting-edge technologies', 'fas fa-code', 'from-blue-500 to-purple-600', 'active', 1),
('IoT Solutions', 'Smart automation and connected systems for modern businesses', 'fas fa-microchip', 'from-orange-500 to-red-600', 'active', 2),
('Mobile Development', 'Native and cross-platform mobile applications for iOS and Android', 'fas fa-mobile-alt', 'from-indigo-500 to-blue-600', 'active', 3);

-- Insert sample service pricing items
INSERT INTO `service_pricing_items` (`service_id`, `item_name`, `price`, `description`, `sort_order`) VALUES
(1, 'Single Page App', 1500.00, 'Single page application with modern framework', 1),
(1, 'Multi-Page Website', 3000.00, 'Multi-page responsive website', 2),
(1, 'E-commerce Frontend', 4500.00, 'E-commerce website frontend', 3),
(1, 'Progressive Web App', 6000.00, 'Progressive web application', 4),
(2, 'IoT Consultation', 1000.00, 'IoT consultation and planning', 1),
(2, 'Smart Device Integration', 3500.00, 'Smart device integration services', 2),
(2, 'Full IoT Platform', 8000.00, 'Complete IoT platform development', 3),
(2, 'Industrial IoT System', 15000.00, 'Industrial IoT system development', 4),
(3, 'Cross-platform App', 4000.00, 'Cross-platform mobile application', 1),
(3, 'Native iOS App', 6000.00, 'Native iOS application', 2),
(3, 'Native Android App', 5500.00, 'Native Android application', 3),
(3, 'Full Mobile Suite', 12000.00, 'Complete mobile application suite', 4);

-- Insert sample service features
INSERT INTO `service_features` (`service_id`, `feature_name`, `sort_order`) VALUES
(1, 'Responsive Design', 1),
(1, 'Cross-browser Compatibility', 2),
(1, 'Performance Optimization', 3),
(1, 'SEO Optimization', 4),
(2, 'Device Integration', 1),
(2, 'Real-time Monitoring', 2),
(2, 'Data Analytics', 3),
(2, 'Security Protocols', 4),
(3, 'App Store Deployment', 1),
(3, 'Push Notifications', 2),
(3, 'Offline Functionality', 3),
(3, 'Performance Optimization', 4); 