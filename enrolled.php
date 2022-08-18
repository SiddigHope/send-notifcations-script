<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Costumers class
require_once BASE_PATH . '/lib/Costumers/Clients.php';
$clients = new Clients();
// Get Input data from query string
$order_by	= filter_input(INPUT_GET, 'order_by');
$order_dir	= filter_input(INPUT_GET, 'order_dir');
$search_str	= filter_input(INPUT_GET, 'search_str');

// Per page limit for pagination
$pagelimit = 15;

// Get current page
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
	$page = 1;
}

// If filter types are not selected we show latest added data first
if (!$order_by) {
	$order_by = 'MatriculeF';
}
if (!$order_dir) {
	$order_dir = 'Desc';
}

// Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('MatriculeF', 'NomArF', 'PrenomArF', 'token');

// Start building query according to input parameters
// If search string
if ($search_str) {
	$db->where('MatriculeF', '%' . $search_str . '%', 'like');
    $db->orwhere('NomArF', '%' . $search_str . '%', 'like');
    $db->orwhere('PrenomArF', '%' . $search_str . '%', 'like');
}
// If order direction option selected
if ($order_dir) {
	$db->orderBy($order_by, $order_dir);
}

// Set pagination limit
// $db->pageLimit = $pagelimit;

// Get result of the query
$rows = $db->arraybuilder()->paginate('personnels_token', $page, $select);
$total_pages = $db->totalPages;
$count= 1
?>
<?php include BASE_PATH . '/includes/header.php'; ?>

<style>
/* Style the Image Used to Trigger the Modal */
#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

</style>

<!-- Main container -->
<div id="page-wrapper"  style="margin-right:20%;width:80%;">
    <div class="row">
        <div>
            <h1 class="page-header">الاساتذة </h1>
        </div>
        
    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

    <!-- Filters -->
    <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">بحث</label>
            <input type="text" class="form-control" id="input_search" name="search_str" value="<?php echo htmlspecialchars($search_str, ENT_QUOTES, 'UTF-8'); ?>">
            <label for="input_order">ترتيب ب</label>
            <select name="order_by" class="form-control">
                <?php
foreach ($clients->setOrderingValues() as $opt_value => $opt_name):
	($order_by === $opt_value) ? $selected = 'selected' : $selected = '';
	echo ' <option value="' . $opt_value . '" ' . $selected . '>' . $opt_name . '</option>';
endforeach;
?>
            </select>
            <select name="order_dir" class="form-control" id="input_order">
                <option value="Asc" <?php
if ($order_dir == 'Asc') {
	echo 'selected';
}
?> >تصاعدي</option>
                <option value="Desc" <?php
if ($order_dir == 'Desc') {
	echo 'selected';
}
?>>تنازلي</option>
            </select>
            <input type="submit" value=" بحث" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <!-- //Filters -->

    <!-- Table -->
    <table class="table text-center table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th class="text-center" width="5%">الرقم التعريفي </th>
                <th class="text-center" width="20%">الاسم</th>
                <th class="text-center" width="10%">اللقب</th>
                <!-- <th width="10%"></th> -->
                <th class="text-center" width="10%">اشعار</th>
                <!-- <th width="10%">العمليات</th> -->
            </tr>
        </thead>
        <tbody class=''>
            <?php foreach ($rows as $row):
            ?>
            <tr>
                <td><?php echo $row['MatriculeF']; ?></td>
                <td><?php echo htmlspecialchars($row['NomArF']); ?></td>
                <td><?php echo htmlspecialchars($row['PrenomArF']); ?></td>
                <!-- <img src="assets/images/1407033719_1597997136.jpg" alt="Snow" style="width:100%;max-width:300px"> -->
                <td>
                    <a href="add_note.php?teacher=<?php echo $row['MatriculeF']; ?>&operation=edit" class="btn btn-success"><i class="glyphicon glyphicon-bell"></i></a>
                </td>
            </tr>
            <?php $count++ ?>
            <?php endforeach;  ?>
        </tbody>
    </table>
    <!-- //Table -->

    <!-- Pagination -->
    <div class="text-center">
    	<?php echo paginationLinks($page, $total_pages, 'enroled.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>
