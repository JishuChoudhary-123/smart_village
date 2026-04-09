<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Heritage – Rajaqpur Baayein Ka Kuan</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f8f9fa;
}

.hero-section {
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('images/baoli.jpeg') center/cover no-repeat;
    color: white;
    padding: 100px 20px;
    text-align: center;
}

.content-box {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.gallery-img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.gallery-img:hover {
    transform: scale(1.03);
}

/* Modal background */
.modal-content {
    background: transparent;
    border: none;
}
</style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="index.php">🏡 Rajaqpur</a>
<a href="index.php" class="btn btn-outline-light btn-sm">Back to Home</a>
</div>
</nav>

<!-- HERO -->
<div class="hero-section">
<h1>🏛 Heritage of Rajaqpur</h1>
<p>Baayein Ka Kuan (Amba Devi Ka Kuan)</p>
</div>

<div class="container my-5">

<div class="content-box">

<p>
Baayein Ka Kuan, also known as <strong>Amba Devi Ka Kuan</strong>, 
is a historic 12th-century stepwell (baoli) located near the spinning mill 
on the Amroha–Bijnor road in Rajaqpur, northwest of Amroha.
</p>

<p>
It is traditionally believed to have been built by <strong>Amba Devi</strong>, 
the sister of the Rajput ruler <strong>Prithviraj Chauhan</strong>, 
for public welfare and water conservation. The structure is approximately 
30 feet deep and includes 30 stone steps leading down to the water level, 
along with distinctive arched verandas built from stone and gravel.
</p>

<p>
The well once served as an essential community water source and 
is regarded as one of the significant historical landmarks of the region.
</p>

<p>
Local folklore adds a fascinating dimension to its history. 
According to village legends, the stepwell was constructed in a single night by supernatural forces. 
It is said that the work stopped when a woman began spinning her charkha (spinning wheel) at midnight, 
causing the spirits to disappear before completing the structure.
</p>

<p>
Although time and lack of preservation have affected its condition, 
Baayein Ka Kuan remains a symbol of Rajaqpur’s historical and cultural identity.
</p>

</div>

<!-- IMAGE GALLERY -->
<h3 class="text-center mt-5 mb-4">Heritage Image Gallery</h3>

<div class="row g-3">

<?php
$images = [
    "baoli.jpeg",
    "heritage1.jpeg",
    "heritage2.jpeg",
    "heritage3.jpeg",
    "heritage4.jpeg",
    "heritage5.jpeg",
    "heritage6.jpeg"
];

foreach($images as $img){
    echo '
    <div class="col-lg-4 col-md-6">
        <img src="images/'.$img.'" class="gallery-img" onclick="openImage(this.src)">
    </div>
    ';
}
?>

</div>

</div>


<!-- IMAGE POPUP MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark border-0">

      <div class="modal-header border-0">
        <button type="button" class="btn-close btn-close-white ms-auto"
                data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded">
      </div>

    </div>
  </div>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openImage(src) {
    document.getElementById("modalImage").src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}
</script>

</body>
</html>