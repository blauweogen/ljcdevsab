<?php
/**
 * DLS Session
 *
 * A class to easily create and use a session without using PHP's native $_SESSION
 * (especially useful is register_globals is on since $_SESSION will be cleared by WordPress)
 *
 * Example Usage...
 *
 * function start_session()
 * {
 *    DLS_Session::start();
 * }
 * add_action('init', 'start_session');
 *
 * function end_session()
 * {
 *    DLS_Session::kill();
 * }
 * add_action('wp_logout', 'end_session');
 * add_action('wp_login', 'end_session');
 *
 * // Get session value
 * DLS_Session::get();
 *
 * // set a key/value pair to the session value
 * DLS_Session::set(array('foo' => 'bar'));
 *
 * @version 0.1
 * @author DLS Software Studios
 * @copyright 2014 DLS Software Studios <http://www.dlssoftwarestudios.com/>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

class DLS_Session
{

    private static $instance = null;
    public static $session_id;
    public $cookie_name = 'dls_session'; // also used as a transient prefix
    public $transient_expiration = 3600; // in seconds
    public $cookie_expiration = 86400; // in seconds

    /**
     * Initialize Class
     */
    private function __construct()
    {
    }

    public static function start()
    {
        if (self::$instance === null) self::$instance = new self;
        self::$session_id = self::$instance->_get_cookie();
        return self::$instance;
    }

    /**
     * Kill session
     */
    public static function kill()
    {
        if (self::$instance === null) self::$instance = new self;
        self::$instance->_get_cookie();
        delete_transient(self::$instance->_get_transient_name());
    }

    /**
     * Get session (whole array or by key)
     *
     * @param null $key
     * @return array|string|null if key doesn't exists, will return null
     */
    public static function get($key=null)
    {
        $session = self::$instance->_get_session_data();
        if ($key === null) return $session;
        if (isset($session[$key])) return $session[$key];
        return null;
    }

    /**
     * Set session
     *
     * @param array $new
     * @param bool $replace completely replace current session data with new data
     * @return bool false if value was not set and true if value was set
     */
    public static function set($new=array(), $replace=false)
    {
        $previous = self::$instance->_get_session_data();
        if ($replace === false) $new = array_merge($previous, $new);
        return self::$instance->_set_transient($new);
    }

    /**
     * Get the existing cookie or create a new one
     *
     * @return mixed cookie value
     */
    protected function _get_cookie()
    {
        if(!isset($_COOKIE[$this->cookie_name])) {
            $this->_set_session_id();
            setcookie($this->cookie_name, self::$session_id, time()+$this->cookie_expiration, '/');
            $_COOKIE[$this->cookie_name] = self::$session_id;
        }
        return $_COOKIE[$this->cookie_name];
    }

    /**
     * Set session transient
     *
     * @param array $data value of session
     * @return bool false if value was not set and true if value was set
     */
    protected function _set_transient($data=array())
    {
        return set_transient($this->_get_transient_name(), $data, $this->transient_expiration);
    }

    /**
     * Get session data
     *
     * @return array session data
     */
    protected function _get_session_data()
    {
        if (empty(self::$session_id)) return array();
        if (($session = get_transient($this->_get_transient_name())) === false) {
            $session = array();
        }
        return (array)$session;
    }

    /**
     * Generate a session id
     *
     * @return string session id
     */
    protected function _set_session_id()
    {
        require_once(ABSPATH . 'wp-includes/class-phpass.php');
        $hash = new PasswordHash(8, false);
        self::$session_id = md5($hash->get_random_bytes(32));
        return self::$session_id;
    }

    /**
     * Get transient name
     *
     * @return string transient name
     */
    protected function _get_transient_name()
    {
        return $this->cookie_name.'_'.self::$session_id;
    }

}