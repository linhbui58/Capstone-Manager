<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Capstone Manager — Professional Capstone Management System">

    <title>Capstone Manager</title>

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- DATATABLE -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- SWEET ALERT -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- AOS ANIMATION -->
    <link rel="stylesheet"
          href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- MAIN CSS (last — overrides Bootstrap) -->
    <link rel="stylesheet"
          href="assets/css/style.css">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="app-wrapper">

    <!-- LOADER -->
    <div id="global-loader">

        <div style="text-align:center">
            <div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p style="color:#a78bfa;font-size:12px;margin-top:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">Loading...</p>
        </div>

    </div>

    <!-- MOBILE OVERLAY -->
    <div id="sidebar-overlay"></div>