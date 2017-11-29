// Set error reporting pretty high
error_reporting(E_ALL | E_STRICT);

// Get base, application and tests path
define('BASE_PATH',  dirname(__DIR__));

// Load autoloader
require_once BASE_PATH . '/autoload.php';