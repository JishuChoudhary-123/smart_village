<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Village Gallery - Rajaqpur</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background: linear-gradient(to right, #eef2f3, #ffffff);
    font-family: 'Segoe UI', sans-serif;
}

/* ===== PREMIUM IMAGE CARD ===== */
.image-card {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
    cursor: pointer;
}

.image-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

.gallery-img {
    height: 250px;
    width: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.image-card:hover .gallery-img {
    transform: scale(1.1);
}

/* Overlay Title */
.image-overlay {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 15px;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    color: white;
    font-weight: 500;
    font-size: 16px;
}

/* ===== PREMIUM MODAL ===== */
.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
    justify-content: center;
    align-items: center;
}

.custom-modal-content {
    background: white;
    width: 60%;
    max-width: 850px;
    border-radius: 25px;
    padding: 30px;
    position: relative;
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from {transform: scale(0.8); opacity:0;}
    to {transform: scale(1); opacity:1;}
}

.modal-body-custom {
    display: flex;
    gap: 30px;
    align-items: center;
}

.modal-body-custom img {
    width: 50%;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.image-description {
    width: 50%;
}

.image-description h4 {
    font-weight: 600;
    margin-bottom: 15px;
}

.image-description p {
    color: #666;
    line-height: 1.6;
}

/* Close button */
.close-btn {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 28px;
    cursor: pointer;
    transition: 0.3s;
}

.close-btn:hover {
    color: #ff4d4d;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="index.php">🏡 Rajaqpur</a>

<div class="ms-auto">
<a href="index.php" class="btn btn-outline-light btn-sm">Home</a>
</div>
</div>
</nav>

<div class="container mt-5">
<h2 class="text-center mb-5">Village Gallery</h2>

<div class="row g-4">

<?php
$images = [
["village1.jpeg","Village Road","Beautiful road view of Rajaqpur village during evening."],
["village3.jpeg","Peaceful Sky","Calm sky and greenery representing peaceful village life."],
["village4.jpeg","Night Lights","Village houses decorated with colorful lighting."],
["village5.jpeg","Green Fields","Agricultural beauty of Rajaqpur village."],
["village6.jpeg","Sunset View","Golden sunset covering the village environment."],
["village7.jpeg","Night Street","Calm and peaceful village night view."],
["village8.jpeg","Fresh Nature","Green trees and fresh air environment."],
["village9.jpeg","Celebration","Village celebration with lights and joy."],
["village10.jpeg","Traditional Homes","Beautiful traditional houses of Rajaqpur."],
["village11.jpeg","Village Life","Simple and peaceful rural lifestyle."]
];

foreach($images as $img){
?>

<div class="col-md-4">
    <div class="image-card"
         onclick="openModal('images/<?php echo $img[0]; ?>','<?php echo $img[1]; ?>','<?php echo $img[2]; ?>')">
        <img src="images/<?php echo $img[0]; ?>" class="gallery-img">
        <div class="image-overlay"><?php echo $img[1]; ?></div>
    </div>
</div>

<?php } ?>

</div>
</div>

<!-- ===== MODAL ===== -->
<div id="imageModal" class="custom-modal">
    <div class="custom-modal-content">

        <span class="close-btn" onclick="closeModal()">&times;</span>

        <div class="modal-body-custom">
            <img id="modalImage" src="">
            <div class="image-description">
                <h4 id="modalTitle"></h4>
                <p id="modalDescription"></p>
            </div>
        </div>

    </div>
</div>

<script>

function openModal(imageSrc, title, description) {
    document.getElementById("imageModal").style.display = "flex";
    document.getElementById("modalImage").src = imageSrc;
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalDescription").innerText = description;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}

/* Close on outside click */
window.onclick = function(event) {
    var modal = document.getElementById("imageModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>

</body>
</html>