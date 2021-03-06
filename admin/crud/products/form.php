<?php
require_once "../../functions.php";
init();
$id=(isset($_GET['id'])) ? $_GET['id'] : false;
$data_set=(isset($_GET['id'])) ? get_one('products', $_GET['id']) : false;
$name=($data_set == false) ? '' : $data_set['name'];
$content=($data_set == false) ? '' : $data_set['content'];
$slug=($data_set == false) ? '' : $data_set['slug'];
$price=($data_set == false) ? '' : $data_set['price'];
$options = get_rows('products');
require_once "../../header_admin.php";
require_once "../../sidebar_admin.php";
?>

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        <b><?= isset($_GET['id'])? 'Update product':'Adding new product'?></b>
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="cmxform form-horizontal tasi-form" method="post">

                                <?php if (isset($_SESSION["adding_product"])) {
                                    echo '<p class="' . (($_SESSION["adding_product"]['type'] == 'error') ?
                                            'error' : 'success') . '" >' . $_SESSION["adding_product"]['message'] . '</p>';
                                }
                                unset ($_SESSION["adding_product"]);

                                if (isset($_SESSION["updating_product"])) {
                                    echo '<p class="' . (($_SESSION["updating_product"]['type'] == 'error') ?
                                            'error' : 'success') . '" >' . $_SESSION["updating_product"]['message'] . '</p>';
                                }
                                unset ($_SESSION["updating_product"]);
                                ?>

                                <div class="form-group ">
                                    <label for="name" class="control-label col-lg-2">Name of product</label>
                                    <div class="col-lg-10">
                                        <input required class="form-control" id="name" name="name" type="text"
                                               value="<?= $name ?>">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="content" class="control-label col-lg-2">Content</label>
                                    <div class="col-lg-10">
                                        <input required class="form-control" id="content" name="content" type="text"
                                               value="<?= $content ?>">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="slug" class="control-label col-lg-2">Slug</label>
                                    <div class="col-lg-10">
                                        <input required class="form-control" id="slug" name="slug" type="text"
                                               value="<?= $slug ?>">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="status" class="control-label col-lg-2">Status</label>
                                    <div class="col-lg-10">
                                        <select name="status" id="status" class="form-control">

                                            <?php
                                                $html = '';
                                                $options= get_enum('products', 'status');
                                                foreach ($options as $option) {
                                                    $html .= "<option value='" . $option . "'>" . $option . "</option>";
                                                }
                                                echo $html;
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="price" class="control-label col-lg-2">Price</label>
                                    <div class="col-lg-10">
                                        <input required class="form-control" id="price" name="price" type="text"
                                               value="<?= $price ?>">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="currency" class="control-label col-lg-2">Currency</label>
                                    <div class="col-lg-10">
                                        <select type="enum" name="currency" id="currency" class="form-control">

                                            <?php
                                            $array= get_enum('products', 'currency');
                                            $html = '';
                                            foreach ($array as $option) {
                                                $html .= "<option value='" . $option . "'>" . $option . "</option>";
                                            }
                                            echo $html;
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="action" value="<?= isset($_GET['id'])? 'update-product' : 'add-product'?>">
                                <input type="hidden" name="id" value="<?= isset($_GET['id'])? $_GET['id'] : ''?>">
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-danger" type="submit"><?= isset($_GET['id'])? 'Update' : 'Save'?></button>
                                        <button class="btn btn-default" type="button">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<?php require_once "../../footer_admin.php"; ?>

<!--script for this page-->

</body>
</html>