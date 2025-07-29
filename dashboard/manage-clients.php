<?php
    // Set session configuration for cross-subdomain access
    ini_set('session.cookie_domain', '.r4xn.com');
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_secure', false);
    ini_set('session.cookie_httponly', false);
    ini_set('session.cookie_samesite', 'None');

    // Use custom session name to avoid conflicts
    session_name('erp_session');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    require_once 'config.php';

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'add') {
                // Add new client
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
                    $stmt = $conn->prepare("INSERT INTO clients (name, email, phone, services, deposit, total_amount, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssddss", $name, $email, $phone, $services, $deposit, $total_amount, $status, $notes);
                    
                    if ($stmt->execute()) {
                        header("Location: manage-clients.php?success=added");
                        exit;
                    } else {
                        $errors[] = "Failed to add client. Please try again.";
                    }
                }
            } elseif ($_POST['action'] === 'update') {
                // Update existing client
                $id = (int)$_POST['client_id'];
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
                    $stmt->bind_param("ssssddssi", $name, $email, $phone, $services, $deposit, $total_amount, $status, $notes, $id);
                    
                    if ($stmt->execute()) {
                        header("Location: manage-clients.php?success=updated");
                        exit;
                    } else {
                        $errors[] = "Failed to update client. Please try again.";
                    }
                }
            }
        }
    }

    // Handle client deletion
    if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: manage-clients.php?success=deleted");
            exit;
        } else {
            header("Location: manage-clients.php?error=delete_failed");
            exit;
        }
    }

    // Fetch client for editing
    $editClient = null;
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $editClient = $result->fetch_assoc();
        }
    }

    // Fetch all clients
    $clients = [];
    $sql = "SELECT * FROM clients ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients - R4XN</title>
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
                        <h1 class="text-2xl font-bold mb-1">Manage Clients</h1>
                        <p class="text-sm text-gray-400">Manage your client information and project details.</p>
                    </div>
                    <button onclick="openModal()" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Client
                    </button>
                </div>

                <!-- Success/Error Messages -->
                <!-- Toast Message -->
                <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                <?php if (isset($_GET['success'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toast = document.getElementById('toast');
                            let message = '';
                            let bgColor = '';
                            
                            <?php if ($_GET['success'] === 'added'): ?>
                                message = 'Client added successfully!';
                                bgColor = 'bg-green-500';
                            <?php elseif ($_GET['success'] === 'updated'): ?>
                                message = 'Client updated successfully!';
                                bgColor = 'bg-green-500';
                            <?php elseif ($_GET['success'] === 'deleted'): ?>
                                message = 'Client deleted successfully!';
                                bgColor = 'bg-green-500';
                            <?php endif; ?>
                            
                            if (message) {
                                toast.textContent = message;
                                toast.className = `fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 ${bgColor} pointer-events-none opacity-0 transition-opacity duration-300`;
                                toast.style.opacity = '1';
                                
                                setTimeout(() => {
                                    toast.style.opacity = '0';
                                }, 3000);
                            }
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toast = document.getElementById('toast');
                            let message = '';
                            let bgColor = '';
                            
                            <?php if ($_GET['error'] === 'delete_failed'): ?>
                                message = 'Failed to delete client. Please try again.';
                                bgColor = 'bg-red-500';
                            <?php endif; ?>
                            
                            if (message) {
                                toast.textContent = message;
                                toast.className = `fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 ${bgColor} pointer-events-none opacity-0 transition-opacity duration-300`;
                                toast.style.opacity = '1';
                                
                                setTimeout(() => {
                                    toast.style.opacity = '0';
                                }, 3000);
                            }
                        });
                    </script>
                <?php endif; ?>

                <!-- Analytics Section -->
                <?php
                    // Calculate analytics
                    $totalClients = count($clients);
                    $activeClients = 0;
                    $completedClients = 0;
                    $pendingClients = 0;
                    $totalRevenue = 0;
                    $totalDeposits = 0;
                    $totalRemaining = 0;

                    foreach ($clients as $client) {
                        $totalRevenue += $client['total_amount'];
                        $totalDeposits += $client['deposit'];
                        $totalRemaining += ($client['total_amount'] - $client['deposit']);
                        
                        switch ($client['status']) {
                            case 'active':
                                $activeClients++;
                                break;
                            case 'completed':
                                $completedClients++;
                                break;
                            case 'pending':
                                $pendingClients++;
                                break;
                        }
                    }
                ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                                <path d="M18 21a8 8 0 0 0-16 0"/>
                                <circle cx="10" cy="8" r="5"/>
                                <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/>
                            </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Total Clients</p>
                            <h2 class="text-xl font-semibold"><?= $totalClients ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark">
                            <path d="M10 18v-7"/>
                            <path d="M11.12 2.198a2 2 0 0 1 1.76.006l7.866 3.847c.476.233.31.949-.22.949H3.474c-.53 0-.695-.716-.22-.949z"/>
                            <path d="M14 18v-7"/>
                            <path d="M18 18v-7"/>
                            <path d="M3 22h18"/>
                            <path d="M6 18v-7"/>
                        </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Total Revenue</p>
                            <h2 class="text-xl font-semibold">MYR <?= number_format($totalRevenue, 2) ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-coins-icon lucide-hand-coins">
                            <path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/>
                            <path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/>
                            <path d="m2 16 6 6"/>
                            <circle cx="16" cy="9" r="2.9"/>
                            <circle cx="6" cy="5" r="3"/>
                        </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Total Deposits</p>
                            <h2 class="text-xl font-semibold">MYR <?= number_format($totalDeposits, 2) ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/>
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                        </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Pending Amount</p>
                            <h2 class="text-xl font-semibold">MYR <?= number_format($totalRemaining, 2) ?></h2>
                        </div>
                    </div>
                </div>

                <!-- Status Analytics -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-md border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Active Projects</p>
                                <p class="text-2xl font-bold text-green-600"><?= $activeClients ?></p>
                            </div>
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Completed Projects</p>
                                <p class="text-2xl font-bold text-blue-600"><?= $completedClients ?></p>
                            </div>
                            <div class="bg-blue-100 p-2 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <path d="m9 11 3 3L22 4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Projects</p>
                                <p class="text-2xl font-bold text-yellow-600"><?= $pendingClients ?></p>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12,6 12,12 16,14"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clients List -->
                <div class="bg-white rounded-lg shadow-sm border">
                    
                    <?php if (empty($clients)): ?>
                        <div class="p-12 text-center text-gray-500">
                            <div class="mb-4">
                                <i class="fas fa-users text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No clients found</h3>
                            <p class="text-sm text-gray-400 mb-4">Get started by adding your first client</p>
                            <button onclick="openModal()" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Add First Client
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-black">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Client Information
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Services
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Financial Details
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($clients as $client): ?>
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-5">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-semibold text-gray-900 mb-1">
                                                            <?= htmlspecialchars($client['name']) ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500 mb-1">
                                                            <i class="fas fa-envelope mr-1"></i>
                                                            <?= htmlspecialchars($client['email']) ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            <?= htmlspecialchars($client['phone']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-sm text-gray-900 max-w-xs">
                                                    <div class="">
                                                        <?= nl2br(htmlspecialchars($client['services'])) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500 uppercase font-medium">Deposit</span>
                                                        <span class="text-sm font-semibold text-green-600">
                                                            MYR <?= number_format($client['deposit'], 2) ?>
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500 uppercase font-medium">Total</span>
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            MYR <?= number_format($client['total_amount'], 2) ?>
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500 uppercase font-medium">Remaining</span>
                                                        <span class="text-sm font-semibold <?= ($client['total_amount'] - $client['deposit']) > 0 ? 'text-orange-600' : 'text-green-600' ?>">
                                                            MYR <?= number_format($client['total_amount'] - $client['deposit'], 2) ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($client['total_amount'] > 0): ?>
                                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                                            <?php $percentage = ($client['deposit'] / $client['total_amount']) * 100; ?>
                                                            <div class="bg-green-500 h-2 rounded-full" style="width: <?= min(100, $percentage) ?>%"></div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 text-center">
                                                            <?= number_format($percentage, 1) ?>% paid
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <?php
                                                    $statusColors = [
                                                        'active' => 'bg-green-100 text-green-800 border-green-200',
                                                        'completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                                    ];
                                                    $statusColor = $statusColors[$client['status']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                ?>
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-6 py-3 rounded-full text-xs font-semibold border <?= $statusColor ?>">
                                                        <?= ucfirst($client['status']) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-3">
                                                    <button onclick="editClient(<?= $client['id'] ?>)" 
                                                           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Edit
                                                    </button>
                                                    <a href="manage-clients.php?delete=<?= $client['id'] ?>" 
                                                       class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                                       onclick="return confirm('Are you sure you want to delete this client? This action cannot be undone.')">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Client Modal -->
    <div id="clientModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3" id="modalTitle">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="m22 21-2-2"/>
                            <path d="M16 16h.01"/>
                        </svg>
                    </div>
                    Add New Client
                </h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="clientForm" method="POST" class="space-y-4">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="client_id" id="clientId" value="">
                
                <!-- Client Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Client Name</label>
                        <input type="text" id="name" name="name" required placeholder="Enter client name"
                               class="border border-gray-300 rounded-md px-4 py-3 w-full">
                    </div>
                    
                    <div>
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Enter client email"
                               class="border border-gray-300 rounded-md px-4 py-3 w-full">
                    </div>
                    
                    <div>
                        <label class="block mb-1 font-medium">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required placeholder="Enter phone number"
                               class="border border-gray-300 rounded-md px-4 py-3 w-full">
                    </div>
                    
                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select id="status" name="status" 
                                class="border border-gray-300 rounded-md px-4 py-3 w-full">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <label class="block mb-1 font-medium">Services</label>
                    <textarea id="services" name="services" rows="3" required
                              placeholder="Enter the services you will provide (e.g., Web Design, Web App Development, UI/UX Design, etc.)"
                              class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>

                <!-- Financial Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-medium">Deposit Amount (MYR)</label>
                        <input type="number" id="deposit" name="deposit" step="0.01" min="0" required placeholder="0.00"
                               class="border border-gray-300 rounded-md px-4 py-3 w-full">
                    </div>
                    
                    <div>
                        <label class="block mb-1 font-medium">Total Project Amount (MYR)</label>
                        <input type="number" id="total_amount" name="total_amount" step="0.01" min="0" required placeholder="0.00"
                               class="border border-gray-300 rounded-md px-4 py-3 w-full">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block mb-1 font-medium">Additional Notes</label>
                    <textarea id="notes" name="notes" rows="2"
                              placeholder="Any additional notes about the client or project..."
                              class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Save Client</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('clientModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerHTML = `
                <div class="bg-black p-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="m22 21-2-2"/>
                        <path d="M16 16h.01"/>
                    </svg>
                </div>
                Add New Client
            `;
            document.getElementById('formAction').value = 'add';
            document.getElementById('clientId').value = '';
            document.getElementById('clientForm').reset();
        }

        function closeModal() {
            document.getElementById('clientModal').classList.add('hidden');
        }

        function editClient(clientId) {
            // Fetch client data via AJAX or redirect with edit parameter
            window.location.href = 'manage-clients.php?edit=' + clientId;
        }

        // Close modal when clicking outside
        document.getElementById('clientModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Auto-fill form if editing
        <?php if ($editClient): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clientModal').classList.remove('hidden');
            document.getElementById('modalTitle').innerHTML = `
                <div class="bg-black p-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="m22 21-2-2"/>
                        <path d="M16 16h.01"/>
                    </svg>
                </div>
                Edit Client
            `;
            document.getElementById('formAction').value = 'update';
            document.getElementById('clientId').value = '<?= $editClient['id'] ?>';
            document.getElementById('name').value = '<?= htmlspecialchars($editClient['name']) ?>';
            document.getElementById('email').value = '<?= htmlspecialchars($editClient['email']) ?>';
            document.getElementById('phone').value = '<?= htmlspecialchars($editClient['phone']) ?>';
            document.getElementById('services').value = '<?= htmlspecialchars($editClient['services']) ?>';
            document.getElementById('deposit').value = '<?= $editClient['deposit'] ?>';
            document.getElementById('total_amount').value = '<?= $editClient['total_amount'] ?>';
            document.getElementById('status').value = '<?= $editClient['status'] ?>';
            document.getElementById('notes').value = '<?= htmlspecialchars($editClient['notes']) ?>';
        });
        <?php endif; ?>
    </script>
</body>
</html> 