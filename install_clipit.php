<?php
session_start();
// FOR PHP < 5.4
if (!defined('PHP_SESSION_DISABLED')) {
    define('PHP_SESSION_DISABLED', 0);
}
if (!defined('PHP_SESSION_NONE')) {
    define('PHP_SESSION_NONE', 1);
}
if (!defined('PHP_SESSION_ACTIVE')) {
    define('PHP_SESSION_ACTIVE', 2);
}
if (!function_exists('session_status')) {
    function session_status(){
        if(!function_exists('session_id')) return PHP_SESSION_DISABLED;
        if (session_id() === "") return PHP_SESSION_NONE ;
        return PHP_SESSION_ACTIVE;
    }
}
?>

<html>
<body>
<div align="center">

<?php
if(!isset($_SESSION["status"])){
    $_SESSION["status"] = "wait";
    ?>
    <h1>ClipIt Install Script</h1>
    <h2>fill in the form below<br/>(recommended values already added)</h2>
    <form action="install_clipit.php" method="post">
        <table>
            <tr>
                <td>
                    <b>MySQL Host</b>
                </td>
                <td>
                    <input size=30 type="text" name="mysql_host" value="localhost">
                </td>
            </tr>
            <tr>
                <td>
                    <b>MySQL Schema</b>
                </td>
                <td>
                    <input size=30 type="text" name="mysql_schema" value="clipit">
                </td>
            </tr>
            <tr>
                <td>
                    <b>MySQL User</b>
                </td>
                <td>
                    <input size=30 type="text" name="mysql_user">
                </td>
            </tr>
            <tr>
                <td>
                    <b>MySQL Password</b>
                </td>
                <td>
                    <input size=30 type="text" name="mysql_password">
                </td>
            </tr>
        </table>
        <p><input type="submit"></p>
    </form>

<?php }else if($_SESSION["status"] == "wait") {
    $_SESSION = $_POST;
    $_SESSION["status"] = "install";
    header("Refresh:0; url=install_clipit.php", true, 303);
    ?>
    <h1>ClipIt Install Script</h1>
    <h2>ClipIt is being downloaded and configured</h2>
    <p>This process will take a few minutes, please don't close this page</p>

<?php } else if($_SESSION["status"] == "install") {
    unset($_SESSION["status"]);
    $git_url = "https://github.com/juxtalearn/clipit.git";
    $clipit_tag ="2.2";
    $mysql_host = $_SESSION["mysql_host"];
    $mysql_schema = $_SESSION["mysql_schema"];
    $mysql_user = $_SESSION["mysql_user"];
    $mysql_pass = $_SESSION["mysql_password"];
    ?>

    <h1>ClipIt Install Script</h1>
    <h2>Install Summary</h2>

    <p>cloning github repository...</p>
    <?php
    echo exec("git clone -b $clipit_tag --recursive $git_url git_tmp");
    echo exec("mv -f git_tmp/* .");
    echo exec("mv -f git/.* .");
    echo exec("rmdir git_tmp");
    ?>

    <p>configuring data folder and permissions...</p>
    <?php
    echo exec("mkdir data");
    echo exec("chmod -R 777 .");
    ?>

    <p>creating database...</p>
    <?php
    echo exec("mysql -h$mysql_host -u$mysql_user -p$mysql_pass -e'create database $mysql_schema;'");
    ?>

    <p>creating settings.php file...</p>
    <?php
    $file_name = fopen("engine/settings.php", "w") or die("Unable to open file!");
    $file_content = "<?php\n";
    $file_content .= "global \$CONFIG;\n";
    $file_content .= "if (!isset(\$CONFIG)) { \$CONFIG = new stdClass; }\n";
    $file_content .= "\$CONFIG->dbhost = '$mysql_host';\n";
    $file_content .= "\$CONFIG->dbuser = '$mysql_user';\n";
    $file_content .= "\$CONFIG->dbpass = '$mysql_pass';\n";
    $file_content .= "\$CONFIG->dbname = '$mysql_schema';\n";
    $file_content .= "\$CONFIG->dbprefix = 'clipit_';\n";
    $file_content .= "\$CONFIG->broken_mta = FALSE;\n";
    $file_content .= "\$CONFIG->db_disable_query_cache = FALSE;\n";
    $file_content .= "\$CONFIG->min_password_length = 6;\n";
    fwrite($file_name, $file_content);
    fclose($file_name);
    ?>

    <p><b>ClipIt has been installed correctly!</b></p>
    <form method='GET' action="install.php">
    <input type="hidden" name="step\" value="database">
    <input type="submit" value="Continue...">
    </form>
    </div></body></html>
<?php } ?>
</div>
</body>
</html>