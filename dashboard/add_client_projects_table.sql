USE r4xn;

-- Drop table if exists to recreate with correct enum
DROP TABLE IF EXISTS client_projects;

-- Create client_projects table with correct status enum
CREATE TABLE client_projects (
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
);

-- Insert sample data with correct image paths
INSERT INTO client_projects (title, description, website_type, client_name, timeline, website_url, image_path, featured, sort_order, status) VALUES
('E-Commerce Platform', 'A comprehensive e-commerce solution with advanced features including payment processing, inventory management, and customer analytics. Built with modern technologies for optimal performance and user experience.', 'E-Commerce', 'TechCorp Solutions', 'Jan 2025 – Mar 2025', 'https://techcorp.com', 'index-assets/project-1.png', TRUE, 1, 'active'),
('Corporate Website', 'A professional corporate website showcasing company services, team profiles, and client testimonials. Features responsive design and SEO optimization for maximum online visibility.', 'Corporate', 'Global Enterprises', 'Feb 2025 – Apr 2025', 'https://globalenterprises.com', 'index-assets/project-2.png', TRUE, 2, 'active'),
('Portfolio Website', 'A creative portfolio website for a design agency featuring project galleries, client testimonials, and contact forms. Built with modern animations and interactive elements.', 'Portfolio', 'Creative Studio', 'Mar 2025 – May 2025', 'https://creativestudio.com', 'index-assets/project-3.png', FALSE, 3, 'inactive'),
('Blog Platform', 'A content management system for a lifestyle blog with user authentication, comment system, and social media integration. Features responsive design and fast loading times.', 'Blog', 'Lifestyle Magazine', 'Apr 2025 – Jun 2025', 'https://lifestylemag.com', 'index-assets/project-4.png', FALSE, 4, 'active'),
('Restaurant Website', 'A restaurant website with online ordering system, menu management, and reservation booking. Features mobile-first design and real-time order tracking.', 'Restaurant', 'Fine Dining Co.', 'May 2025 – Jul 2025', 'https://finedining.com', 'index-assets/project-5.png', TRUE, 5, 'inactive'); 