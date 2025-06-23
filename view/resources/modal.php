<link rel="stylesheet" href="./assets/css/modal.css">

<section class="modalBackground">
    <section class="modal">
        <button onclick="changepage()">&times;</button>
        <?php
            if(isset($_GET['modalpage'])){
                if($_GET['modalpage'] == "login"){
                    include_once('view/pages/login.php');
                }else{
                    include_once('view/pages/produtos.php');
                }
            }
        ?>
    </section>
</section>

<script>

function changepage(){
    window.location="index.php?page=test&modal=nao"
}
</script>