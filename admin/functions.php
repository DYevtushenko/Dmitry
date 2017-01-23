<?php
session_start();
const ADMIN_URL = 'http://localhost/dmitry/admin';
const ADMIN_PATH = __DIR__;
if (!file_exists(ADMIN_PATH . '/local.php')) {
    die('You did not have file local.php');
}
$local = require_once(ADMIN_PATH . '/local.php');
$base = require_once(ADMIN_PATH . '/base.php');
$config = array_merge($base, $local);

function init()
{
    if (!is_login() && $_SERVER['SCRIPT_NAME'] != '/dmitry/admin/login.php') {
        redirect("/dmitry/admin/login.php");
    }
    auth();
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        logout();
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-user') {
        if (isset ($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset ($_POST['password'])) {
            $password = $_POST['password'];
        }
        if (isset ($_POST['confirm_password'])) {
            $confirm_password = $_POST['confirm_password'];
        }
        if (isset ($_POST['email'])) {
            $email = $_POST['email'];
        }
        if (isset ($_POST['role'])) {
            $role = $_POST['role'];
        }
        try {
            $user_created = create_admin($name, $email, $password, $confirm_password, $role);
            if ($user_created) {
                $arr = [
                    'message' => "User with $role rights was added.",
                    'type' => 'success'
                ];
            } else {
                $arr = [
                    'message' => 'Please complete all fields.',
                    'type' => 'error'
                ];
            }
            $_SESSION["user_add_message"] = $arr;
        } catch (Exception $e) {
            $arr = [
                'message' => $e->getMessage(),
                'type' => 'error'
            ];
            $_SESSION["user_add_message"] = $arr;
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-option-group') {
        if (isset ($_POST['name'])) {
            try {
                $create_option_group = create_option_group($_POST['name']);
                if ($create_option_group) {
                    $arr = [
                        'message' => "Option was added.",
                        'type' => 'success'
                    ];
                } else {
                    $arr = [
                        'message' => 'Please complete all fields.',
                        'type' => 'error'
                    ];
                }
                $_SESSION["adding_option_group"] = $arr;
            } catch (Exception $e) {
                $arr = [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ];
                $_SESSION["adding_option_group"] = $arr;
            }
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-config') {
        if (isset ($_POST['name']) && isset ($_POST['value']) && isset ($_POST['opt_group'])) {
            try {
                $create_config = create_config($_POST['name'], $_POST['value'], $_POST['opt_group']);
                if ($create_config) {
                    $arr = [
                        'message' => "Config was added.",
                        'type' => 'success'
                    ];
                } else {
                    $arr = [
                        'message' => 'Please complete all fields.',
                        'type' => 'error'
                    ];
                }
                $_SESSION["adding_config"] = $arr;
            } catch (Exception $e) {
                $arr = [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ];
                $_SESSION["adding_config"] = $arr;
            }
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-lead') {
        if (isset ($_POST['email']) && isset($_POST['status'])) {
            try {
                $create_lead = create_lead($_POST['email'], $_POST['status']);
                if ($create_lead) {
                    $arr = [
                        'message' => "Lead was added.",
                        'type' => 'success'
                    ];
                } else {
                    $arr = [
                        'message' => 'Email is empty.',
                        'type' => 'error'
                    ];
                }
                $_SESSION["adding_lead"] = $arr;
            } catch (Exception $e) {
                $arr = [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ];
                $_SESSION["adding_config"] = $arr;
            }
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-page') {
        try {
            if (isset ($_POST['name']) && isset ($_POST['content']) && isset ($_POST['slug']) && isset($_POST['status'])) {
                create_page($_POST['name'], $_POST['content'], $_POST['slug'], $_POST['status']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_page"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'add-product') {
        try {
            if (isset ($_POST['name']) && isset ($_POST['content']) && isset ($_POST['slug'])
                && isset($_POST['status']) && isset($_POST['price']) && isset($_POST['currency'])) {
                create_product($_POST['name'], $_POST['content'], $_POST['slug'], $_POST['status'],
                    $_POST['price'], $_POST['currency']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_product"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-config') {
        try {
            if (isset ($_POST['name']) && isset ($_POST['value']) && isset ($_POST['opt_group']) && isset($_POST['id'])) {
                update_config($_POST['name'], $_POST['value'], $_POST['opt_group'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_config"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-lead') {
        try {
            if (isset ($_POST['email']) && isset ($_POST['status']) && isset($_POST['id'])) {
                update_lead($_POST['email'], $_POST['status'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_lead"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-option-group') {
        try {
            if (isset ($_POST['name']) && isset($_POST['id'])) {
                update_option_group($_POST['name'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_option_group"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-page') {
        try {
            if (isset ($_POST['name']) && isset($_POST['content']) && isset($_POST['slug'])
                && isset($_POST['status']) && isset($_POST['id'])) {
                update_page($_POST['name'], $_POST['content'], $_POST['slug'], $_POST['status'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_page"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-product') {
        try {
            if (isset ($_POST['name']) && isset($_POST['content']) && isset($_POST['slug']) && isset($_POST['status'])
                && isset($_POST['price']) && isset($_POST['currency']) && isset($_POST['id'])) {
                update_product($_POST['name'], $_POST['content'], $_POST['slug'], $_POST['status'],
                    $_POST['price'], $_POST['currency'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_product"] = $e->getMessage();
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'update-user') {
        try {
            if (isset ($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])
                && isset($_POST['role']) && isset($_POST['id']) && $_POST['password']==$_POST['confirm_password']) {
                update_user($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'], $_POST['id']);
            } else {
                throw new Exception('Fill in all lines');
            }
        } catch (Exception $e) {
            $_SESSION["adding_user"] = $e->getMessage();
        }
    }
    if (isset($_GET['delete_id'])) {
        delete($_GET['name'], $_GET['delete_id']);
    }

}

function create_admin($name, $email, $password, $confirm_password, $role)
{
    if (isset($name) && isset($email) && isset($password) && isset($confirm_password) && isset($role) && !empty($name)
        && !empty($email) && !empty($password) && !empty($role) && $password == $confirm_password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Not valid email');
        }
        $conn = get_connection();
        $sql = "SELECT * FROM users WHERE name='$name' OR email='$email'";
        $result = $conn->query($sql);
        if (isset($result->num_rows) && $result->num_rows > 0) {
            throw new Exception('User already exist');
        }
        $password = md5($password);
        $insert = "INSERT INTO users (name, role, email, password) VALUES ('$name','$role','$email','$password')";
        if ($conn->query($insert) === TRUE) {
            return true;
        } else {
            throw new Exception('Error with inserting into database');
        }
    }
    throw new Exception('Arguments are not correct');
}

function create_option_group($name)
{
    $conn = get_connection();

    $insert = "INSERT INTO option_group (name,count) VALUES ('$name',0 )";
    if ($conn->query($insert) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function create_config($name, $value, $opt_group)
{
    $conn = get_connection();
    $insert = "INSERT INTO configs (name, value, option_group) VALUES ('$name', '$value', '$opt_group')";
    if ($conn->query($insert) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function create_lead($email, $status)
{
    $conn = get_connection();
    $insert = "INSERT INTO leads (email, status) VALUES ('$email', '$status')";
    if ($conn->query($insert) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function create_page($name, $content, $slug, $status)
{
    $conn = get_connection();
    $insert = "INSERT INTO pages (name, content, slug, status) VALUES ('$name','$content','$slug','$status')";
    if ($conn->query($insert) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function create_product($name, $content, $slug, $status, $price, $currency)
{
    $conn = get_connection();
    $insert = "INSERT INTO products (name, content, slug, status,price,currency) VALUES ('$name','$content','$slug','$status','$price','$currency')";
    if ($conn->query($insert) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update($name, $content, $slug, $status, $price, $currency, $id)
{
    $conn = get_connection();
    $update = "UPDATE products SET name='$name',content='$content',slug='$slug',status='$status',price='$price',currency='$currency' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_config($name, $value, $opt_group, $id)
{
    $conn = get_connection();
    $update = "UPDATE configs SET name='$name',value='$value',option_group='$opt_group' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_lead($email, $status, $id)
{
    $conn = get_connection();
    $update = "UPDATE leads SET email='$email',status='$status' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_option_group($name, $id)
{
    $conn = get_connection();
    $update = "UPDATE option_group SET name='$name' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_page($name, $content, $slug, $status, $id)
{
    $conn = get_connection();
    $update = "UPDATE pages SET name='$name',content='$content',slug='$slug',status='$status' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_product($name, $content, $slug, $status, $price, $currency, $id)
{
    $conn = get_connection();
    $update = "UPDATE products SET name='$name',content='$content',slug='$slug',status='$status',price='$price',currency='$currency' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function update_user($name, $email, $password, $role, $id)
{
    $conn = get_connection();
    $update = "UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id=$id";
    if ($conn->query($update) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function delete($name, $id)
{
    $conn = get_connection();
    $delete = "DELETE FROM $name WHERE id=$id";
    if ($conn->query($delete) === TRUE) {
        return true;
    } else {
        throw new Exception('Error with inserting into database');
    }
}

function get_rows($name, $page = 1, $per_page = 20)
{
    $res = [];
    if (isset($name) && !empty($name)) {
        $conn = get_connection();
        $sql = "SELECT * FROM $name ";
        $_SESSION[$name . '_page'] = $page;
        $_SESSION[$name . '_per_page'] = $per_page;
        if ($page == 1) {
            $sql .= "LIMIT $per_page";
        } else {
            $offset = ($page - 1) * $per_page;
            $sql .= "LIMIT $per_page OFFSET $offset";
        }
        $result = $conn->query($sql);
        while ($item = $result->fetch_assoc()) {
            $res[] = $item;
        }
    }
    return $res;
}

//SHOW COLUMNS FROM `products` LIKE 'currency'

function get_enum($table_name, $column_name)
{
    $enum = false;
    $conn = get_connection();
    $sql = "SHOW COLUMNS FROM `" . $table_name . "` LIKE '" . $column_name . "'";
    $result = $conn->query($sql);
    while ($item = $result->fetch_assoc()) {
        $enum = $item['Type'];
    }
    if ($enum) {
        preg_match("/\(\'(.*)\'\)/", $enum, $math);
        if (isset($math[1])) {
            $enum = explode('\',\'', $math[1]);
        }
    }
    return $enum;
}

function get_pagination($name)
{
    $res = [];
    if (isset($name) && !empty($name)) {
        $conn = get_connection();
        $sql = "SELECT count(*) as 'total' FROM $name";
        $result = $conn->query($sql);
        // $name.'_page'
        // $name.'_per_page'
        $res['total'] = $result->fetch_assoc()['total'];
        $page = $_SESSION[$name . '_page'];
        $per_page = $_SESSION[$name . '_per_page'];
        $res['page'] = $page;
        $res['per_page'] = $per_page;
        $res['min_number'] = (($page - 1) * $per_page) + 1;
        $max = $page * $per_page;
        $res['max_number'] = ($max < $res['total']) ? $max : $res['total'];
        $res['number_pages'] = ceil($res['total'] / $per_page);
        /* $item = $result->fetch_row();
         $res['total'] = $item[0];*/
    }
    return $res;
}

function get_one($name, $id)
{
    $res = false;
    if (isset($name) && !empty($name) && isset($id)) {
        $conn = get_connection();
        $sql = "SELECT * FROM $name WHERE id=$id";
        $result = $conn->query($sql);
        while ($item = $result->fetch_assoc()) {
            $res = $item;
        }
    }
    return $res;
}

function create_link($page)
{
    $url = $_SERVER['SCRIPT_NAME'];
    if ($page > 1) {
        $url .= '?page=' . $page;
    }
    return $url;
}

function admin_link($page)
{
    return ADMIN_URL . $page;
}

//create session
function login($login, $pass)
{
    if (isset($login) && isset($pass)) {
        $user = get_user($login, $pass);
        if ($user) {
            $_SESSION['admin'] = $user;
            return true;
        }
    }
    return false;
}

//check if user is logged in
function is_login()
{
    return (isset ($_SESSION ['admin']) && !empty($_SESSION ['admin'])) ? true : false;
}

//redirect to login
function redirect($url)
{
    if ($_SERVER['SCRIPT_NAME'] == '/dmitry/admin/login.php' && $url == "/dmitry/admin/login.php") {
        return;
    }
    $url = (is_login())? $url : ADMIN_URL.'/login.php';
    header("Location: $url");
    exit;
}

//logout user
function logout()
{
    if ($_SESSION ['admin']) {
        unset($_SESSION ['admin']);
    }
    redirect("/dmitry/admin/login.php");
}

function auth()
{
    if (isset($_POST["login"])) {
        if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
            if (login($_POST["username"], $_POST["password"])) {
                return redirect("/dmitry/admin/index.php");
            } else {
                $_SESSION["login_errors"] = "Incorrect login or password";
            }
        } else {
            $_SESSION["login_errors"] = "Login or password is empty";
        }
    }
}

function get_connection() // Create connection
{
    global $config;
    $host = $config['db']['host'];
    $user_name = $config['db']['user'];
    $pass = $config['db']['password'];
    $db_name = $config['db']['db_name'];
    $conn = new mysqli($host, $user_name, $pass, $db_name);
    if (isset($host) && isset($user_name) && isset($pass) && isset($db_name)) {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}

function get_user($login, $password, $role = 'admin')
{
    $conn = get_connection();
    $sql = "SELECT * FROM users WHERE  name='$login' AND password=MD5('$password') AND role='$role'";
    $result = $conn->query($sql);
    return (isset($result->num_rows) && $result->num_rows > 0) ? $result->fetch_assoc() : false;
}

function logout_link()
{
    return $_SERVER['SCRIPT_NAME'] . "?action=logout";
}