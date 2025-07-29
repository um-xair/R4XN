<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    require_once 'config.php';

    // Get client ID from URL
    $client_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($client_id <= 0) {
        header("Location: manage-clients.php");
        exit;
    }

    // Fetch client data
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header("Location: manage-clients.php");
        exit;
    }
    
    $client = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $services = trim($_POST['services']);
        $deposit = (float)$_POST['deposit'];
        $total_amount = (float)$_POST['total_amount'];
        $status = $_POST['status'];
        $notes = trim($_POST['notes']);

        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Client name is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        }
        
        if (empty($phone)) {
            $errors[] = "Phone number is required";
        }
        
        if (empty($services)) {
            $errors[] = "Services are required";
        }
        
        if ($deposit < 0) {
            $errors[] = "Deposit amount cannot be negative";
        }
        
        if ($total_amount < 0) {
            $errors[] = "Total amount cannot be negative";
        }
        
        if ($deposit > $total_amount) {
            $errors[] = "Deposit cannot be greater than total amount";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("UPDATE clients SET name = ?, email = ?, phone = ?, services = ?, deposit = ?, total_amount = ?, status = ?, notes = ? WHERE id = ?");
            $stmt->bind_param("ssssddssi", $name, $email, $phone, $services, $deposit, $total_amount, $status, $notes, $client_id);
            
            if ($stmt->execute()) {
                header("Location: manage-clients.php?success=updated");
                exit;
            } else {
                $errors[] = "Failed to update client. Please try again.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client - R4XN</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Poppins", sans-serif;
        }
        html {
            overflow-x: hidden;
        }
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-[#F5F5FD]">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <main class="flex-1 p-6 w-full overflow-y-auto min-w-0 relative">
            <div>
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-3 sm:gap-0 px-2 sm:px-0">
                    <div class="max-w-full sm:max-w-[60%]">
                        <h1 class="text-3xl font-extrabold mb-1">Edit Client</h1>
                        <p class="text-sm text-gray-400">Update client information and project details.</p>
                    </div>
                    <a href="manage-clients.php" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Clients
                    </a>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Client Form -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">Edit Client Information</h2>
                    </div>
                    
                    <form method="POST" class="p-6 space-y-6">
                        <!-- Client Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                                <input type="text" id="name" name="name" required
                                       value="<?= htmlspecialchars($_POST['name'] ?? $client['name']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="email" required
                                       value="<?= htmlspecialchars($_POST['email'] ?? $client['email']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required
                                       value="<?= htmlspecialchars($_POST['phone'] ?? $client['phone']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                    <option value="active" <?= ($_POST['status'] ?? $client['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="pending" <?= ($_POST['status'] ?? $client['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="completed" <?= ($_POST['status'] ?? $client['status']) === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Services -->
                        <div>
                            <label for="services" class="block text-sm font-medium text-gray-700 mb-2">Services *</label>
                            <textarea id="services" name="services" rows="4" required
                                      placeholder="Enter the services you will provide (e.g., Web Design, Web App Development, UI/UX Design, etc.)"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"><?= htmlspecialchars($_POST['services'] ?? $client['services']) ?></textarea>
                        </div>

                        <!-- Financial Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Deposit Amount ($) *</label>
                                <input type="number" id="deposit" name="deposit" step="0.01" min="0" required
                                       value="<?= htmlspecialchars($_POST['deposit'] ?? $client['deposit']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Project Amount ($) *</label>
                                <input type="number" id="total_amount" name="total_amount" step="0.01" min="0" required
                                       value="<?= htmlspecialchars($_POST['total_amount'] ?? $client['total_amount']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="Any additional notes about the client or project..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"><?= htmlspecialchars($_POST['notes'] ?? $client['notes']) ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="manage-clients.php" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition-colors">
                                Update Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 